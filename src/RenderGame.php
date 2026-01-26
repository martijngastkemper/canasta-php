<?php 

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\set_cursor_position;
use MartijnGastkemper\Canasta\bold;

final class RenderGame implements EventListener {

    private Hand $hand;
    private Pool $pool;
    private Table $table;

    public function handle($event): void {

        if ($event instanceof GameStarted) {
            $this->hand = $event->hand;
            $this->pool = $event->pool;
            $this->table = $event->table;
            $this->render();
            return;
        }

        // if ($event instanceof DrewCard) {
            $this->render();
            // return;
        // }
    }

    private function render() {
        clear_screen();
        set_cursor_position(2, 1);

        $cardRenderer = new CardRenderer();

        echo "Pool Top Card: " . $cardRenderer->render($this->pool->getTopCard()) . "\n\n";

        echo "Table:\n";
        foreach ($this->table->getSlots() as $slot) {
            foreach ($slot->getCards() as $card) {
                echo $cardRenderer->render($card) . " ";
            }
            echo "\n";
        }

        echo "\nHand:\n";

        foreach ($this->hand->getCards() as $card) {
            echo $cardRenderer->render($card);
        }
    }
}