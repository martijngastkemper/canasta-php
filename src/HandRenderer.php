<?php

namespace MartijnGastkemper\Canasta;

final class HandRenderer {

    public function render(Hand $hand): void {
        echo "Your hand:\n";
        foreach ($hand->getCards() as $card) {
            echo "- " . (new CardRenderer())->render($card) . "\n";
        }
    }

}