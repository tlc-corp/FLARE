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

namespace Flare\event\block;

use Flare\block\Block;
use Flare\event\Cancellable;
use Flare\item\Item;
use Flare\Player;
use Flare\tile\ItemFrame;

class ItemFrameDropItemEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;

	/** @var  Player */
	private $player;
	/** @var  Item */
	private $item;
	/** @var  ItemFrame */
	private $itemFrame;

	public function __construct(Player $player, Block $block, ItemFrame $itemFrame, Item $item){
		$this->player = $player;
		$this->block = $block;
		$this->itemFrame = $itemFrame;
		$this->item = $item;
	}

	public function getPlayer(){
		return $this->player;
	}

	public function getItemFrame(){
		return $this->itemFrame;
	}

	public function getItem(){
		return $this->item;
	}
}
