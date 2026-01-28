<?php

namespace MartijnGastkemper\Canasta;

final class CursorMoved {

    public function __construct(public readonly int $newPosition) {
        
    }

}