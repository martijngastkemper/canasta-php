<?php

namespace MartijnGastkemper\Canasta;

final class Cards {

    public function __construct(private array $cards = []) {
    }

    public function all(): array {
        return $this->cards;
    }

    public function add(CardInterface $card): self {
        $this->cards[] = $card;
        return $this;
    }

    public function count(): int {
        return count($this->cards);
    }

    public function first(): ?CardInterface {
        return $this->cards[0] ?? null;
    }

    public function hasSingleRank(): bool {
        if (empty($this->cards)) {
            return true;
        }
        $firstRank = $this->cards[0]->rank;
        foreach ($this->cards as $card) {
            if ($card instanceof Joker || $card->rank === Rank::Two) {
                continue;
            }
            if ($card->rank !== $firstRank) {
                return false;
            }
        }
        return true;
    }

    public function only(array $indexes): self {
        $pickedCards = [];
        foreach ($indexes as $index) {
            if (isset($this->cards[$index])) {
                $pickedCards[] = $this->cards[$index];
            }
        }
        return new self($pickedCards);
    }

    public function pop(): ?CardInterface {
        return array_pop($this->cards) ?: null;
    }

    public function forget(array $indexes): self {
        foreach ($indexes as $position) {
            if (isset($this->cards[$position])) {
                unset($this->cards[$position]);   
            }
        }
        // Reindex the cards array
        $this->cards = array_values($this->cards);
        return $this;
    }

    public function shuffle(): self {
        shuffle($this->cards);
        return $this;
    }

    public function sort(): self {
        usort($this->cards, function (CardInterface $a, CardInterface $b) {
            return $a->getOrderByWeight() <=> $b->getOrderByWeight();
        });
        return $this;
    }

    public function top(): ?CardInterface {
        return end($this->cards) ?: null;
    }
}