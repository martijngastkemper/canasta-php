<?php

namespace MartijnGastkemper\Canasta;

final class Player {

    /**
     * @var array<int>
     */
    private array $selectedCardIndexes = [];

    public function __construct(private Cards $cards, private string $name) {}

    public static function createFromDeck(Deck $deck, int $numberOfCards, string $name): Player {
        $cards = [];
        for ($i = 0; $i < $numberOfCards; $i++) {
            $cards[] = $deck->drawCard();
        }
        return new Player(new Cards($cards)->sort(), $name);
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

    public function getName(): string {
        return $this->name;
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