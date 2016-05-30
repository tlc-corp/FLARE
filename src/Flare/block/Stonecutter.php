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

namespace Flare\block;

use Flare\item\Item;
use Flare\item\Tool;
use Flare\Player;

//TODO: check orientation
class Stonecutter extends Solid{

	protected $id = self::STONECUTTER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Stonecutter";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		if($player instanceof Player){
			$player->craftingType = 2;
		}

		return true;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 1){
			return [
				[Item::STONECUTTER, 0, 1],
			];
		}else{
			return [];
		}
	}
}