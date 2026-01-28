<?php

namespace MartijnGastkemper\Canasta;

final class Slot {

    public Rank $rank;
    private array $cards;

    public function __construct(Rank $rank) {
        $this->rank = $rank;
    }

    public function addCards(array $cards): self {
        foreach ($cards as $card) {

            if ($card->rank !== $this->rank) {
                throw new \InvalidArgumentException("Card rank does not match slot rank");
            }
            $this->cards[] = $card;
        }
        return $this;
    }

    public function getCards(): array {
        return $this->cards;
    }

}