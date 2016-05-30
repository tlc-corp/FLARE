<?php

/*
 *
 *  _____   _____   __   _   _   _____  __    __  _____
 * /  ___| | ____| |  \ | | | | /  ___/ \ \  / / /  ___/
 * | |     | |__   |   \| | | | | |___   \ \/ /  | |___
 * | |  _  |  __|  | |\   | | | \___  \   \  /   \___  \
 * | |_| | | |___  | | \  | | |  ___| |   / /     ___| |
 * \_____/ |_____| |_|  \_| |_| /_____/  /_/     /_____/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author iTX Technologies
 * @link https://mcper.cn
 *
 */

namespace Flare\command\defaults;

use Flare\command\CommandSender;
use Flare\event\TranslationContainer;
use Flare\level\Level;
use Flare\level\weather\Weather;
use Flare\Player;
use Flare\utils\TextFormat;

class WeatherCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%Flare.command.weather.description",
			"%Flare.command.weather.usage"
		);
		$this->setPermission("Flare.command.weather");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($sender instanceof Player){
			$wea = Weather::getWeatherFromString($args[0]);
			if(!isset($args[1])) $duration = mt_rand(min($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax), max($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax));
			else $duration = (int) $args[1];
			if($wea >= 0 and $wea <= 3){
				$sender->getLevel()->getWeather()->setWeather($wea, $duration);
				$sender->sendMessage(new TranslationContainer("Flare.command.weather.changed", [$sender->getLevel()->getFolderName()]));
				return true;
				/*if(WeatherManager::isRegistered($sender->getLevel())){
					$sender->getLevel()->getWeather()->setWeather($wea, $duration);
					$sender->sendMessage(new TranslationContainer("Flare.command.weather.changed", [$sender->getLevel()->getFolderName()]));
					return true;
				}else{
					$sender->sendMessage(new TranslationContainer("Flare.command.weather.noregistered", [$sender->getLevel()->getFolderName()]));
					return false;
				}*/
			}else{
				$sender->sendMessage(TextFormat::RED . "%Flare.command.weather.invalid");
				return false;
			}
		}

		if(count($args) < 2){
			$sender->sendMessage(TextFormat::RED . "%Flare.command.weather.wrong");
			return false;
		}

		$level = $sender->getServer()->getLevelByName($args[0]);
		if(!$level instanceof Level){
			$sender->sendMessage(TextFormat::RED . "%Flare.command.weather.invalid.level");
			return false;
		}

		$wea = Weather::getWeatherFromString($args[1]);
		if(!isset($args[1])) $duration = mt_rand(min($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax), max($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax));
		else $duration = (int) $args[1];
		if($wea >= 0 and $wea <= 3){
			$level->getWeather()->setWeather($wea, $duration);
			$sender->sendMessage(new TranslationContainer("Flare.command.weather.changed", [$level->getFolderName()]));
			return true;
			/*if(WeatherManager::isRegistered($level)){
				$level->getWeather()->setWeather($wea, $duration);
				$sender->sendMessage(new TranslationContainer("Flare.command.weather.changed", [$level->getFolderName()]));
				return true;
			}else{
				$sender->sendMessage(new TranslationContainer("Flare.command.weather.noregistered", [$level->getFolderName()]));
				return false;
			}*/
		}else{
			$sender->sendMessage(TextFormat::RED . "%Flare.command.weather.invalid");
			return false;
		}
	}
}
