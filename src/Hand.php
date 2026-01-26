<?php

namespace MartijnGastkemper\Canasta;

final class Hand {

    public function __construct(private array $cards) {}

    public static function createFromDeck(Deck $deck, int $numberOfCards): Hand {
        $cards = [];
        for ($i = 0; $i < $numberOfCards; $i++) {
            $cards[] = $deck->drawCard();
        }
        return new Hand($cards)->sort();
    }

    public function getCards(): array {
        return $this->cards;
    }

    public function playCard(CardInterface $card): CardInterface {
        foreach ($this->cards as $key => $handCard) {
            if ($handCard === $card) {
                unset($this->cards[$key]);
                return $card;
            }
        }
        throw new \InvalidArgumsentException("Card not found in hand");
    }

    public function addCard(CardInterface $card): self {
        $this->cards[] = $card;
        $this->sort();
        return $this;
    }

    public function sort(): self {
        usort($this->cards, function (CardInterface $a, CardInterface $b) {
            return $a->getOrderByWeight() <=> $b->getOrderByWeight();
        });
        return $this;
    }
}