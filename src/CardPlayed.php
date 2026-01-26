<?php

namespace MartijnGastkemper\Canasta;

final class CardPlayed {
    
    public function __construct(public readonly Card $card) {}
}