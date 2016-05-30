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

use Flare\command\CommandSender;
use Flare\Player;
use Flare\utils\TextFormat;
use Flare\utils\Utils;

class StatusCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%Flare.command.status.description",
			"%Flare.command.status.usage"
		);
		$this->setPermission("Flare.command.status");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$mUsage = Utils::getMemoryUsage(true);
		$rUsage = Utils::getRealMemoryUsage();

		$server = $sender->getServer();
		$onlineCount = 0;
		foreach($sender->getServer()->getOnlinePlayers() as $player){
			if($player->isOnline() and (!($sender instanceof Player) or $sender->canSee($player))){
				++$onlineCount;
			}
		}
		$sender->sendMessage(TextFormat::GREEN . "---- " . TextFormat::WHITE . "%Flare.command.status.title" . TextFormat::GREEN . " ----");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.player" . TextFormat::GREEN ." ". $onlineCount . "/" . $sender->getServer()->getMaxPlayers());

		$time = microtime(true) - \Flare\START_TIME;

		$seconds = floor($time % 60);
		$minutes = null;
		$hours = null;
		$days = null;

		if($time >= 60){
			$minutes = floor(($time % 3600) / 60);
			if($time >= 3600){
				$hours = floor(($time % (3600 * 24)) / 3600);
				if($time >= 3600 * 24){
					$days = floor($time / (3600 * 24));
				}
			}
		}

		$uptime = ($minutes !== null ?
				($hours !== null ?
					($days !== null ?
						"$days %Flare.command.status.days "
						: "") . "$hours %Flare.command.status.hours "
					: "") . "$minutes %Flare.command.status.minutes "
				: "") . "$seconds %Flare.command.status.seconds";

		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.uptime " . TextFormat::RED . $uptime);

		$tpsColor = TextFormat::GREEN;
		if($server->getTicksPerSecondAverage() < 10){
			$tpsColor = TextFormat::GOLD;
		}elseif($server->getTicksPerSecondAverage() < 1){
			$tpsColor = TextFormat::RED;
		}

		$tpsColour = TextFormat::GREEN;
		if($server->getTicksPerSecond() < 10){
			$tpsColour = TextFormat::GOLD;
		}elseif($server->getTicksPerSecond() < 1){
			$tpsColour = TextFormat::RED;
		}

		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.AverageTPS " . $tpsColor . $server->getTicksPerSecondAverage() . " (" . $server->getTickUsageAverage() . "%)");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.CurrentTPS " . $tpsColour . $server->getTicksPerSecond() . " (" . $server->getTickUsage() . "%)");

		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Networkupload " . TextFormat::RED . \round($server->getNetwork()->getUpload() / 1024, 2) . " kB/s");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Networkdownload " . TextFormat::RED . \round($server->getNetwork()->getDownload() / 1024, 2) . " kB/s");

		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Threadcount " . TextFormat::RED . Utils::getThreadCount());

		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Mainmemory " . TextFormat::RED . number_format(round(($mUsage[0] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Totalmemory " . TextFormat::RED . number_format(round(($mUsage[1] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Totalvirtualmemory " . TextFormat::RED . number_format(round(($mUsage[2] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Heapmemory " . TextFormat::RED . number_format(round(($rUsage[0] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Maxmemorysystem " . TextFormat::RED . number_format(round(($mUsage[2] / 1024) / 1024, 2)) . " MB.");

		if($server->getProperty("memory.global-limit") > 0){
			$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.Maxmemorymanager " . TextFormat::RED . number_format(round($server->getProperty("memory.global-limit"), 2)) . " MB.");
		}
		foreach($server->getLevels() as $level){
			$sender->sendMessage(TextFormat::GOLD . "%Flare.command.status.World \"" . $level->getFolderName() . "\"" . ($level->getFolderName() !== $level->getName() ? " (" . $level->getName() . ")" : "") . ": " .
				TextFormat::RED . number_format(count($level->getChunks())) . TextFormat::GREEN . " %Flare.command.status.chunks " .
				TextFormat::RED . number_format(count($level->getEntities())) . TextFormat::GREEN . " %Flare.command.status.entities " .
				TextFormat::RED . number_format(count($level->getTiles())) . TextFormat::GREEN . " %Flare.command.status.tiles " .
				"%Flare.command.status.Time " . (($level->getTickRate() > 1 or $level->getTickRateTime() > 40) ? TextFormat::RED : TextFormat::YELLOW) . round($level->getTickRateTime(), 2) . "%Flare.command.status.ms" . ($level->getTickRate() > 1 ? " (tick rate " . $level->getTickRate() . ")" : "")
			);
		}

		return true;
	}
}
