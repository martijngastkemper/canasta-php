<?php

namespAce MartijnGastkemper\Canasta;

final class Card implements CardInterface {
    public function __construct(public readonly Suite $suite, public readonly Rank $rank) {}

    public function getOrderByWeight(): int {
        return match ($this->rank) {
            Rank::Ace => 14,
            Rank::King => 13,
            Rank::Queen => 12,
            Rank::Jack => 11,
            Rank::Ten => 10,
            Rank::Nine => 9,
            Rank::Eight => 8,
            Rank::Seven => 7,
            Rank::Six => 6,
            Rank::Five => 5,
            Rank::Four => 4,
            Rank::Three => 3,
            Rank::Two => 2,
        };
    }

    public function getValue(): int {
        return match ($this->rank) {
            Rank::Ace || Rank::Two => 20,
            Rank::Three && (Suite::HEARTS || Suite::DIAMONDS) => 100,
            Rank::Three => 5,
            Rank::Four || Rank::Five || Rank::Six || Rank::Seven => 5,
            Rank::Eight || Rank::Nine || Rank::Ten || Rank::Jack || Rank::Queen || Rank::King => 10,
        };
    }

    public function isJoker(): bool {
        return $this->rank === Rank::Two;
    }

}