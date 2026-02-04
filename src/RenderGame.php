<?php

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\Display\AnsiCard;
use MartijnGastkemper\Canasta\Display\AnsiStack;
use MartijnGastkemper\Canasta\Display\HandWidgetRenderer;
use MartijnGastkemper\Canasta\Display\HandWidget;
use MartijnGastkemper\Canasta\Events\CursorMoved;
use MartijnGastkemper\Canasta\Events\GameStarted;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Display\Display;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Layout\Constraint;
use PhpTui\Tui\Text\Title;
use PhpTui\Tui\Widget\Direction;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Widget\Widget;

final class RenderGame implements EventListener {
    private Player $hand;
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
                    Constraint::percentage(25),
                    Constraint::percentage(17),
                    Constraint::percentage(15),
                    Constraint::percentage(18),
                    Constraint::percentage(25),
                )
                ->widgets(
                    $this->getHandWidget(),
                    $this->getTableWidget($this->table),
                    $this->getPoolWidget(),
                    $this->getTableWidget($this->table),
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

    private function getTableWidget(Table $table): Widget {
        /** @var array<int, ParagraphWidget> $slotWidgets */
        $slotWidgets = [];

        foreach (Rank::cases() as $rank) {
            if ($rank === Rank::Two) continue;

            $canasta = $table->getCanasta($rank);

            if ($canasta) {
                $stack = $canasta->getCards()->reduce(
                    fn(AnsiStack $stack, CardInterface $card, int $key) => $key === $canasta->getCards()->count() - 1
                        ? $stack->push(AnsiCard::fromCard($card)->full())
                        : $stack->push(AnsiCard::fromCard($card)->top())
                    ,
                    new AnsiStack()
                );
            } else {
                $stack = AnsiStack::fromAnsiCard(AnsiCard::fromRank($rank));
            }

            $slotWidgets[] = $stack->toParagraphWidget();
        }

        return BlockWidget::default()
            ->titles(Title::fromString($table->teamName))
            ->widget(
                GridWidget::default()
                ->direction(Direction::Horizontal)
                ->constraints(...array_fill(
                    0,
                    count($slotWidgets),
                    Constraint::percentage((int)round(100 / count($slotWidgets)))
                ))
                ->widgets(...$slotWidgets)
            );
    }
}