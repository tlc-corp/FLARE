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

use Flare\entity\Entity;
use Flare\item\Item;
use Flare\level\Level;
use Flare\math\Vector3;
use Flare\nbt\tag\ByteTag;
use Flare\nbt\tag\CompoundTag;
use Flare\nbt\tag\DoubleTag;
use Flare\nbt\tag\ListTag;
use Flare\nbt\tag\FloatTag;
use Flare\nbt\tag\IntTag;
use Flare\Player;

abstract class Fallable extends Solid{

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$ret = $this->getLevel()->setBlock($this, $this, true, true);

		return $ret;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(Vector3::SIDE_DOWN);
			if($down->getId() === self::AIR or ($down instanceof Liquid)){
				$fall = Entity::createEntity("FallingSand", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", $this->x + 0.5),
						new DoubleTag("", $this->y),
						new DoubleTag("", $this->z + 0.5)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", 0),
						new FloatTag("", 0)
					]),
					"TileID" => new IntTag("TileID", $this->getId()),
					"Data" => new ByteTag("Data", $this->getDamage()),
				]));

				$fall->spawnToAll();
			}
		}
	}
}