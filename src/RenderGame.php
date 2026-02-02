<?php 

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\Display\AnsiCard;
use MartijnGastkemper\Canasta\Display\HandWidgetRenderer;
use MartijnGastkemper\Canasta\Display\HandWidget;
use MartijnGastkemper\Canasta\Events\CursorMoved;
use MartijnGastkemper\Canasta\Events\GameStarted;
use MartijnGastkemper\Canasta\set_cursor_position;
use MartijnGastkemper\Canasta\bold;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Display\Display;
use PhpTui\Tui\Extension\Core\Shape\MapResolution;
use PhpTui\Tui\Extension\Core\Widget\CanvasWidget;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Borders;
use PhpTui\Tui\Widget\Direction;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Extension\Core\Widget\ListWidget;
use PhpTui\Tui\Extension\Core\Widget\TableWidget;
use PhpTui\Tui\Text\Text;
use PhpTui\Tui\Text\Span;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Extension\Core\Widget\List\ListItem;
use PhpTui\Tui\Extension\Core\Widget\List\ListState;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Style\Style;
use PhpTui\Term\Terminal;
use PhpTui\Tui\Widget\Widget;

final class RenderGame implements EventListener {

    private Hand $hand;
    private Pool $pool;
    private Table $table;
    private int $cursorPosition = 0;
    private Display $display;

    public function __construct(DisplayBuilder $displayBuilder) {
        $this->display = $displayBuilder
            ->fullscreen()
            ->addWidgetRenderer(new HandWidgetRenderer())
            ->build();
    }

    public function handle($event): void {

        if ($event instanceof CursorMoved) {
            $this->cursorPosition = $event->newPosition;
        }

        if ($event instanceof GameStarted) {
            $this->hand = $event->hand;
            $this->pool = $event->pool;
            $this->table = $event->table;
        }

        $this->render();
    }

    private function render(): void {
        $display = $this->display;
        $display->clear();
        $display->draw(
            GridWidget::default()
                ->direction(Direction::Vertical)
                ->constraints(
                    Constraint::percentage(20),
                    Constraint::percentage(40),
                    Constraint::percentage(40),
                )
                ->widgets(
                    $this->getPoolWidget(),
                    $this->getTableWidget(),
                    $this->getHandWidget(),
                )
        );
    }

    private function getHandWidget(): Widget {
        return new HandWidget($this->cursorPosition, $this->hand);
    }

    private function getPoolWidget(): Widget {
        $topCard = $this->pool->getTopCard();

        $deckCard = AnsiCard::backside();

        $poolCard = null;

        if (!$topCard) {
            $poolCard = AnsiCard::placeholder();
        } else {
            // Show extra lines when the pool has 2 or more cards
            $poolCard = AnsiCard::fromCard($topCard);
        }

        return GridWidget::default()
            ->direction(Direction::Horizontal)
            ->constraints(
                Constraint::min($deckCard->getWidth()),
                Constraint::min(2),
            )
            ->widgets(
                ParagraphWidget::fromString(
                    $deckCard
                ),
                ParagraphWidget::fromString(
                    $poolCard
                )
            );
    }

    private function getTableWidget(): Widget {
        $slots = [];

        foreach(Rank::cases() as $rank) {
            if ($rank === Rank::Two) continue;

            $canasta = $this->table->getCanasta($rank);

            if ($canasta) {
                $lines = [];
                foreach($canasta->getCards() as $i => $card) {
                    if ($i === $canasta->getCards()->count() - 1) {
                        $lines = array_merge($lines, AnsiCard::fromCard($card)->full()->getRawLines());
                    } else {
                        $lines = array_merge($lines, AnsiCard::fromCard($card)->top()->getRawLines());
                    }
                }
                $slots[] = $lines;
            } else {
                $slots[] =  AnsiCard::fromRank($rank)->getRawLines();
            }
        }

        $slotWidgets = array_map(
            fn ($lines) => ParagraphWidget::fromText(
                new Text(
                    array_map(
                        fn (string $line) => Line::fromString($line), 
                        $lines
                    )
                ),
            ), 
            $slots
        );

        return GridWidget::default()
            ->direction(Direction::Horizontal)
            ->constraints(...array_fill(0, count($slotWidgets), Constraint::percentage(round(100 / count($slotWidgets)))))
            ->widgets(...$slotWidgets);
    }
}