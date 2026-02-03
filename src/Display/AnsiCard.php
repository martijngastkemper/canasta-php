<?php

namespace MartijnGastkemper\Canasta\Display;

use MartijnGastkemper\Canasta\CardInterface;
use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\Joker;
use MartijnGastkemper\Canasta\Rank;
use MartijnGastkemper\Canasta\Suite;

final class AnsiCard {

    /** @var array<string> $lines */
    private array $lines;

    public static function backside(): self {
        return new self('-', '-');
    }

    public static function fromCard(CardInterface $card): self {
        if ($card instanceof Joker) {
            return new self('🤡', '');
        }

        if ($card instanceof Card) {
            $suiteChar = match ($card->suite) {
                Suite::Clubs => '♣️',
                Suite::Diamonds => '♦️',
                Suite::Hearts => '♥️',
                Suite::Spades => '♠️',
            };

            return new self($suiteChar, $card->rank->character());
        }

        throw new \InvalidArgumentException("Provided CardInterface class isn't supported.");
    }

    public static function fromRank(Rank $rank): self {
        return new self($rank->character(), '');
    }

    public static function placeholder(): self {
        return new self('', '');
    }

    public function __construct(private string $middleChar, private string $topChar) {
        $this->lines = $this->lines($middleChar, $topChar);
    }

    public function full(): self {
        $this->lines = $this->lines($this->middleChar, $this->topChar);
        return $this;
    }

    public function getHeight(): int {
        return count($this->lines);
    }

    /**
     * @return string[]
     */
    public function getRawLines(): array {
        return $this->lines;
    }

    public function getWidth(): int {
        return mb_strlen($this->lines[0]);
    }

    public function left(): self {
        $this->lines = array_map(fn(string $line) => mb_substr($line, 0, 1), $this->lines($this->middleChar, $this->topChar));
        return $this;
    }

    public function top(): self {
        $this->lines = array_slice($this->lines('', $this->middleChar), 0, 1);
        return $this;
    }

    public function __toString(): string {
        if (!$this->lines) $this->full();
        return join("\n", $this->lines);
    }

    /**
     * @return array<string>
     */
    private function lines(string $middleChar, string $topChar, ?string $bottomChar = null): array {
        return [
            "┌──────┐",
            "| " . mb_str_pad($topChar, 2) . "   |",
            "|      |",
            "|  " . mb_str_pad($middleChar, 2) . "  |",
            "|      |",
            "|    " . mb_str_pad($bottomChar ?? $topChar, 2) . "|",
            "└──────┘"
        ];
    }
}