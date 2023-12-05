<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day5 extends Day
{
    protected const TITLE = 'If You Give A Seed A Fertilizer';

    protected array $seeds = [];
    protected array $maps = [];

    protected function loadData(): void
    {
        parent::loadData();
        $this->data = array_filter($this->data);
        $map = '';
        foreach ($this->data as $line) {
            if (strpos($line, 'seeds:') !== false) {
                preg_match_all('/\d+/', $line, $matches);
                $this->seeds = $matches[0];
            } elseif (strpos($line, '-to-') !== false) {
                preg_match('/(?<map>\w+-to-\w+)/', $line, $matches);
                $map = $matches['map'];
            } else {
                preg_match('/(?<dest>\d+) (?<src>\d+) (?<len>\d+)/', $line, $matches);
                $this->maps[$map][] = [
                    'dest' => (int)$matches['dest'],
                    'src' => (int)$matches['src'],
                    'len' => (int)$matches['len'],
                ];
            }
        }
    }

    protected function part1(): Result
    {
        $answer = PHP_INT_MAX;
        foreach ($this->seeds as $seed) {
            $resource = $seed;
            foreach ($this->maps as $ranges) {
                foreach ($ranges as $range) {
                    if ($resource >= $range['src'] && $resource < ($range['src'] + $range['len'])) {
                        $resource += ($range['dest'] - $range['src']);
                        break;
                    }
                }
            }
            $answer = min($answer, $resource);
        }
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = PHP_INT_MAX;
        $mapIndex = array_keys($this->maps);
        $target = count($mapIndex) - 1;

        for ($i = 0; $i < count($this->seeds); $i += 2) {
            $stack[] = [
                'index' => -1,
                'range' => [
                    $this->seeds[$i],
                    $this->seeds[$i] + $this->seeds[$i + 1] - 1,
                ],
            ];
        }

        while (!empty($stack)) {
            $currentRange = array_pop($stack);

            // Our target map is the current map, we're done
            if ($target === $currentRange['index']) {
                $answer = min($answer, $currentRange['range'][0]);
                continue;
            }

            // Iterate through ranges for our current map
            foreach ($this->maps[$mapIndex[$currentRange['index'] + 1]] as $range) {
                $mapRange = [
                    $range['src'],
                    $range['src'] + $range['len'] - 1
                ];

                // Find the intersection of our range and the new range
                if ($currentRange['range'][0] <= $mapRange[1] && $mapRange[0] <= $currentRange['range'][1]) {

                    // Add this intersection to the next map
                    $shift = $range['dest'] - $range['src'];
                    $overlap = [
                        max($currentRange['range'][0], $mapRange[0]),
                        min($currentRange['range'][1], $mapRange[1]),
                    ];
                    $stack[] = [
                        'index' => $currentRange['index'] + 1,
                        'range' => array_map(function ($range) use ($shift) {
                            return $range + $shift;
                        }, $overlap),
                    ];

                    // Add the new ranges to the stack
                    if ($currentRange['range'][0] < $overlap[0]) {
                        $stack[] = [
                            'index' => $currentRange['index'],
                            'range' => [$currentRange['range'][0], $overlap[0] - 1],
                        ];
                    }

                    if ($currentRange['range'][1] > $overlap[1]) {
                        $stack[] = [
                            'index' => $currentRange['index'],
                            'range' => [$overlap[1] + 1, $currentRange['range'][1]],
                        ];
                    }

                    continue 2;
                }
            }

            // We haven't matched any ranges in our target map, so move to the next map with the same range
            $stack[] = [
                'index' => $currentRange['index'] + 1,
                'range' => $currentRange['range']
            ];
        }
        return new Result(Result::PART2, $answer);
    }
}