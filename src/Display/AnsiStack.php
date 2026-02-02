<?php

namespace MartijnGastkemper\Canasta\Display;

use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Text\Line;
use PhpTui\Tui\Text\Text;

final class AnsiStack {

    public static function fromAnsiCard(AnsiCard $card): self {
        return new self($card->getRawLines());
    }

    /**
     * @param array<int, string> $lines
     */
    public function __construct(private array $lines = []) {
    }

    public function push(AnsiCard $card): self {
        return new self(array_merge($this->lines, $card->getRawLines()));
    }

    public function toParagraphWidget(): ParagraphWidget {
        return ParagraphWidget::fromText(
            new Text(
                array_map(
                    fn(string $line) => Line::fromString($line),
                    $this->lines
                )
            ),
        );
    }
}