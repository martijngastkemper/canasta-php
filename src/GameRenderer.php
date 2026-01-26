<?php 

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\set_cursor_position;
use MartijnGastkemper\Canasta\bold;

final class GameRenderer {

    public function render(Hand $hand, Pool $pool, Table $table): void {
        clear_screen();
        set_cursor_position(2, 1);

        $cardRenderer = new CardRenderer();

        echo "Pool Top Card: " . $cardRenderer->render($pool->getTopCard()) . "\n\n";

        echo "Table:\n";
        foreach ($table->getSlots() as $slot) {
            foreach ($slot->getCards() as $card) {
                echo $cardRenderer->render($card) . " ";
            }
            echo "\n";
        }

        echo "\nHand:\n";

        foreach ($hand->getCards() as $card) {
            echo $cardRenderer->render($card);
        }
    }

}