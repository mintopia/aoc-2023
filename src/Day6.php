<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day6 extends Day
{
    protected const TITLE = 'Wait For It';

    protected array $races;

    protected function loadData(): void
    {
        parent::loadData();
        preg_match_all('/\d+/', $this->data[0], $times);
        preg_match_all('/\d+/', $this->data[1], $distances);
        foreach ($times[0] as $i => $time) {
            $this->races[] = [
                'time' => (int)$time,
                'record' => (int)$distances[0][$i],
            ];
        }
    }

    protected function part1(): Result
    {
        $answer = [];
        foreach ($this->races as $race) {
            $answer[] = $this->solveRace($race['time'], $race['record']);
        }
        $answer = array_product($answer);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $time = implode('', array_column($this->races, 'time'));
        $record = implode('', array_column($this->races, 'record'));

        $answer = $this->solveRace($time, $record);

        return new Result(Result::PART2, $answer);
    }

    protected function solveRace(int $time, int $record): int
    {
        $distance = pow($time, 2) - (4 * ($record + 1));

        if ($distance < 0) {
            return 0;
        } elseif ($distance === 0) {
            return $time % 2 ? 1 : 0;
        } else {
            $root = sqrt($distance);
            return (int)(floor(($time + $root) / 2) - ceil(($time - $root) / 2) + 1);
        }
    }
}