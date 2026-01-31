<?php

namespace MartijnGastkemper\Canasta\Events;

use MartijnGastkemper\Canasta\CardInterface;

final class DrewCard {

    public function __construct(public readonly CardInterface $card) {

    }

}