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

namespace Flare\event\player;

use Flare\block\Block;
use Flare\event\Cancellable;
use Flare\item\Item;
use Flare\Player;

class PlayerGlassBottleEvent extends PlayerEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Block */
    private $target;
    /** @var Item */
    private $item;

    /**
     * @param Player $Player
     * @param Block  $target
     * @param Item   $itemInHand
     */
    public function __construct(Player $Player, Block $target, Item $itemInHand){
        $this->player = $Player;
        $this->target = $target;
        $this->item = $itemInHand;
    }
    
    /**
     * @return Item
     */
    public function getItem(){
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item){
        $this->item = $item;
    }

    /**
     * @return Block
     */
    public function getBlock(){
        return $this->target;
    }
}