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
        $newCard = $this->deck->drawCard();
        $this->hand->addCard($newCard);
        $this->pendingEvents[] = new DrewCard($newCard);
    }

    public function playCard(): void {
        $cardsInHand = $this->hand->getCards();
        $card = array_pop($cardsInHand);
        $this->pool->addCard($this->hand->playCard($card));
        $this->pendingEvents[] = new CardPlayed($card);
    }

    public function drawPool(): void {
        // ...
    }

    public function addToTable(): void {
        $cards = $this->hand->playSelectedCards();
        $this->table->addCards($cards, new Slot($cards[0]->rank));

        $this->pendingEvents[] = new TableUpdated();
        $this->pendingEvents[] = new CardSelected($this->hand->getSelectedCardIndexes());
    }

    public function moveCursorRight(): void {
        $this->cursorPosition = min(count($this->hand->getCards()) - 1, $this->cursorPosition + 1);
        $this->pendingEvents[] = new CursorMoved($this->cursorPosition);
    }

    public function moveCursorLeft(): void {
        $this->cursorPosition = max(0, $this->cursorPosition - 1);
        $this->pendingEvents[] = new CursorMoved($this->cursorPosition);
    }

    public function toggleSelection(): void {
        $this->hand->selectCard($this->cursorPosition);
        $this->pendingEvents[] = new CardSelected($this->hand->getSelectedCardIndexes());
    }

    public function flushEvents(): array {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];
        return $events;
    }

}