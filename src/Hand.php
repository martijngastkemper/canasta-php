<?php

namespace MartijnGastkemper\Canasta;

final class Hand {

    private array $selectedCardIndexes = [];

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

    public function selectCard(int $position): void {
        if (in_array($position, $this->selectedCardIndexes, true)) {
            // Deselect
            $this->selectedCardIndexes = array_filter($this->selectedCardIndexes, fn($pos) => $pos !== $position);
        } else {
            // Select
            $this->selectedCardIndexes[] = $position;
        }
    }

    public function playSelectedCards(): array {
        $playedCards = [];
        foreach ($this->selectedCardIndexes as $position) {
            if (!isset($this->cards[$position])) {
                throw new \InvalidArgumentException("Card position $position not found in hand");
            }
            $playedCards[] = $this->cards[$position];
            unset($this->cards[$position]);
        }
        // Reindex the cards array
        $this->cards = array_values($this->cards);
        $this->selectedCardIndexes = [];
        return $playedCards;
    }

    public function getSelectedCardIndexes(): array {
        return $this->selectedCardIndexes;
    }

    public function getSelectedCards(): array {
        $selectedCards = [];
        foreach ($this->selectedCardIndexes as $position) {
            if (isset($this->cards[$position])) {
                $selectedCards[] = $this->cards[$position];
            }
        }
        return $selectedCards;
    }

    public function addCard(CardInterface $card): self {
        $this->cards[] = $card;
        $this->sort();
        $this->selectedCardIndexes = [];
        return $this;
    }

    public function sort(): self {
        usort($this->cards, function (CardInterface $a, CardInterface $b) {
            return $a->getOrderByWeight() <=> $b->getOrderByWeight();
        });
        return $this;
    }
}