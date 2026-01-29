<?php 

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\set_cursor_position;
use MartijnGastkemper\Canasta\bold;

final class RenderGame implements EventListener {

    private Hand $hand;
    private Pool $pool;
    private Table $table;
    private int $cursorPosition = 0;

    public function handle($event): void {

        if ($event instanceof CursorMoved) {
            $this->cursorPosition = $event->newPosition;
        }

        if ($event instanceof GameStarted) {
            clear_screen();

            $this->hand = $event->hand;
            $this->pool = $event->pool;
            $this->table = $event->table;
        }

        $this->render();
    }

    private function render() {
        // Quick fix to remove previous rendered ANSII
        // But when scrolling up in the terminal, the previous renderings are still visible
        clear_screen();
        set_cursor_position(2, 1);

        $cardRenderer = new CardRenderer();

        echo "Pool Top Card: " . $cardRenderer->render($this->pool->getTopCard()) . "\n\n";

        echo "Table:\n";
        foreach ($this->table->getCanastas() as $canasta) {
            foreach ($canasta->getCards() as $card) {
                echo $cardRenderer->render($card) . " ";
            }
            echo "\n";
        }

        echo "\nHand:\n\n";

        // $this->eraseHand();

        foreach ($this->hand->getCards() as $i => $card) {
            // Limit the number of cards per line
            if ($i % 13 === 0 && $i !== 0) {
                echo "\n\n\n";
            }
            $selected = $this->hand->isSelected($card);
            if ($selected) {
                move_cursor_up(1);
            }

            echo $cardRenderer->render($card);

            if ($selected) {
                move_cursor_down(1);
            }

            if ($i === $this->cursorPosition) {
                $this->renderCursor();
            }

        }
        echo "\n\n";
    }

    private function renderCursor(): void {
        ns_save_cursor_position();
        move_cursor_down(1);
        move_cursor_backward(3);
        echo "👆";
        ns_restore_cursor_position();
    }

    // private function eraseHand(): void {
    //     ns_save_cursor_position();
    //     // erase selected cards line
    //     move_cursor_up(1);
    //     erase_to_end_of_line();
    //     // erase cards line
    //     move_cursor_down(1);
    //     erase_to_end_of_line();
    //     // erase selected cards line
    //     move_cursor_down(1);
    //     erase_to_end_of_line();
    //     ns_restore_cursor_position();
    // }
}