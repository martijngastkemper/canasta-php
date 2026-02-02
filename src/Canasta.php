<?php

namespace MartijnGastkemper\Canasta;

final class Canasta {

    public function __construct(private Cards $cards, private Rank $rank) {
    }

    public function add(CardInterface $card): void {
        if ($card->isJoker()) {
            if ($this->getCards()->count() > $this->getCards()->countJokers() + 1) {
                throw new \InvalidArgumentException("Canasta must contain more cards then jokers.");
            }
        }
        if ($card instanceof Card && $card->rank !== $this->rank) {
            throw new \InvalidArgumentException("Card rank does not match Canasta rank");
        }
        $this->cards->add($card);
    }

    public function getCards(): Cards {
        return $this->cards;
    }

    public function getRank(): Rank {
        return $this->rank;
    }

    public function isFinished(): bool {
        return $this->cards->count() >= 7;
    }

    public function isPure(): bool {
        return $this->cards->countJokers() === 0;
    }

    public function merge(Canasta $other): void {
        if ($this->rank !== $other->rank) {
            throw new \InvalidArgumentException("Cannot merge Canastas of different ranks");
        }
        foreach ($other->getCards() as $card) {
            $this->add($card);
        }
    }

    public function sort(): void {
        $this->cards->sort();
    }
}