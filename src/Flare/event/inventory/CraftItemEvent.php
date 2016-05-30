<?php
/**
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
 * @link   http://www.Flare.net/
 *
 *
 */
namespace Flare\event\inventory;

use Flare\event\Cancellable;
use Flare\event\Event;
use Flare\inventory\Recipe;
use Flare\item\Item;
use Flare\Player;

class CraftItemEvent extends Event implements Cancellable{
	public static $handlerList = null;
	/** @var Item[] */
	private $input = [];
	/** @var Recipe */
	private $recipe;
	/** @var \Flare\Player */
	private $player;

	/**
	 * @param \Flare\Player $player
	 * @param Item[]             $input
	 * @param Recipe             $recipe
	 */
	public function __construct(Player $player, array $input, Recipe $recipe){
		$this->player = $player;
		$this->input = $input;
		$this->recipe = $recipe;
	}

	/**
	 * @return Item[]
	 */
	public function getInput(){
		$items = [];
		foreach($items as $i => $item){
			$items[$i] = clone $item;
		}
		return $items;
	}

	/**
	 * @return Recipe
	 */
	public function getRecipe(){
		return $this->recipe;
	}

	/**
	 * @return \Flare\Player
	 */
	public function getPlayer(){
		return $this->player;
	}
}