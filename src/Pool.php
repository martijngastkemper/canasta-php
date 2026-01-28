<?php

namespace MartijnGastkemper\Canasta;

final class Pool {

    private Cards $cards;

    public function __construct() {
        $this->cards = new Cards([]);
    }

    public function addCard(CardInterface $card): void {
        $this->cards->add($card);
    }

    public function draw(): Cards {
        $drawnCards = $this->cards;
        $this->cards = new Cards([]);
        return $drawnCards;
    }

    public function getTopCard(): ?CardInterface {
        return $this->cards->top() ?: null;
    }

}