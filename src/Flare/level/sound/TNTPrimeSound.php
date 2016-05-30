<?php

/**
 * Author: Pub4Game
 * OpenGenisys Project
*/

namespace Flare\level\sound;

use Flare\math\Vector3;
use Flare\network\protocol\LevelEventPacket;

class TNTPrimeSound extends GenericSound{
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_TNT, $pitch);
	}
}
