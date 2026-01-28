<?php

namespace MartijnGastkemper\Canasta;

final class Canasta {

    public static function fromCards(Cards $cards): self {
        if (!$cards->hasSingleRank()) {
            throw new \InvalidArgumentException("All cards in a Canasta must have the same rank");
        }
        
        $canasta = new self($cards, $cards->first()->rank);

        if (!$canasta->isValid()) {
            throw new \InvalidArgumentException("The provided cards do not form a valid Canasta");
        }

        return $canasta;
    }

    public function __construct(private Cards $cards, private Rank $rank) {
    }

    public function add(CardInterface $card): void {
        if ($card->rank !== $this->rank) {
            throw new \InvalidArgumentException("Card rank does not match Canasta rank");
        }
        $this->cards->add($card);
    }

    public function isFinished(): bool {
        return $this->cards->count() >= 7;
    }

    public function getCards(): Cards {
        return $this->cards;
    }

    public function getRank(): Rank {
        return $this->rank;
    }

    public function isValid(): bool {
        // Add joker validation later
        // - minimum of 2 non joker cards
        // - always less jokers then non joker cards
        return $this->cards->count() >= 3;
    }

    public function isPure(): bool {
        // Add joker validation later
        return true;
    }

    public function merge(Canasta $other): void {
        if ($this->rank !== $other->rank) {
            throw new \InvalidArgumentException("Cannot merge Canastas of different ranks");
        }
        foreach ($other->getCards()->all() as $card) {
            $this->add($card);
        }
    }
}