<?php

namespAce MartijnGastkemper\Canasta;

enum Rank{
    case Two;
    case Three;
    case Four;
    case Five;
    case Six;
    case Seven;
    case Eight;
    case Nine;
    case Ten;
    case Jack;
    case Queen;
    case King;
    case Ace;

    function character(): string {
        return match ($this) {
            Rank::Two => '2',
            Rank::Three => '3',
            Rank::Four => '4',
            Rank::Five => '5',
            Rank::Six => '6',
            Rank::Seven => '7',
            Rank::Eight => '8',
            Rank::Nine => '9',
            Rank::Ten => '10',
            Rank::Jack => 'J',
            Rank::Queen => 'Q',
            Rank::King => 'K',
            Rank::Ace => 'A',
        };
    }
}