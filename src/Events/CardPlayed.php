<?php

namespace MartijnGastkemper\Canasta\Events;

use MartijnGastkemper\Canasta\CardInterface;

final class CardPlayed {
    
    public function __construct(public readonly CardInterface $card) {}
}