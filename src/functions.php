<?php

namespace MartijnGastkemper\Canasta;

use MartijnGastkemper\Canasta\Display\AnsiCodes;

function ansi(int $code): string {
    $code = str_pad("$code", 3, 0, STR_PAD_LEFT);
    return "\x1b[{$code}m";
}

function off(): string {
    return '\u001b[0m';
}

function bold(string $string): string {
    return ansi(AnsiCodes::$bold) . $string . ansi(AnsiCodes::$off);
}

function italic(string $string): string {
    return ansi(AnsiCodes::$italic) . $string . ansi(AnsiCodes::$off);
}

function underline(string $string): string {
    return ansi(AnsiCodes::$underline) . $string . ansi(AnsiCodes::$off);
}

function blink(string $string): string {
    return ansi(AnsiCodes::$blink) . $string . ansi(AnsiCodes::$off);
}

function inverse(string $string): string {
    return ansi(AnsiCodes::$inverse) . $string . ansi(AnsiCodes::$off);
}

function hidden(string $string): string {
    return ansi(AnsiCodes::$hidden) . $string . ansi(AnsiCodes::$off);
}

function black(string $string): string {
    return ansi(AnsiCodes::$black) . $string . ansi(AnsiCodes::$off);
}

function red(string $string): string {
    return ansi(AnsiCodes::$red) . $string . ansi(AnsiCodes::$off);
}

function green(string $string): string {
    return ansi(AnsiCodes::$green) . $string . ansi(AnsiCodes::$off);
}

function yellow(string $string): string {
    return ansi(AnsiCodes::$yellow) . $string . ansi(AnsiCodes::$off);
}

function blue(string $string): string {
    return ansi(AnsiCodes::$blue) . $string . ansi(AnsiCodes::$off);
}

function magenta(string $string): string {
    return ansi(AnsiCodes::$magenta) . $string . ansi(AnsiCodes::$off);
}

function cyan(string $string): string {
    return ansi(AnsiCodes::$cyan) . $string . ansi(AnsiCodes::$off);
}

function white(string $string): string {
    return ansi(AnsiCodes::$white) . $string . ansi(AnsiCodes::$off);
}

function brightBlack(string $string): string {
    return ansi(AnsiCodes::$brightBlack) . $string . ansi(AnsiCodes::$off);
}

function brightWhite(string $string): string {
    return ansi(AnsiCodes::$brightWhite) . $string . ansi(AnsiCodes::$off);
}

function bgBlack(string $string): string {
    return ansi(AnsiCodes::$blackBg) . $string . ansi(AnsiCodes::$off);
}

function bgRed(string $string): string {
    return ansi(AnsiCodes::$redBg) . $string . ansi(AnsiCodes::$off);
}

function bgGreen(string $string): string {
    return ansi(AnsiCodes::$greenBg) . $string . ansi(AnsiCodes::$off);
}

function bgYellow(string $string): string {
    return ansi(AnsiCodes::$yellowBg) . $string . ansi(AnsiCodes::$off);
}

function bgBlue(string $string): string {
    return ansi(AnsiCodes::$blueBg) . $string . ansi(AnsiCodes::$off);
}

function bgMagenta(string $string): string {
    return ansi(AnsiCodes::$magentaBg) . $string . ansi(AnsiCodes::$off);
}

function bgCyan(string $string): string {
    return ansi(AnsiCodes::$cyanBg) . $string . ansi(AnsiCodes::$off);
}

function bgWhite(string $string): string {
    return ansi(AnsiCodes::$whiteBg) . $string . ansi(AnsiCodes::$off);
}

function set_cursor_position(int $lineNumber, int $columnNumber): void {
    echo "\033[{$lineNumber};{$columnNumber}H";
}

function move_cursor_up(int $lineCount): void {
    echo "\033[{$lineCount}A";
}

function move_cursor_down(int $lineCount): void {
    echo "\033[{$lineCount}B";
}

function move_cursor_forward(int $columnCount): void {
    echo "\033[{$columnCount}C";
}

function move_cursor_backward(int $columnCount): void {
    echo "\033[{$columnCount}D";
}

function clear_screen(): void {
    echo "\033[2J";
}

function erase_to_end_of_line(): void {
    echo "\033[K";
}

function ns_save_cursor_position(): void {
    echo "\033[s";
}

function ns_restore_cursor_position(): void {
    echo "\033[u";
}

/**
 * @return array{0: int, 0: int}
 */
function terminal_cursor_position(): array {
    $ttyprops = trim(shell_exec('stty -g'));
    system('stty -icanon -echo');

    $term = fopen('/dev/tty', 'w');
    fwrite($term, "\033[6n");
    fclose($term);

    $buf = fread(STDIN, 16);

    system("stty '$ttyprops'");

    $matches = [];
    preg_match('/^\033\[(\d+);(\d+)R$/', $buf, $matches);

    return [
        intval($matches[2]),
        intval($matches[1]),
    ];
}

function terminal_width(): int {
    return shell_exec('tput cols');
}

function terminal_height(): int {
    return shell_exec('tput lines');
}