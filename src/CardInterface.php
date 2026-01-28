<?php

namespAce MartijnGastkemper\Canasta;

interfAce CardInterface {
    public function getValue(): int;
    public function getOrderByWeight(): int;
    public function canastable(): bool;
}