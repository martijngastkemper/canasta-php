<?php

namespace MartijnGastkemper\Canasta;

final class Hand {

    private array $selectedCardIndexes = [];

    public function __construct(private Cards $cards) {}

    public static function createFromDeck(Deck $deck, int $numberOfCards): Hand {
        $cards = [];
        for ($i = 0; $i < $numberOfCards; $i++) {
            $cards[] = $deck->drawCard();
        }
        return new Hand(new Cards($cards)->sort());
    }

    public function getCards(): Cards {
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

    public function playSelectedCards(): Cards {
        $playedCards = $this->cards->only($this->selectedCardIndexes);
        $this->cards->forget($this->selectedCardIndexes);
        $this->selectedCardIndexes = [];
        return $playedCards;
    }

    public function getSelectedCardIndexes(): array {
        return $this->selectedCardIndexes;
    }

    public function countSelectedCards(): int {
        return count($this->selectedCardIndexes);
    }

    public function addCard(CardInterface $card): self {
        $this->cards->add($card);
        $this->cards->sort();
        $this->selectedCardIndexes = [];
        return $this;
    }

}