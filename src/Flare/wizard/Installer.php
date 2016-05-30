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

/**
 * Set-up wizard used on the first run
 * Can be disabled with --no-wizard
 */
namespace Flare\wizard;

use Flare\utils\Config;
use Flare\utils\Utils;

class Installer{
	const DEFAULT_NAME = "Minecraft: PE Server";
	const DEFAULT_PORT = 19132;
	const DEFAULT_MEMORY = 512;
	const DEFAULT_PLAYERS = 20;
	const DEFAULT_GAMEMODE = 0;
	const DEFAULT_LEVEL_NAME = "world";
	const DEFAULT_LEVEL_TYPE = "DEFAULT";

	private $defaultLang;

	public function __construct(){
		echo "[*] Genisys set-up wizard\n";
		echo "[*] Please select a language:\n";
		foreach(InstallerLang::$languages as $short => $native){
			echo " $native => $short\n";
		}
		do{
			echo "[?] Language (eng): ";
			$lang = strtolower($this->getInput("eng"));
			if(!isset(InstallerLang::$languages[$lang])){
				echo "[!] Couldn't find the language\n";
				$lang = false;
			}
			$this->defaultLang = $lang;
		}while($lang == false);
		$this->lang = new InstallerLang($lang);


		echo "[*] " . $this->lang->language_has_been_selected . "\n";

		if(!$this->showLicense()){
			@\Flare\kill(getmypid());
			exit(-1);
		}

		echo "[?] " . $this->lang->skip_installer . " (y/N): ";
		if(strtolower($this->getInput()) === "y"){
			return;
		}
		echo "\n";
		$this->welcome();
		$this->generateBaseConfig();
		$this->generateUserFiles();

		$this->networkFunctions();

		$this->endWizard();
	}

	public function getDefaultLang(){
		return $this->defaultLang;
	}

	private function showLicense(){
		echo $this->lang->welcome_to_Flare . "\n";
		echo <<<LICENSE

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Lesser General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

LICENSE;
		echo "\n[?] " . $this->lang->accept_license . " (y/N): ";
		if(strtolower($this->getInput("n")) != "y"){
			echo "[!] " . $this->lang->you_have_to_accept_the_license . "\n";
			sleep(5);

			return false;
		}

		return true;
	}

	private function welcome(){
		echo "[*] " . $this->lang->setting_up_server_now . "\n";
		echo "[*] " . $this->lang->default_values_info . "\n";
		echo "[*] " . $this->lang->server_properties . "\n";

	}

