<?php

require_once __DIR__ . '/vendor/autoload.php';

use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\CardRenderer;
use MartijnGastkemper\Canasta\Deck;
use MartijnGastkemper\Canasta\GameRenderer;
use MartijnGastkemper\Canasta\Hand;
use MartijnGastkemper\Canasta\HandRenderer;
use MartijnGastkemper\Canasta\NonBlockingKeyboardPlayerInput;
use MartijnGastkemper\Canasta\Pool;
use MartijnGastkemper\Canasta\PoolRenderer;
use MartijnGastkemper\Canasta\Rank;
use MartijnGastkemper\Canasta\Slot;
use MartijnGastkemper\Canasta\Suite;
use MartijnGastkemper\Canasta\Table;
use MartijnGastkemper\Canasta\TableRenderer;

// setup game
$cardRenderer = new CardRenderer();
$deck = Deck::create();
$pool = new Pool();
$userInput = new NonBlockingKeyboardPlayerInput();
$table = new Table();

// deal hand
$hand = Hand::createFromDeck($deck, 11);

// add first card to the pool
$pool->addCard($deck->drawCard());

$renderer = new GameRenderer();

$renderer->render($hand, $pool, $table);

while(true) {
    $pressedKey = $userInput->pressedKey();
    
    switch ($pressedKey) {
        case 'q':
            echo "Quitting game.\n";
            exit(0);
        case 'd':
            $newCard = $deck->drawCard();
            $hand->addCard($newCard);
            $renderer->render($hand, $pool, $table);
            break;
        case 'p':
            $cardsInHand = $hand->getCards();
            $card = array_pop($cardsInHand);
            $pool->addCard($hand->playCard($card));
            $renderer->render($hand, $pool, $table);
            break;
        case 'a':
            $cardsInHand = $hand->getCards();
            $card = array_pop($cardsInHand);
            $card = $hand->playCard($card);
            $table->addCard($card, new Slot($card->rank));
            $renderer->render($hand, $pool, $table);
            break;
        case 'h':
            echo "You pressed h for help!\n";
            break;
        case null:
            // No key pressed, continue
            break;
    }
}