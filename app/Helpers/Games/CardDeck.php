<?php

namespace App\Helpers\Games;


class CardDeck
{
    private $deck = [
        // D diamonds (♦), C clubs (♣), H hearts (♥), S spades (♠)
        // '2','3','4','5','6','7','8','9','10','J','Q','K','A'
        'c2','c3','c4','c5','c6','c7','c8','c9','ct','cj','cq','ck','ca',
        'd2','d3','d4','d5','d6','d7','d8','d9','dt','dj','dq','dk','da',
        'h2','h3','h4','h5','h6','h7','h8','h9','ht','hj','hq','hk','ha',
        's2','s3','s4','s5','s6','s7','s8','s9','st','sj','sq','sk','sa',
    ];

    public function __construct()
    {
        //
    }

    /**
     * Shuffle card deck
     *
     * @return CardDeck
     */
    public function shuffle(): CardDeck
    {
        $shuffledDeck = [];

        $n = count($this->deck);
        while ($n > 0) {
            $shuffledDeck[] = array_splice($this->deck, random_int(0, $n-1), 1)[0];
            $n--;
        }

        $this->deck = $shuffledDeck;

        return $this;
    }

    /**
     * Cut N cards from the deck and append to the end
     *
     * @param $count
     * @return CardDeck
     */
    public function cut($count): CardDeck
    {
        $cutCards = array_splice($this->deck, 0, $count);
        $this->deck = array_merge($this->deck, $cutCards);

        return $this;
    }

    /**
     * Set card deck to a given deck
     *
     * @param $deck
     * @return CardDeck
     */
    public function set($deck): CardDeck
    {
        $this->deck = $deck;

        return $this;
    }

    /**
     * Get a slice of card deck or the whole deck
     *
     * @param int $count
     * @param int $offset - skip given number of cards
     * @return array
     */
    public function get(int $count = 0, int $offset = 0): array
    {
        return $count > 0 ? array_slice($this->deck, $offset, $count) : $this->deck;
    }
}