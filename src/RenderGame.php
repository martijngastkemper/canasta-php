<?php 

namespace MartijnGastkemper\Canasta;

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

    private function render() {
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

    private function getHandWidget(): HandWidget {
        return new HandWidget($this->cursorPosition, $this->hand);
    }

    private function getPoolWidget(): BlockWidget {
        return BlockWidget::default()->borders(Borders::ALL)->titles(Title::fromString('Pool'))
            ->widget(ParagraphWidget::fromString(
                join("\n", $this->renderCard($this->pool->getTopCard()))
            ));
    }

    private function getTableWidget(): GridWidget {
        $slotWidgets = [];

        foreach(Rank::cases() as $rank) {
            if ($rank === Rank::Two) continue;

            $canasta = $this->table->getCanasta($rank);

            if (!$canasta) {
                $slotWidgets[] = ParagraphWidget::fromText(new Text(
                    array_map(fn (string $line) => Line::fromString($line), $this->template($rank->character(), ''))
                ));
                continue;
            }

            $slotWidgets[] = ParagraphWidget::fromString("A canasta of rank {$rank->character()}");
        }

        return GridWidget::default()
            ->direction(Direction::Horizontal)
            ->constraints(...array_fill(0, count($slotWidgets), Constraint::percentage(round(100 / count($slotWidgets)))))
            ->widgets(...$slotWidgets);
    }

    private function renderCard(CardInterface $card): array {
        if ($card instanceof Joker) {
            return $this->template('🤡', '');
        }

        $suiteChar = match($card->suite) {
            Suite::Clubs => '♣️',
            Suite::Diamonds => '♦️',
            Suite::Hearts => '♥️',
            Suite::Spades => '♠️',
        };

        return $this->template($suiteChar, $card->rank->character());
    }
    
    private function template(string $suite, string $rank): array {
        return [
            "┌──────┐",
            "| " . mb_str_pad($rank, 2) . "   |",
            "|      |",
            "|  " . mb_str_pad($suite, 2) . "  |",
            "|      |",
            "|    " . mb_str_pad($rank, 2) . "|",
            "└──────┘"
        ];
    }
}