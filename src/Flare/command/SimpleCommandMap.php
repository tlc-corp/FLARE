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

namespace Flare\command;

use Flare\command\defaults\BanCommand;
use Flare\command\defaults\BanIpCommand;
use Flare\command\defaults\BanListCommand;
use Flare\command\defaults\BiomeCommand;
use Flare\command\defaults\CaveCommand;
use Flare\command\defaults\ChunkInfoCommand;
use Flare\command\defaults\DefaultGamemodeCommand;
use Flare\command\defaults\DeopCommand;
use Flare\command\defaults\DifficultyCommand;
use Flare\command\defaults\DumpMemoryCommand;
use Flare\command\defaults\EffectCommand;
use Flare\command\defaults\EnchantCommand;
use Flare\command\defaults\GamemodeCommand;
use Flare\command\defaults\GarbageCollectorCommand;
use Flare\command\defaults\GiveCommand;
use Flare\command\defaults\HelpCommand;
use Flare\command\defaults\KickCommand;
use Flare\command\defaults\KillCommand;
use Flare\command\defaults\ListCommand;
use Flare\command\defaults\LoadPluginCommand;
use Flare\command\defaults\LvdatCommand;
use Flare\command\defaults\MeCommand;
use Flare\command\defaults\OpCommand;
use Flare\command\defaults\PardonCommand;
use Flare\command\defaults\PardonIpCommand;
use Flare\command\defaults\ParticleCommand;
use Flare\command\defaults\PluginsCommand;
use Flare\command\defaults\ReloadCommand;
use Flare\command\defaults\SaveCommand;
use Flare\command\defaults\SaveOffCommand;
use Flare\command\defaults\SaveOnCommand;
use Flare\command\defaults\SayCommand;
use Flare\command\defaults\SeedCommand;
use Flare\command\defaults\SetBlockCommand;
use Flare\command\defaults\SetWorldSpawnCommand;
use Flare\command\defaults\SpawnpointCommand;
use Flare\command\defaults\StatusCommand;
use Flare\command\defaults\StopCommand;
use Flare\command\defaults\SummonCommand;
use Flare\command\defaults\TeleportCommand;
use Flare\command\defaults\TellCommand;
use Flare\command\defaults\TimeCommand;
use Flare\command\defaults\TimingsCommand;
use Flare\command\defaults\VanillaCommand;
use Flare\command\defaults\VersionCommand;
use Flare\command\defaults\WhitelistCommand;
use Flare\command\defaults\XpCommand;
use Flare\command\defaults\FillCommand;
use Flare\event\TranslationContainer;
use Flare\Player;
use Flare\Server;
use Flare\utils\MainLogger;
use Flare\utils\TextFormat;

use Flare\command\defaults\MakeServerCommand;
use Flare\command\defaults\ExtractPluginCommand;
use Flare\command\defaults\ExtractPharCommand;
use Flare\command\defaults\MakePluginCommand;
use Flare\command\defaults\BancidbynameCommand;
use Flare\command\defaults\BanipbynameCommand;
use Flare\command\defaults\BanCidCommand;
use Flare\command\defaults\PardonCidCommand;
use Flare\command\defaults\WeatherCommand;

class SimpleCommandMap implements CommandMap{

	/**
	 * @var Command[]
	 */
	protected $knownCommands = [];

	/** @var Server */
	private $server;

	public function __construct(Server $server){
		$this->server = $server;
		$this->setDefaultCommands();
	}

