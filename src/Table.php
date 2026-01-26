<?php

namespace MartijnGastkemper\Canasta;

final class Table {

    private array $canastas = [];
    private array $slots = [];

    public function addCard(CardInterface $card, Slot $slot): self {
        $existingSlot = null;
        foreach ($this->slots as $tableSlot) {
            if ($tableSlot->rank === $slot->rank) {
                $existingSlot = $tableSlot;
                break;
            }
        }
        if ($existingSlot === null) {
            $this->slots[] = $existingSlot = new Slot($card->rank);
        }
        $existingSlot->addCard($card);
        return $this;
    }

    public function getSlots(): array {
        return $this->slots;
    }

    public function createCanasta(Slot $slot): self {
        $this->canastas[] = $slot;
        return $this;
    }
}