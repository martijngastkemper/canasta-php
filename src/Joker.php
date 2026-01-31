<?php

namespAce MartijnGastkemper\Canasta;

final class Joker implements CardInterface {

    public function __construct(public readonly JokerColor $color) {}

    public function getOrderByWeight(): int {
        return 0;
    }

    public function getValue(): int {
        return 50;
    }

    public function isJoker(): bool {
        return true;
    }

}