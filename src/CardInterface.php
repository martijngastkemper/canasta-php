<?php

namespace MartijnGastkemper\Canasta;

interface CardInterface {

    public function getOrderByWeight(): int;

    public function getValue(): int;

    public function isJoker(): bool;
}