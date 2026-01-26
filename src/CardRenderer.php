<?php

namespAce MartijnGastkemper\Canasta;

final class CardRenderer {

    public function render(CardInterface $card): string {
        if ($card instanceof Joker) {
            return bgWhite(mb_chr(0x2009) . '🤡' . mb_chr(0x2009));
        }

        if ($card->suite === Suite::Clubs) return bgWhite(mb_chr(0x2009) . "♣️ " . $card->rank->character() . mb_chr(0x2009));
        if ($card->suite === Suite::Diamonds) return bgWhite(mb_chr(0x2009) . '♦️ ' . $card->rank->character() . mb_chr(0x2009));
        if ($card->suite === Suite::Hearts) return bgWhite(mb_chr(0x2009) . '♥️ ' . $card->rank->character() . mb_chr(0x2009));
        if ($card->suite === Suite::Spades) return bgWhite(mb_chr(0x2009) . '♠️ ' . $card->rank->character() . mb_chr(0x2009));

        throw new \Exception('Unknown card type');
    }
}