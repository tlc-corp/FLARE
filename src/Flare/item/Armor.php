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


namespace Flare\item;

use Flare\nbt\tag\CompoundTag;
use Flare\nbt\tag\IntTag;
use Flare\utils\Color;

abstract class Armor extends Item{

	public function getMaxStackSize() : int {
		return 1;
	}

	public function isArmor(){
		return true;
	}

	public function setCustomColor(Color $color){
		if(($hasTag = $this->hasCompoundTag())){
			$tag = $this->getNamedTag();
		}else{
			$tag = new CompoundTag("", []);
		}
		$tag->customColor = new IntTag("customColor", $color->getColorCode());
		$this->setCompoundTag($tag);
	}

	public function getCustomColor(){
		if(!$this->hasCompoundTag()) return null;
		$tag = $this->getNamedTag();
		if(isset($tag->customColor)){
			return $tag["customColor"];
		}
		return null;
	}

	public function clearCustomColor(){
		if(!$this->hasCompoundTag()) return;
		$tag = $this->getNamedTag();
		if(isset($tag->customColor)){
			unset($tag->customColor);
		}
		$this->setCompoundTag($tag);
	}
}