	private function setDefaultCommands(){
		$this->register("Flare", new WeatherCommand("weather"));

		$this->register("Flare", new BanCidCommand("bancid"));
		$this->register("Flare", new PardonCidCommand("pardoncid"));
		$this->register("Flare", new BancidbynameCommand("bancidbyname"));
		$this->register("Flare", new BanipbynameCommand("banipbyname"));

		$this->register("Flare", new ExtractPharCommand("extractphar"));
		$this->register("Flare", new ExtractPluginCommand("extractplugin"));
		$this->register("Flare", new MakePluginCommand("makeplugin"));
		$this->register("Flare", new MakeServerCommand("ms"));
		//$this->register("Flare", new MakeServerCommand("makeserver"));
		$this->register("Flare", new ExtractPluginCommand("ep"));
		$this->register("Flare", new MakePluginCommand("mp"));

		$this->register("Flare", new LoadPluginCommand("loadplugin"));

		$this->register("Flare", new LvdatCommand("lvdat"));
		$this->register("Flare", new BiomeCommand("biome"));
		$this->register("Flare", new CaveCommand("cave"));
		$this->register("Flare", new ChunkInfoCommand("chunkinfo"));

		$this->register("Flare", new VersionCommand("version"));
		$this->register("Flare", new FillCommand("fill"));
		$this->register("Flare", new PluginsCommand("plugins"));
		$this->register("Flare", new SeedCommand("seed"));
		$this->register("Flare", new HelpCommand("help"));
		$this->register("Flare", new StopCommand("stop"));
		$this->register("Flare", new TellCommand("tell"));
		$this->register("Flare", new DefaultGamemodeCommand("defaultgamemode"));
		$this->register("Flare", new BanCommand("ban"));
		$this->register("Flare", new BanIpCommand("ban-ip"));
		$this->register("Flare", new BanListCommand("banlist"));
		$this->register("Flare", new PardonCommand("pardon"));
		$this->register("Flare", new PardonIpCommand("pardon-ip"));
		$this->register("Flare", new SayCommand("say"));
		$this->register("Flare", new MeCommand("me"));
		$this->register("Flare", new ListCommand("list"));
		$this->register("Flare", new DifficultyCommand("difficulty"));
		$this->register("Flare", new KickCommand("kick"));
		$this->register("Flare", new OpCommand("op"));
		$this->register("Flare", new DeopCommand("deop"));
		$this->register("Flare", new WhitelistCommand("whitelist"));
		$this->register("Flare", new SaveOnCommand("save-on"));
		$this->register("Flare", new SaveOffCommand("save-off"));
		$this->register("Flare", new SaveCommand("save-all"));
		$this->register("Flare", new GiveCommand("give"));
		$this->register("Flare", new EffectCommand("effect"));
		$this->register("Flare", new EnchantCommand("enchant"));
		$this->register("Flare", new ParticleCommand("particle"));
		$this->register("Flare", new GamemodeCommand("gamemode"));
		$this->register("Flare", new KillCommand("kill"));
		$this->register("Flare", new SpawnpointCommand("spawnpoint"));
		$this->register("Flare", new SetWorldSpawnCommand("setworldspawn"));
		$this->register("Flare", new SummonCommand("summon"));
		$this->register("Flare", new TeleportCommand("tp"));
		$this->register("Flare", new TimeCommand("time"));
		$this->register("Flare", new TimingsCommand("timings"));
		$this->register("Flare", new ReloadCommand("reload"));
		$this->register("Flare", new XpCommand("xp"));
		$this->register("Flare", new SetBlockCommand("setblock"));

		if($this->server->getProperty("debug.commands", false)){
			$this->register("Flare", new StatusCommand("status"));
			$this->register("Flare", new GarbageCollectorCommand("gc"));
			$this->register("Flare", new DumpMemoryCommand("dumpmemory"));
		}
	}


	public function registerAll($fallbackPrefix, array $commands){
		foreach($commands as $command){
			$this->register($fallbackPrefix, $command);
		}
	}

	public function register($fallbackPrefix, Command $command, $label = null){
		if($label === null){
			$label = $command->getName();
		}
		$label = strtolower(trim($label));
		$fallbackPrefix = strtolower(trim($fallbackPrefix));

		$registered = $this->registerAlias($command, false, $fallbackPrefix, $label);

		$aliases = $command->getAliases();
		foreach($aliases as $index => $alias){
			if(!$this->registerAlias($command, true, $fallbackPrefix, $alias)){
				unset($aliases[$index]);
			}
		}
		$command->setAliases($aliases);

		if(!$registered){
			$command->setLabel($fallbackPrefix . ":" . $label);
		}

		$command->register($this);

		return $registered;
	}

