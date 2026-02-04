<?php

namespace MartijnGastkemper\Canasta\Events;

use MartijnGastkemper\Canasta\Player;
use MartijnGastkemper\Canasta\Pool;
use MartijnGastkemper\Canasta\Table;

final class GameStarted {

    public function __construct(public readonly Player $hand, public readonly Pool $pool, public readonly Table $table) {

    }

}