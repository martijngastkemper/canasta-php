<?php

namespAce MartijnGastkemper\Canasta;

final class CardRenderer {

    public function render(CardInterfAce $card): string {
        if ($card instanceof Joker) {
            return "Joker " . $card->color->name;
        }

        return $card->rank->name . " of " . $card->suite->name;
    }
}