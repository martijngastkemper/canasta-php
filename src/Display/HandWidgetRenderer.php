<?php

namespace MartijnGastkemper\Canasta\Display;

use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class HandWidgetRenderer implements WidgetRenderer {

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        if (!$widget instanceof HandWidget) {
            return;
        }

        $x = $area->left();
        $y = $area->top();

        foreach ($widget->hand->getCards() as $cardIndex => $card) {
            $ansiCard = AnsiCard::fromCard($card)->full();
            foreach ($ansiCard->getRawLines() as $i => $line) {
                if (!$widget->hand->isSelected($card)) $i += 1;

                $buffer->putString(Position::at($x, $y + $i), $line);
            }

            if ($widget->cursorPosition === $cardIndex) {
                $buffer->putString(Position::at($x + round($ansiCard->getWidth() / 2) - 1, $y + $ansiCard->getHeight() + 2), "👆");
            }

            $x += $ansiCard->getWidth() + 1;

            if ($x > $area->right()) {
                $x = $area->left();
                $y += round($ansiCard->getHeight() / 2, 0, PHP_ROUND_HALF_UP);
            }
        }
    }

}