	private function registerAlias(Command $command, $isAlias, $fallbackPrefix, $label){
		$this->knownCommands[$fallbackPrefix . ":" . $label] = $command;
		if(($command instanceof VanillaCommand or $isAlias) and isset($this->knownCommands[$label])){
			return false;
		}

		if(isset($this->knownCommands[$label]) and $this->knownCommands[$label]->getLabel() !== null and $this->knownCommands[$label]->getLabel() === $label){
			return false;
		}

		if(!$isAlias){
			$command->setLabel($label);
		}

		$this->knownCommands[$label] = $command;

		return true;
	}

	private function dispatchAdvanced(CommandSender $sender, Command $command, $label, array $args, $offset = 0){
		if(isset($args[$offset])){
			$argsTemp = $args;
			switch($args[$offset]){
				case "@a":
					$p = $this->server->getOnlinePlayers();
					if(count($p) <= 0){
						$sender->sendMessage(TextFormat::RED . "No players online"); //TODO: add language
					}else{
						foreach($p as $player){
							$argsTemp[$offset] = $player->getName();
							$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
						}
					}
					break;
				case "@r":
					$players = $this->server->getOnlinePlayers();
					if(count($players) > 0){
						$argsTemp[$offset] = $players[array_rand($players)]->getName();
						$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
					}
					break;
				case "@p":
					if($sender instanceof Player){
						$distance = 5;
						$nearestPlayer = $sender;
						foreach($sender->getLevel()->getPlayers() as $p){
							if($p != $sender and (($dis = $p->distance($sender)) < $distance)){
								$distance = $dis;
								$nearestPlayer = $p;
							}
						}
						if($distance != 5){
							$argsTemp[$offset] = $nearestPlayer->getName();
							$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
						}else $sender->sendMessage(TextFormat::RED . "No player is near you!");
					}else $sender->sendMessage(TextFormat::RED . "You must be a player!"); //TODO: add language
					break;
				default:
					$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
			}
		}else $command->execute($sender, $label, $args);
	}

	public function dispatch(CommandSender $sender, $commandLine){
		$args = explode(" ", $commandLine);

		if(count($args) === 0){
			return false;
		}

		$sentCommandLabel = strtolower(array_shift($args));
		$target = $this->getCommand($sentCommandLabel);

		if($target === null){
			return false;
		}

		$target->timings->startTiming();
		try{
			if($this->server->advancedCommandSelector){
				$this->dispatchAdvanced($sender, $target, $sentCommandLabel, $args);
			}else{
				$target->execute($sender, $sentCommandLabel, $args);
			}
		}catch(\Throwable $e){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.exception"));
			$this->server->getLogger()->critical($this->server->getLanguage()->translateString("Flare.command.exception", [$commandLine, (string) $target, $e->getMessage()]));
			$logger = $sender->getServer()->getLogger();
			if($logger instanceof MainLogger){
				$logger->logException($e);
			}
		}
		$target->timings->stopTiming();

		return true;
	}

	public function clearCommands(){
		foreach($this->knownCommands as $command){
			$command->unregister($this);
		}
		$this->knownCommands = [];
		$this->setDefaultCommands();
	}

	public function getCommand($name){
		if(isset($this->knownCommands[$name])){
			return $this->knownCommands[$name];
		}

		return null;
	}

	/**
	 * @return Command[]
	 */
	public function getCommands(){
		return $this->knownCommands;
	}


	/**
	 * @return void
	 */
	public function registerServerAliases(){
		$values = $this->server->getCommandAliases();

		foreach($values as $alias => $commandStrings){
			if(strpos($alias, ":") !== false or strpos($alias, " ") !== false){
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("Flare.command.alias.illegal", [$alias]));
				continue;
			}

			$targets = [];

			$bad = "";
			foreach($commandStrings as $commandString){
				$args = explode(" ", $commandString);
				$command = $this->getCommand($args[0]);

				if($command === null){
					if(strlen($bad) > 0){
						$bad .= ", ";
					}
					$bad .= $commandString;
				}else{
					$targets[] = $commandString;
				}
			}

			if(strlen($bad) > 0){
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("Flare.command.alias.notFound", [$alias, $bad]));
				continue;
			}

			//These registered commands have absolute priority
			if(count($targets) > 0){
				$this->knownCommands[strtolower($alias)] = new FormattedCommandAlias(strtolower($alias), $targets);
			}else{
				unset($this->knownCommands[strtolower($alias)]);
			}

		}
	}


}
