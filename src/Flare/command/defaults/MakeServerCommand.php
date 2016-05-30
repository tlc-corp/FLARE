<?php
namespace Flare\command\defaults;

use Flare\command\CommandSender;
use Flare\plugin\Plugin;
use Flare\Server;
use Flare\utils\TextFormat;
use Flare\network\protocol\Info;

class MakeServerCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"Creates a Flare Phar",
			"/makeserver (nogz)"
		);
		$this->setPermission("Flare.command.makeserver");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}

		$server = $sender->getServer();
		$pharPath = Server::getInstance()->getPluginPath() . DIRECTORY_SEPARATOR . "Genisys" . DIRECTORY_SEPARATOR . $server->getName() . "_" . $server->getFlareVersion() . ".phar";
		if(file_exists($pharPath)){
			$sender->sendMessage("Phar file already exists, overwriting...");
			@unlink($pharPath);
		}
		$phar = new \Phar($pharPath);
		$phar->setMetadata([
			"name" => $server->getName(),
			"version" => $server->getFlareVersion(),
			"api" => $server->getApiVersion(),
			"geniapi" => $server->getGeniApiVersion(),
			"minecraft" => $server->getVersion(),
			"protocol" => Info::CURRENT_PROTOCOL,
			"creator" => "Genisys MakeServerCommand",
			"creationDate" => time()
		]);
		$phar->setStub('<?php define("Flare\\\\PATH", "phar://". __FILE__ ."/"); require_once("phar://". __FILE__ ."/src/Flare/Flare.php");  __HALT_COMPILER();');
		$phar->setSignatureAlgorithm(\Phar::SHA1);
		$phar->startBuffering();

		$filePath = substr(\Flare\PATH, 0, 7) === "phar://" ? \Flare\PATH : realpath(\Flare\PATH) . "/";
		$filePath = rtrim(str_replace("\\", "/", $filePath), "/") . "/";
		foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath . "src")) as $file){
			$path = ltrim(str_replace(["\\", $filePath], ["/", ""], $file), "/");
			if($path{0} === "." or strpos($path, "/.") !== false or substr($path, 0, 4) !== "src/"){
				continue;
			}
			$phar->addFile($file, $path);
			$sender->sendMessage("[Genisys] Adding $path");
		}
		foreach($phar as $file => $finfo){
			/** @var \PharFileInfo $finfo */
			if($finfo->getSize() > (1024 * 512)){
				$finfo->compress(\Phar::GZ);
			}
		}
		if(!isset($args[0]) or (isset($args[0]) and $args[0] != "nogz")){
			$phar->compressFiles(\Phar::GZ);
		}
		$phar->stopBuffering();

		$sender->sendMessage($server->getName() . " " . $server->getFlareVersion() . " Phar file has been created on " . $pharPath);

		return true;
	}
}
