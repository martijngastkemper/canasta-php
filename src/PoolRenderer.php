<?php

namespace MartijnGastkemper\Canasta;

final class PoolRenderer {
    public function render(Pool $pool): void {
        echo "Top card of the pool: " . (new CardRenderer())->render($pool->getTopCard()) . "\n";
    }
}