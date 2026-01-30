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

        $x = $area->left();
        $y = $area->top();
        $cardWidth = mb_strlen($this->template('', '')[0]);
        $cardHeight = count($this->template('', ''));

        foreach ($widget->hand->getCards() as $cardIndex => $card) {
            $cardLines = $this->renderCard($card);
            foreach ($cardLines as $i => $line) {
                if (!$widget->hand->isSelected($card)) $i += 1;

                $buffer->putString(Position::at($x, $y + $i), $line);
            }

            if ($widget->cursorPosition === $cardIndex) {
                $buffer->putString(Position::at($x + round($cardWidth / 2) - 1, $y + $cardHeight + 2), "👆");
            }

            $x += $cardWidth + 1;

            if ($x > $area->right()) {
                $x = $area->left();
                $y += $cardHeight + 2;
            }
        }
    }

    private function renderCard(CardInterface $card): array {
        if ($card instanceof Joker) {
            return $this->template('🤡', '');
        }

        $suiteChar = match($card->suite) {
            Suite::Clubs => '♣️',
            Suite::Diamonds => '♦️',
            Suite::Hearts => '♥️',
            Suite::Spades => '♠️',
        };

        return $this->template($suiteChar, $card->rank->character());
    }

    private function template(string $suite, string $rank): array {
        return [
            "┌──────┐",
            "| " . mb_str_pad($rank, 2) . "   |",
            "|      |",
            "|  " . mb_str_pad($suite, 2) . "  |",
            "|      |",
            "|    " . mb_str_pad($rank, 2) . "|",
            "└──────┘"
        ];
    }

}
