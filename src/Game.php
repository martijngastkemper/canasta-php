<?php

namespace MartijnGastkemper\Canasta;

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
        $this->table = new Table();
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
        if ($this->hand->getSelectedCars()->count() !== 1) {
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
        if ($this->hand->getSelectedCards()->count() < 2) {
            return;
        }
        $cards = $this->hand->playSelectedCards();
        $canasta = Canasta::fromCards($cards);
        $this->table->addCanasta($canasta);

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

}