<?php

require_once __DIR__ . '/vendor/autoload.php';

use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\Deck;
use MartijnGastkemper\Canasta\Dispatcher;
use MartijnGastkemper\Canasta\Game;
use MartijnGastkemper\Canasta\RenderGame;
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

$userInput = new NonBlockingKeyboardPlayerInput();
$dispatcher = new Dispatcher(
    [new RenderGame()]
);

$game = Game::start();

while(true) {
    $pressedKey = $userInput->pressedKey();
    
    switch ($pressedKey) {
        case 'q':
            echo "Quitting game.\n";
            exit(0);
        case 'd':
            $game->drawCard();
            break;
        case 'p':
            $game->playCard();
            break;
        case 'a':
            $game->addToTable();
            break;
        case 'h':
            echo "You pressed h for help!\n";
            break;
        case null:
            // No key pressed, continue
            break;
    }

    $dispatcher->dispatch($game->flushEvents());
}