<?php

namespace MartijnGastkemper\Canasta;

final class Table {

    private array $canastas = [];

    public function addCanasta(Canasta $canasta): self {
        $existingCanasta = null;

        foreach ($this->canastas as $tableCanasta) {
            if ($tableCanasta->rank === $canasta->rank) {
                $existingCanasta = $tableCanasta;
                break;
            }
        }

        if ($existingCanasta) {
            $existingCanasta->merge($canasta);
        } else {
            $this->canastas[] = $canasta;
        }
        return $this;
    }

    public function getCanastas(): array {
        return $this->canastas;
    }
}