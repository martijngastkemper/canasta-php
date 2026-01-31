<?php

namespace MartijnGastkemper\Canasta\Events;

final class CursorMoved {

    public function __construct(public readonly int $newPosition) {
        
    }

}