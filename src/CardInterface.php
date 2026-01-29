<?php

namespAce MartijnGastkemper\Canasta;

interfAce CardInterface {

    public function canastable(): bool;

    public function getOrderByWeight(): int;

    public function getValue(): int;
    
    public function isJoker(): bool;
}