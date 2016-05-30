<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Flare Team
 * @link http://www.Flare.net/
 *
 *
*/

namespace Flare\command\defaults;

use Flare\command\Command;
use Flare\command\CommandSender;
use Flare\event\TranslationContainer;


class StopCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%Flare.command.stop.description",
			"%commands.stop.usage"
		);
		$this->setPermission("Flare.command.stop");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$msg = "";
		if(isset($args[0])){
			$msg = $args[0];
		}

		$restart = false;
		if(isset($args[1])){
			if($args[0] == 'force'){
				$restart = true;
			}else{
				$restart = false;
			}
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.stop.start"));

		$sender->getServer()->shutdown($restart, $msg);

		return true;
	}
}