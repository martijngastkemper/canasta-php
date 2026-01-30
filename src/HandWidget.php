<?php

namespace MartijnGastkemper\Canasta;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class HandWidget implements Widget {

    public function __construct(public readonly int $cursorPosition, public readonly Hand $hand) {

    }


}