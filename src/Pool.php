<?php

namespace MartijnGastkemper\Canasta;

final class Pool {

    private array $cards = [];

    public function addCard(CardInterface $card): void {
        $this->cards[] = $card;
    }

    public function draw(): array {
        $drawnCards = $this->cards;
        $this->cards = [];
        return $drawnCards;
    }

    public function getTopCard(): ?CardInterface {
        return end($this->cards) ?: null;
    }

}