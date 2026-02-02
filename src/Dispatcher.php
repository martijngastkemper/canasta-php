<?php

namespace MartijnGastkemper\Canasta;

final class Dispatcher {

    public function __construct(private array $listeners = []) {
    }

    public function dispatch(array $events): void {
        foreach ($this->listeners as $listener) {
            foreach ($events as $event) {
                $listener->handle($event);
            }
        }
    }

}