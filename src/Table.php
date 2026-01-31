<?php

namespace MartijnGastkemper\Canasta;

final class Table {

    /** @var array<Canasta> */
    private array $canastas = [];

    public function addCanasta(Canasta $canasta): self {
        $existingCanasta = null;

        foreach ($this->canastas as $tableCanasta) {
            if ($tableCanasta->getRank() === $canasta->getRank()) {
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

    public function getCanasta(Rank $rank): ?Canasta {
        foreach ($this->canastas as $canasta) {
            if ($canasta->getRank() === $rank) {
                return $canasta;
            }
        }
        return null;
    }

    /**
     * @return array<Canasta>
     */
    public function getCanastas(): array {
        return $this->canastas;
    }
}