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

namespace Flare\tile;

use Flare\inventory\EnchantInventory;
use Flare\inventory\InventoryHolder;
use Flare\item\Item;
use Flare\level\format\FullChunk;
use Flare\nbt\tag\CompoundTag;
use Flare\nbt\tag\IntTag;
use Flare\nbt\tag\StringTag;

class EnchantTable extends Spawnable implements InventoryHolder, Nameable{
	/** @var EnchantInventory */
	protected $inventory;

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new EnchantInventory($this);
	}

	public function getName() : string{
		return $this->hasName() ? $this->namedtag->CustomName->getValue() : "Enchanting Table";
	}

	public function hasName(){
		return isset($this->namedtag->CustomName);
	}

	public function setName($str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}

		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}

	/**
	 * @return EnchantInventory
	 */
	public function getInventory(){
		return $this->inventory;
	}

	public function getSpawnCompound(){
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::ENCHANT_TABLE),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);

		if($this->hasName()){
			$nbt->CustomName = $this->namedtag->CustomName;
		}

		return $nbt;
	}
}
