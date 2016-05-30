<?php
namespace Flare\command\defaults;

use Flare\command\Command;
use Flare\command\CommandSender;
use Flare\event\TranslationContainer;
use Flare\Player;
use Flare\utils\TextFormat;

class BancidbynameCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%Flare.command.bancidbyname.description",
			"%commands.bancidbyname.usage"
		);
		$this->setPermission("Flare.command.bancidbyname");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$name = array_shift($args);
		$reason = implode(" ", $args);
		
		if ($sender->getServer()->getPlayer($name) instanceof Player) $target = $sender->getServer()->getPlayer($name);
		else return false;

		$sender->getServer()->getCIDBans()->addBan($target->getClientId(), $reason, null, $sender->getName());

		$target->kick($reason !== "" ? "Banned by admin. Reason:" . $reason : "Banned by admin.");

		Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.bancidbyname.success", [$target !== null ? $target->getName() : $name]));

		return true;
	}
}
