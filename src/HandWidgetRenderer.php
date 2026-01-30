<?php

namespace MartijnGastkemper\Canasta;


use PhpTui\Tui\Display\Area;
use PhpTui\Tui\Display\Buffer;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Position\Position;
use PhpTui\Tui\Widget\Widget;
use PhpTui\Tui\Widget\WidgetRenderer;

final class HandWidgetRenderer implements WidgetRenderer {

    public function render(WidgetRenderer $renderer, Widget $widget, Buffer $buffer, Area $area): void
    {
        if (!$widget instanceof HandWidget) {
            return;
        }

        $cardRenderer = new CardRenderer();

        $x = $area->left();
        $y = $area->top();

        foreach ($widget->hand->getCards() as $cardIndex => $card) {
            $cardLines = $cardRenderer->renderFull($card);
            foreach ($cardLines as $i => $line) {
                if (!$widget->hand->isSelected($card)) $i += 1;

                $buffer->putString(Position::at($x, $y + $i), $line);
            }

            if ($widget->cursorPosition === $cardIndex) {
                $buffer->putString(Position::at($x + round($cardRenderer->getWidth() / 2) - 1, $y + $cardRenderer->getHeight() + 2), "👆");
            }

            $x += $cardRenderer->getWidth() + 1;

            if ($x > $area->right()) {
                $x = $area->left();
                $y += $cardRenderer->getHeight() + 2;
            }
        }
    }

}
