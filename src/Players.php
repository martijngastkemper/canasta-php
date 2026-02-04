<?php

namespace MartijnGastkemper\Canasta;

use Traversable;

final class Players implements \IteratorAggregate {

    public function __construct(private array $players) {}

    public function getIterator(): Traversable {
        return new \ArrayIterator($this->players);
    }
}
