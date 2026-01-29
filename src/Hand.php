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

    public function addCard(CardInterface $card): self {
        $this->cards->add($card);
        $this->cards->sort();
        $this->resetSelectedCards();
        return $this;
    }

    public function getCards(): Cards {
        return $this->cards;
    }

    public function getSelectedCards(): Cards {
        return $this->cards->only($this->selectedCardIndexes);
    }

    public function isSelected(CardInterface $card): bool {
        return $this->getSelectedCards()->contains($card);
    }

    public function playSelectedCards(): Cards {
        $selectedCards = $this->getSelectedCards();
        $this->cards->forget($this->selectedCardIndexes);
        $this->resetSelectedCards();
        return $selectedCards;
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
    
    private function resetSelectedCards(): void {
        $this->selectedCardIndexes = [];
    }
}