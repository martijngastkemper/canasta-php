<?php

require_once __DIR__ . '/vendor/autoload.php';

use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\Deck;
use MartijnGastkemper\Canasta\Dispatcher;
use MartijnGastkemper\Canasta\Game;
use MartijnGastkemper\Canasta\RenderGame;
use MartijnGastkemper\Canasta\Hand;
use PhpTui\Term\Terminal;  
use PhpTui\Term\KeyCode;
use PhpTui\Term\KeyModifiers;
use MartijnGastkemper\Canasta\NonBlockingKeyboardPlayerInput;
use MartijnGastkemper\Canasta\Pool;
use MartijnGastkemper\Canasta\Rank;
use MartijnGastkemper\Canasta\Suite;
use MartijnGastkemper\Canasta\Table;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Bridge\PhpTerm\PhpTermBackend;
use PhpTui\Term\Actions;
use PhpTui\Term\ClearType;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;

$terminal = Terminal::new();


$dispatcher = new Dispatcher(
    [new RenderGame(DisplayBuilder::default(PhpTermBackend::new($terminal)))]
);

try {
    $terminal->enableRawMode();

    $game = Game::start();

    while(true) {
        while (null !== $event = $terminal->events()->next()) {
            if ($event instanceof CharKeyEvent) {
                if ($event->char === 'a') {
                    $game->moveCursorLeft();
                } else if ($event->char === 'c') {
                    $game->drawCard();
                } else if ($event->char === 'd') {
                    $game->moveCursorRight();
                } else if ($event->char === 'p') {
                    $game->drawPool();
                } else if ($event->char === 's') {
                    $game->toggleSelection();
                } else if ($event->char === "\n") {
                    // Play selected card
                    // $game->playCard();
                    // Play selected cards
                    $game->addToTable();
                } else if ($event->char === 'q') {
                    echo "\nQuitting game.\n";
                    break 2;
                }
            }
        }
        $dispatcher->dispatch($game->flushEvents());
        usleep(5_000);
    }
} finally {
    $terminal->disableRawMode();
    $terminal->execute(Actions::alternateScreenDisable());
    $terminal->execute(Actions::clear(ClearType::All));   
}