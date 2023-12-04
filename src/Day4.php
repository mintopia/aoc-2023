<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day4 extends Day
{
    protected const TITLE = 'Scratchcards';
    protected function loadData(): void
    {
        parent::loadData();
        $cards = [];
        foreach ($this->data as $line) {
            preg_match('/^Card\s*(?<num>\d+):(?<winning>(\s*\d+)*).*\|(?<ticket>(\s*\d+)*)$/', $line, $matches);
            $id = $matches['num'];
            preg_match_all('/\d+/', $matches['winning'], $winning);
            preg_match_all('/\d+/', $matches['ticket'], $ticket);
            $cards[$id] = (object)[
                'id' => $id,
                'count' => 1,
                'wins' => 0,
                'winning' => $winning[0],
                'ticket' => $ticket[0],
            ];
        }
        $this->data = $cards;
    }

    protected function part1(): Result
    {
        $answer = 0;
        foreach ($this->data as $card) {
            $intersect = array_intersect($card->winning, $card->ticket);
            $wins = count($intersect);
            $this->data[$card->id]->wins = $wins;
            if ($wins > 0) {
                $answer += pow(2, $wins - 1);
            }
        }
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = 0;
        foreach ($this->data as $card) {
            if ($card->wins > 0) {
                foreach (range(1, $card->wins) as $i) {
                    if (isset($this->data[$card->id + $i])) {
                        $this->data[$card->id + $i]->count += $card->count;
                    }
                }
            }
            $answer += $card->count;
        }

        return new Result(Result::PART2, $answer);
    }
}