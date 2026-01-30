<?php

namespace MartijnGastkemper\Canasta;

final class CardRenderer {

    public function renderFull(CardInterface $card): array {
        if ($card instanceof Joker) {
            return $this->template('🤡', '');
        }

        return $this->template($this->getSuiteChar($card), $card->rank->character());
    }

    public function renderTop(CardInterface $card): array {
        return array_slice($this->renderFull($card), 0, 1);
    }

    public function renderLeft(CardInterface $card): array {
        return array_map(fn (string $line) => mb_substr($line, 0, 1), $this->renderFull($card));
    }

    public function renderPlaceHolder(Rank $rank): array {
        return $this->template($rank->character(), '');
    }

    public function renderBackside(): array {
        return $this->renderFull('-', '-');
    }

    public function getHeight(): int {
        return count($this->template('', ''));
    }

    public function getWidth(): int {
        return mb_strlen($this->template('', '')[0]);
    }

    private function renderCard(CardInterface $card): array {
        
    }

    private function getSuiteChar(CardInterface $card): string {
        return match($card->suite) {
            Suite::Clubs => '♣️',
            Suite::Diamonds => '♦️',
            Suite::Hearts => '♥️',
            Suite::Spades => '♠️',
        };
    }

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