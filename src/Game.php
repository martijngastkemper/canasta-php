<?php

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\Events\CardPlayed;
use MartijnGastkemper\Canasta\Events\CardSelected;
use MartijnGastkemper\Canasta\Events\CursorMoved;
use MartijnGastkemper\Canasta\Events\DrewCard;
use MartijnGastkemper\Canasta\Events\GameStarted;
use MartijnGastkemper\Canasta\Events\TableUpdated;

final class Game {

    public Deck $deck;
    public Pool $pool;
    public Hand $hand;
    public Table $table;
    private array $pendingEvents = [];
    public int $cursorPosition = 0;

    public static function start(): Game {
        $game = new Game();
        $game->pendingEvents[] = new GameStarted($game->hand, $game->pool, $game->table);
        return $game;
    }

    public function __construct() {
        $this->deck = Deck::create();
        $this->pool = new Pool();
        $this->hand = Hand::createFromDeck($this->deck, 11);
        $this->pool->addCard($this->deck->drawCard());
        $this->table = new Table("Team top / bottom");
    }

    public function nextPlayer(): void {

    }

    public function drawCard(): void {
        if ($this->deck->isEmpty()) {
            // game should be over
            return;
        }
        $newCard = $this->deck->drawCard();
        $this->hand->addCard($newCard);
        $this->pendingEvents[] = new DrewCard($newCard);
    }

    public function playCard(): void {
        if ($this->hand->getSelectedCards()->count() !== 1) {
            return;
        }
        $cards = $this->hand->playSelectedCards();
        $card = $cards->first();
        $this->pool->addCard($card);
        $this->pendingEvents[] = new CardPlayed($card);
    }

    public function drawPool(): void {
        // ...
    }

    public function addToTable(): void {
        $selectedCards = $this->hand->getSelectedCards();

        // No cards selected
        if ($selectedCards->count() === 0) {
            return;
        }

        // Multiple ranks selected
        if (!$selectedCards->hasSingleRank()) {
            return;
        }

        $rank = $selectedCards->getFirstRank();

        $canasta = $this->table->getCanasta($rank);

        if ($canasta) {
            $canasta->sort();
            foreach ($selectedCards as $card) {
                $canasta->add($card);
            }
        } else {
            // Cards selected to create a new canasta
            $canasta = $this->createCanasta($selectedCards);
            if (!$canasta) {
                return;
            }
            $canasta->sort();
            $this->table->addCanasta($canasta);
        }

        $cards = $this->hand->playSelectedCards();

        $this->pendingEvents[] = new TableUpdated();
    }

    public function moveCursorRight(): void {
        $newPosition = $this->cursorPosition + 1;
        if ($newPosition >= $this->hand->getCards()->count()) {
            $newPosition = 0;
        }
        $this->cursorPosition = $newPosition;
        $this->pendingEvents[] = new CursorMoved($this->cursorPosition);
    }

    public function moveCursorLeft(): void {
        $newPosition = $this->cursorPosition - 1;
        if ($newPosition < 0) {
            $newPosition = $this->hand->getCards()->count() - 1;
        }
        $this->cursorPosition = $newPosition;
        $this->pendingEvents[] = new CursorMoved($this->cursorPosition);
    }

    public function toggleSelection(): void {
        $this->hand->selectCard($this->cursorPosition);
        $this->pendingEvents[] = new CardSelected();
    }

    public function flushEvents(): array {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];
        return $events;
    }

    private function createCanasta(Cards $cards): ?Canasta {
        if ($cards->count() < 3) {
            return null;
            // throw new \InvalidArgumentException("Provide at least three cards to create a Canasta object.");
        }

        if (!$cards->hasSingleRank()) {
            return null;
            // throw new \InvalidArgumentException("Provided cards with one rank to create a Canasta object.");
        }

        if (!$cards->hasLessJokersThenCards()) {
            return null;
            // throw new \InvalidArgumentException("Canasta object must contain more cards then jokers.");
        }

        // Handle three of hearts and diamonds => not allowed to, shouldn't be available here.
        // Handle threes of club and spades => only allowed when finishing the round.

        return new Canasta($cards, $cards->getFirstRank());
    }
}