	private function generateBaseConfig(){
		$config = new Config(\Flare\DATA . "server.properties", Config::PROPERTIES);
		echo "[?] " . $this->lang->name_your_server . " (" . self::DEFAULT_NAME . "): ";
		$config->set("server-name", $this->getInput(self::DEFAULT_NAME));
		$config->set("motd", $this->getInput(self::DEFAULT_NAME));
		echo "[*] " . $this->lang->port_warning . "\n";
		do{
			echo "[?] " . $this->lang->server_port . " (" . self::DEFAULT_PORT . "): ";
			$port = (int) $this->getInput(self::DEFAULT_PORT);
			if($port <= 0 or $port > 65535){
				echo "[!] " . $this->lang->invalid_port . "\n";
			}
		}while($port <= 0 or $port > 65535);
		$config->set("server-port", $port);
		
		echo "[?] " . $this->lang->level_name . " (" . self::DEFAULT_LEVEL_NAME . "): ";
		$config->set("level-name", $this->getInput(self::DEFAULT_LEVEL_NAME));
		
		do{
			echo "[?] " . $this->lang->level_type . " (" . self::DEFAULT_LEVEL_TYPE . "): ";
			$type = (string) $this->getInput(self::DEFAULT_LEVEL_TYPE);
			if($type != "flat" or $type != "default"){
				echo "[!] " . $this->lang->invalid_level_type . "\n";
			}
		}while($type == "flat" or $type == "default");
		$config->set("level-type", $type);
		
		/*echo "[*] " . $this->lang->ram_warning . "\n";
		echo "[?] " . $this->lang->server_ram . " (" . self::DEFAULT_MEMORY . "): ";
		$config->set("memory-limit", ((int) $this->getInput(self::DEFAULT_MEMORY)) . "M");*/
		echo "[*] " . $this->lang->gamemode_info . "\n";
		do{
			echo "[?] " . $this->lang->default_gamemode . ": (" . self::DEFAULT_GAMEMODE . "): ";
			$gamemode = (int) $this->getInput(self::DEFAULT_GAMEMODE);
		}while($gamemode < 0 or $gamemode > 3);
		$config->set("gamemode", $gamemode);
		echo "[?] " . $this->lang->max_players . " (" . self::DEFAULT_PLAYERS . "): ";
		$config->set("max-players", (int) $this->getInput(self::DEFAULT_PLAYERS));
		echo "[*] " . $this->lang->spawn_protection_info . "\n";
		echo "[?] " . $this->lang->spawn_protection . " (Y/n): ";
		if(strtolower($this->getInput("y")) == "n"){
			$config->set("spawn-protection", -1);
		}else{
			$config->set("spawn-protection", 16);
		}
		
		echo "[?] " . $this->lang->announce_player_achievements . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("announce-player-achievements", "on");
		}else{
			$config->set("announce-player-achievements", "off");
		}
		$config->save();
	}

	private function generateUserFiles(){
		echo "[*] " . $this->lang->op_info . "\n";
		echo "[?] " . $this->lang->op_who . ": ";
		$op = strtolower($this->getInput(""));
		if($op === ""){
			echo "[!] " . $this->lang->op_warning . "\n";
		}else{
			$ops = new Config(\Flare\DATA . "ops.txt", Config::ENUM);
			$ops->set($op, true);
			$ops->save();
		}
		echo "[*] " . $this->lang->whitelist_info . "\n";
		echo "[?] " . $this->lang->whitelist_enable . " (y/N): ";
		$config = new Config(\Flare\DATA . "server.properties", Config::PROPERTIES);
		if(strtolower($this->getInput("n")) === "y"){
			echo "[!] " . $this->lang->whitelist_warning . "\n";
			$config->set("white-list", true);
		}else{
			$config->set("white-list", false);
		}
		$config->save();
	}

	private function networkFunctions(){
		$config = new Config(\Flare\DATA . "server.properties", Config::PROPERTIES);
		echo "[!] " . $this->lang->query_warning1 . "\n";
		echo "[!] " . $this->lang->query_warning2 . "\n";
		echo "[?] " . $this->lang->query_disable . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("enable-query", false);
		}else{
			$config->set("enable-query", true);
		}

		echo "[*] " . $this->lang->rcon_info . "\n";
		echo "[?] " . $this->lang->rcon_enable . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("enable-rcon", true);
			$password = substr(base64_encode(@Utils::getRandomBytes(20, false)), 3, 10);
			$config->set("rcon.password", $password);
			echo "[*] " . $this->lang->rcon_password . ": " . $password . "\n";
		}else{
			$config->set("enable-rcon", false);
		}

		/*echo "[*] " . $this->lang->usage_info . "\n";
		echo "[?] " . $this->lang->usage_disable . " (y/N): ";
		if(strtolower($this->getInput("n")) === "y"){
			$config->set("send-usage", false);
		}else{
			$config->set("send-usage", true);
		}*/
		$config->save();


		echo "[*] " . $this->lang->ip_get . "\n";

		$externalIP = Utils::getIP();
		$internalIP = gethostbyname(trim(`hostname`));

		echo "[!] " . $this->lang->get("ip_warning", ["{{EXTERNAL_IP}}", "{{INTERNAL_IP}}"], [$externalIP, $internalIP]) . "\n";
		echo "[!] " . $this->lang->ip_confirm;
		$this->getInput();
	}

	private function endWizard(){
		echo "[*] " . $this->lang->you_have_finished . "\n";
		echo "[*] " . $this->lang->Flare_plugins . "\n";
		echo "[*] " . $this->lang->Flare_will_start . "\n\n\n";
		sleep(4);
	}

	private function getInput($default = ""){
		$input = trim(fgets(STDIN));

		return $input === "" ? $default : $input;
	}


}