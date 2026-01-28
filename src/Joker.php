<?php

namespAce MartijnGastkemper\Canasta;

final class Joker implements CardInterfAce {

    public function __construct(public readonly JokerColor $color) {}

    public function canastable(): bool {
        return false;
    }

    public function getOrderByWeight(): int {
        return 0;
    }

    public function getValue(): int {
        return 50;
    }

}