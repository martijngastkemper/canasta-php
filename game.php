<?php

require_once __DIR__ . '/vendor/autoload.php';

use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\Deck;
use MartijnGastkemper\Canasta\Dispatcher;
use MartijnGastkemper\Canasta\Game;
use MartijnGastkemper\Canasta\RenderGame;
use MartijnGastkemper\Canasta\Hand;
use MartijnGastkemper\Canasta\NonBlockingKeyboardPlayerInput;
use MartijnGastkemper\Canasta\Pool;
use MartijnGastkemper\Canasta\Rank;
use MartijnGastkemper\Canasta\Suite;
use MartijnGastkemper\Canasta\Table;

$dispatcher = new Dispatcher(
    [new RenderGame()]
);

$userInput = new NonBlockingKeyboardPlayerInput();

$game = Game::start();

while(true) {
    $pressedKey = $userInput->pressedKey();
    
    switch ($pressedKey) {
        case 'a':
            $game->moveCursorLeft();
            break;
        case 'd':
            $game->moveCursorRight();
            break;
        case 'q':
            echo "Quitting game.\n";
            exit(0);
        case 'c':
            $game->drawCard();
            break;
        case 'p':
            $game->drawPool();
            break;
        case 's':
            $game->toggleSelection();
            break;
        case "\n":
            // Play selected card
            // $game->playCard();
            // Play selected cards
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