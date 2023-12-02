<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day2 extends Day
{
    protected const TITLE = 'Cube Conundrum';

    protected function part1(): Result
    {
        $answer = 0;
        $maxRed = 12;
        $maxGreen = 13;
        $maxBlue = 14;
        foreach ($this->data as $line) {
            $game = $this->analyseGame($line);
            foreach ($game->sets as $set) {
                if ($set->red > $maxRed) {
                    continue 2;
                }
                if ($set->blue > $maxBlue) {
                    continue 2;
                }
                if ($set->green > $maxGreen) {
                    continue 2;
                }
            }
            $answer += $game->id;
        }
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = 0;
        foreach ($this->data as $line) {
            $game = $this->analyseGame($line);
            $minRed = 0;
            $minBlue = 0;
            $minGreen = 0;
            foreach ($game->sets as $set) {
                $minRed = max($minRed, $set->red);
                $minBlue = max($minBlue, $set->blue);
                $minGreen = max($minGreen, $set->green);
            }
            $answer += ($minRed * $minBlue * $minGreen);
        }
        return new Result(Result::PART2, $answer);
    }

    protected function analyseGame(string $line): object
    {
        [$intro, $games] = explode(':', $line);
        preg_match('/Game (?<id>\d+)/', $intro, $matches);
        $return = (object)[
            'id' => (int)$matches['id'],
            'sets' => [],
        ];
        $sets = explode(';', $games);
        foreach ($sets as $set) {
            $counts = (object)[
                'red' => 0,
                'green' => 0,
                'blue' => 0,
            ];
            preg_match_all('/(\d+) (red|green|blue)/', $set, $matches);
            foreach ($matches[1] as $i => $count) {
                $counts->{$matches[2][$i]} = (int)$count;
            }
            $return->sets[] = $counts;
        }
        return $return;
    }
}