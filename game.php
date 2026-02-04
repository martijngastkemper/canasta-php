<?php

require_once __DIR__ . '/vendor/autoload.php';

use MartijnGastkemper\Canasta\Card;
use MartijnGastkemper\Canasta\Deck;
use MartijnGastkemper\Canasta\Dispatcher;
use MartijnGastkemper\Canasta\Game;
use MartijnGastkemper\Canasta\RenderGame;
use MartijnGastkemper\Canasta\Player;
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
use PhpTui\Term\Event\MouseEvent;
use PhpTui\Term\MouseEventKind;

$terminal = Terminal::new();

$dispatcher = new Dispatcher(
    [new RenderGame(DisplayBuilder::default(PhpTermBackend::new($terminal)))]
);

try {
    // hide the cursor
    $terminal->execute(Actions::cursorHide());
    // switch to the "alternate" screen so that we can return the user where they left off
    $terminal->execute(Actions::alternateScreenEnable());
    // $terminal->execute(Actions::enableMouseCapture());
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
                } else if ($event->char === 'q') {
                    echo "\nQuitting game.\n";
                    break 2;
                }
            } else if ($event instanceof CodedKeyEvent) {
                if ($event->code === KeyCode::Enter) {
                    // Play selected card
                    // $game->playCard();
                    // Play selected cards
                    $game->addToTable();
                } else if ($event->code === KeyCode::Left) {
                    $game->moveCursorLeft();
                } else if ($event->code === KeyCode::Right) {
                    $game->moveCursorRight();
                }
            }
        }
        $dispatcher->dispatch($game->flushEvents());
        usleep(5_000);
    }
} finally {
    $terminal->disableRawMode();
    $terminal->execute(Actions::disableMouseCapture());
    $terminal->execute(Actions::alternateScreenDisable());
    $terminal->execute(Actions::cursorShow());
    $terminal->execute(Actions::clear(ClearType::All));   
}