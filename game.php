<?php

require_once __DIR__ . '/vendor/autoload.php';

use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\CardRenderer;
use MartijnGastkemper\Canasta\Deck;
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

echo "Press 'q' to quit, 's' to show table, 'd' to draw a card, 'p' to play a card, 'h' for help.\n";

while(true) {
    $pressedKey = $userInput->pressedKey();
    
    switch ($pressedKey) {
        case 'q':
            echo "Quitting game.\n";
            exit(0);
        case 'd':
            $newCard = $deck->drawCard();
            echo "Drew card from deck: " . $cardRenderer->render($newCard) . "\n";
            $hand->addCard($newCard);
            break;
        case 'p':
            $cardsInHand = $hand->getCards();
            $card = array_pop($cardsInHand);
            $pool->addCard($hand->playCard($card));
            echo "Played card: " . $cardRenderer->render($card) . "\n";
            break;
        case 'a':
            $cardsInHand = $hand->getCards();
            $card = array_pop($cardsInHand);
            $card = $hand->playCard($card);
            $table->addCard($card, new Slot($card->rank));
            echo "Added card to table: " . $cardRenderer->render($card) . "\n";
            break;
        case 's':
            (new PoolRenderer())->render($pool);
            (new TableRenderer())->render($table);
            (new HandRenderer())->render($hand);
            break;
        case 'h':
            echo "You pressed h for help!\n";
            break;
        case null:
            // No key pressed, continue
            break;
        default:
            echo "You pressed: " . $pressedKey . "\n";
    }
}