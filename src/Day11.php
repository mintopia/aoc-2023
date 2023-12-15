<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day11 extends Day
{
    protected const TITLE = 'Cosmic Expansion';

    protected function loadData(): void
    {
        $this->loadGridFromData();
    }

    protected function part1(): Result
    {
        throw new \Exception('Work In Progress');
        $map = $this->sparseMap($this->data);
        $map = $this->expandSpace($map);
        dd($map);
        $galaxies = [];
        foreach ($this->data as $row => $cols) {
            foreach ($cols as $col => $symbol) {
                if ($symbol === '#') {
                    $galaxies[] = [$row, $col];
                }
            }
        }

        $pairs = [];
        $answer = 0;
        foreach ($galaxies as $g1 => $galaxy1) {
            foreach ($galaxies as $g2 => $galaxy2) {
                $key = [$g1, $g2];
                sort($key);
                if (in_array($key, $pairs)) {
                    continue;
                }
                $pairs[] = $key;
                // Manhattan Distance
                $answer += abs($galaxy1[0] - $galaxy2[0]) + abs($galaxy1[1] - $galaxy2[1]);
            }
        }

        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = 0;
        return new Result(Result::PART2, $answer);
    }

    protected function expandSpace($map): array
    {
        foreach ($map as $row => $cols) {
            foreach ($cols as $col => $char) {

            }
        }
        return $data;
    }

    protected function transposeArray($input): array
    {
        return array_map(null, ...$input);
    }
}