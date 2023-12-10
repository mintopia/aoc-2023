<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day9 extends Day
{
    protected const TITLE = 'Mirage Maintenance';
    protected array $sets;

    protected function loadData(): void
    {
        parent::loadData();
        $this->sets = array_map(function ($set) {
            return explode(' ', $set);
        }, $this->data);
    }

    protected function part1(): Result
    {
        $answer = array_reduce($this->sets, function ($carry, $set) {
            return $carry + $this->getForecast($set);
        }, 0);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = array_reduce($this->sets, function ($carry, $set) {
            $set = array_reverse($set);
            return $carry + $this->getForecast($set);
        }, 0);
        return new Result(Result::PART2, $answer);
    }

    protected function getForecast(array $set): int
    {
        $diffSet = $set;
        do {
            $diffSet = $this->getDifferences($diffSet);
            $diffs[] = $diffSet;
        } while (count(array_count_values($diffSet)) !== 1);
        return array_reduce($diffs, function ($carry, $diffSet) {
            return $carry + end($diffSet);
        }, end($set));
    }

    protected function getDifferences(array $set): array
    {
        $diffs = [];
        for ($i = 1; $i < count($set); $i++) {
            $diffs[] = $set[$i] - $set[$i - 1];
        }
        return $diffs;
    }
}