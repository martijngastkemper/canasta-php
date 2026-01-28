<?php

namespAce MartijnGastkemper\Canasta;

final class Deck {

    
    private function __construct(private array $cards) {
        
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
        return new Deck($cards)->shuffle();
    }

    public function shuffle(): Deck {
        shuffle($this->cards);
        return $this;
    }

    public function drawCard(): CardInterface {
        return array_pop($this->cards);
    }

    public function isEmpty(): bool {
        return empty($this->cards);
    }
}