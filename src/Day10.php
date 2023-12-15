<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day10 extends Day
{
    protected const TITLE = 'Pipe Maze';

    protected array $maze;
    protected array $start;

    protected function loadData(): void
    {
        parent::loadData();
        foreach ($this->data as $row => $line) {
            $this->maze[$row] = str_split($line);
            $start = strpos($line, 'S');
            if ($start !== false) {
                $this->start = [$row, $start];
            }
        }
    }

    protected function part1(): Result
    {
        [$startRow, $startCol] = $this->start;
        $directions = [
            [$startRow - 1, $startCol],
            [$startRow + 1, $startCol],
            [$startRow, $startCol - 1],
            [$startRow, $startCol + 1],
        ];
        $paths = [];
        foreach ($directions as $direction) {
            $paths[] = $this->getPath($direction);
        }
        $longestPath = null;
        $answer = PHP_INT_MIN;
        foreach ($paths as $path) {
            $distance = ceil(count($path) / 2);
            if ($distance > $answer) {
                $answer = $distance;
                $longestPath = $path;
            }
        }

        return new Result(Result::PART1, $answer, $longestPath);
    }

    protected function part2(Result $part1): Result
    {
        $boundary = $part1->carry;
        $answer = 0;

        $edgePipes = [
            '|',
            'J',
            'L',
        ];

        if ($boundary[0][0] === $boundary[1][0]) {
            $edgePipes[] = 'S';
        }

        foreach ($this->maze as $row => $cols) {
            $inside = false;
            foreach ($cols as $col => $symbol) {
                if (in_array([$row, $col], $boundary)) {
                    if (in_array($this->maze[$row][$col], $edgePipes)) {
                        $inside = !$inside;
                    }
                } elseif ($inside) {
                    $answer++;
                }
            }
        }

        return new Result(Result::PART2, $answer);
    }

    protected function getPath(array $start): array
    {
        $directions = [
            '|' => [
                [-1, 0],
                [1, 0],
            ],
            '-' => [
                [0, -1],
                [0, 1],
            ],
            '7' => [
                [0, -1],
                [1, 0],
            ],
            'J' => [
                [-1, 0],
                [0, -1],
            ],
            'L' => [
                [-1, 0],
                [0, 1],
            ],
            'F' => [
                [0, 1],
                [1, 0],
            ],
            '.' => [],
            'S' => [],
        ];
        $queue = new \SplQueue();
        $path = [$this->start];
        $queue->enqueue([$start[0], $start[1], $path]);
        $visited = [
            $start,
        ];
        while (!$queue->isEmpty()) {
            [$currentRow, $currentCol, $path] = $queue->dequeue();
            $visited[] = [$currentRow, $currentCol];
            $path[] = [$currentRow, $currentCol];
            $current = $this->maze[$currentRow][$currentCol] ?? '.';
            $matrix = $directions[$current];
            foreach ($matrix as [$rowTransform, $colTransform]) {
                $row = $currentRow + $rowTransform;
                $col = $currentCol + $colTransform;
                if (count($path) > 2 && [$row, $col] === $this->start) {
                    return $path;
                }
                if (!in_array([$row, $col], $visited)) {
                    $queue->enqueue([$row, $col, $path]);
                }
            }
        }
        return [];
    }
}