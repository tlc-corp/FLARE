<?php
namespace Flare\command\defaults;

use Flare\command\Command;
use Flare\command\CommandSender;
use Flare\event\TranslationContainer;
use Flare\Player;
use Flare\utils\TextFormat;

class BanCidCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%Flare.command.bancid.description",
			"%commands.bancid.usage"
		);
		$this->setPermission("Flare.command.bancid");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$cid = array_shift($args);
		$reason = implode(" ", $args);

		$sender->getServer()->getCIDBans()->addBan($cid, $reason, null, $sender->getName());

		$player = null;

		foreach($sender->getServer()->getOnlinePlayers() as $p){
			if($p->getClientId() == $cid) {
				$p->kick($reason !== "" ? "Banned by admin. Reason:" . $reason : "Banned by admin.");
				$player = $p;
				break;
			}
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.bancid.success", [$player !== null ? $player->getName() : $cid]));

		return true;
	}
}
