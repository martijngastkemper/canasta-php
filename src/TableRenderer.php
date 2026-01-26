<?php

namespace MartijnGastkemper\Canasta;

final class TableRenderer {

    public function render(Table $table) {
        echo "Your table:\n";
        foreach ($table->getSlots() as $slot) {
            echo "- " . $slot->rank->name . " (" . count($slot->getCards()) . ")\n";
        }
    }

}