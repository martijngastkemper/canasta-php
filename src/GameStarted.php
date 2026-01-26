<?php

namespace MartijnGastkemper\Canasta;

final class GameStarted {

    public function __construct(public readonly Hand $hand, public readonly Pool $pool, public readonly Table $table) {

    }

}