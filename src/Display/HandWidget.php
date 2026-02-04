<?php

namespace MartijnGastkemper\Canasta\Display;

use MartijnGastkemper\Canasta\Player;
use PhpTui\Tui\Widget\Widget;

final class HandWidget implements Widget {

    public function __construct(public readonly int $cursorPosition, public readonly Player $hand) {
    }

}