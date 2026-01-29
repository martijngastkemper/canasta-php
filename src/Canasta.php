<?php

namespace MartijnGastkemper\Canasta;

final class Canasta {

    public static function fromCards(Cards $cards): self {
        if ($cards->count() < 1) {
            throw new \InvalidArgumentException("Provide at least one card to create a Canasta object.");
        }

        if (!$cards->hasSingleRank()) {
            throw new \InvalidArgumentException("Provided cards with one rank to create a Canasta object.");
        }
        
        return new self($cards, $cards->getFirstRank());
    }

    public static function tryFromHand(Hand $hand): ?self {
        try {
            return self::fromCards($hand->getCards());
        } catch (Exception $e) {
            return null;
        }
    }

    public function __construct(private Cards $cards, private Rank $rank) {
    }

    public function add(CardInterface $card): void {
        if ($card->rank !== $this->rank) {
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
        // Add joker validation later
        return true;
    }

    public function isValid(): bool {
        // Add joker validation later
        // - minimum of 2 non joker cards
        // - always less jokers then non joker cards
        return $this->cards->count() >= 3;
    }

    public function merge(Canasta $other): void {
        if ($this->rank !== $other->rank) {
            throw new \InvalidArgumentException("Cannot merge Canastas of different ranks");
        }
        foreach ($other->getCards() as $card) {
            $this->add($card);
        }
    }
}