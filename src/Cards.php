<?php

namespace MartijnGastkemper\Canasta;

use ArrayIterator;

/**
 * @implements \IteratorAggregate<int, CardInterface>
 */
final class Cards implements \IteratorAggregate {

    /**
     * @param array<int, CardInterface> $cards
     */
    public function __construct(private array $cards = []) {
    }

    /**
     * Remove this function, replace it with ->map() etc.
     *
     * @return array<int, CardInterface>
     */
    public function all(): array {
        return $this->cards;
    }

    public function add(CardInterface $card): self {
        $this->cards[] = $card;
        return $this;
    }

    public function contains(CardInterface $card): bool {
        foreach ($this->cards as $c) {
            if ($c === $card) {
                return true;
            }
        }
        return false;
    }

    public function count(): int {
        return count($this->cards);
    }

    public function countJokers(): int {
        $jokers = 0;
        foreach ($this->cards as $card) {
            if ($card->isJoker()) {
                $jokers++;
            }
        }
        return $jokers;
    }

    public function first(): ?CardInterface {
        return $this->cards[0] ?? null;
    }

    public function getIterator(): \Traversable {
        return new ArrayIterator($this->cards);
    }

    /**
     * Get a rank to
     */
    public function getFirstRank(): ?Rank {
        foreach ($this->cards as $card) {
            if ($card->isJoker() or !$card instanceof Card) {
                continue;
            }
            return $card->rank;
        }
        return null;
    }

    public function hasLessJokersThenCards(): bool {
        return $this->countJokers() < count($this->cards);
    }

    public function hasSingleRank(): bool {
        $firstRank = $this->getFirstRank();
        foreach ($this->cards as $card) {
            if ($card->isJoker()) {
                continue;
            }
            if ($card instanceof Card and $card->rank !== $firstRank) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array<int, int> $indexes
     */
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

    /**
     * @param array<int, int> $indexes
     */
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

    /**
     * @template T
     * @param T $initial
     * @return T
     */
    public function reduce(callable $callable, $initial) {
        $result = $initial;

        foreach ($this->cards as $key => $card) {
            $result = $callable($result, $card, $key);
        }
        return $result;
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