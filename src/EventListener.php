<?php

namespace MartijnGastkemper\Canasta;

interface EventListener {

    public function handle($event): void;

}