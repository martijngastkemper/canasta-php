<?php

namespace MartijnGastkemper\Canasta;

final class Deck {


    private function __construct(private Cards $cards) {

    }

    public static function create(): Deck {
        $cards = [];
        foreach (Suite::cases() as $suite) {
            foreach (Rank::cases() as $rank) {
                $cards[] = new Card($suite, $rank);
            }
        }
        $cards[] = new Joker(JokerColor::Red);
        $cards[] = new Joker(JokerColor::Black);
        return new Deck(new Cards($cards)->shuffle());
    }

    public function drawCard(): CardInterface {
        $card = $this->cards->pop();
        if ($card === null) {
            throw new \RuntimeException("No more cards in the deck");
        }
        return $card;
    }

    public function isEmpty(): bool {
        return $this->cards->count() === 0;
    }
}