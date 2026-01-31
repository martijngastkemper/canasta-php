<?php

namespace MartijnGastkemper\Canasta\Display;

use MartijnGastkemper\Canasta\CardInterface;
use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\Joker;
use MartijnGastkemper\Canasta\Rank;
use MartijnGastkemper\Canasta\Suite;

final class CardRenderer {

    /**
     * @return array<string>
     */
    public function renderFull(CardInterface $card): array {
        if ($card instanceof Joker) {
            return $this->template('🤡', '');
        }

        if ($card instanceof Card) {
            $suiteChar = match($card->suite) {
                Suite::Clubs => '♣️',
                Suite::Diamonds => '♦️',
                Suite::Hearts => '♥️',
                Suite::Spades => '♠️',
            };

            return $this->template($suiteChar, $card->rank->character());
        }

        throw new \InvalidArgumentException("Unsupported card class given.");
    }

    /**
     * @return array<string>
     */
    public function renderTop(CardInterface $card): array {
        return array_slice($this->renderFull($card), 0, 2);
    }

    /**
     * @return array<string>
     */
    public function renderLeft(CardInterface $card): array {
        return array_map(fn (string $line) => mb_substr($line, 0, 1), $this->renderFull($card));
    }

    /**
     * @return array<string>
     */
    public function renderPlaceHolder(?Rank $rank = null): array {
        return $this->template($rank ? $rank->character() : '', '');
    }

    /**
     * @return array<string>
     */
    public function renderBackside(): array {
        return $this->template('-', '-');
    }

    public function getHeight(): int {
        return count($this->template('', ''));
    }

    public function getWidth(): int {
        return mb_strlen($this->template('', '')[0]);
    }

    /**
     * @return array<string>
     */
    private function template(string $suite, string $rank): array {
        return [
            "┌──────┐",
            "| " . mb_str_pad($rank, 2) . "   |",
            "|      |",
            "|  " . mb_str_pad($suite, 2) . "  |",
            "|      |",
            "|    " . mb_str_pad($rank, 2) . "|",
            "└──────┘"
        ];
    }

}