<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day3 extends Day
{
    protected const TITLE = 'Gear Ratios';

    protected function loadData(): void
    {
        parent::loadData();
        foreach ($this->data as $i => $line) {
            $this->data[$i] = str_split($line);
        }
    }

    protected function part1(): Result
    {
        $answer = 0;
        foreach ($this->data as $y => $line) {
            $currentNumber = '';
            $isPartNumber = false;
            foreach ($line as $x => $char) {
                if (!is_numeric($char)) {
                    if ($currentNumber && $isPartNumber) {
                        $answer += (int)$currentNumber;
                    }
                    $currentNumber = '';
                    $isPartNumber = false;
                    continue;
                }
                $currentNumber .= $char;
                if ($isPartNumber) {
                    continue;
                } else {
                    $isPartNumber = $this->checkIsPartNumber($x, $y);
                }
            }
            if ($currentNumber && $isPartNumber) {
                $answer += (int)$currentNumber;
            }
        }
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = 0;
        foreach($this->data as $y => $line) {
            foreach ($line as $x => $char) {
                if ($char === '*') {
                    $answer += $this->getGearRatio($x, $y);
                }
            }
        }
        return new Result(Result::PART2, $answer);
    }

    protected function getGearRatio($x, $y): int
    {
        $numbers = [];
        foreach (range($y - 1, $y + 1) as $iY) {
            foreach (range($x - 1, $x + 1) as $iX) {
                $chr = $this->data[$iY][$iX];
                if (!is_numeric($chr)) {
                    continue;
                } else {
                    [$num, $location] = $this->expandNumber($iX, $iY);
                    $numbers[$location] = $num;
                }
            }
        }

        if (count($numbers) === 2) {
            return array_product($numbers);
        }
        return 0;
    }

    protected function expandNumber(int $iX, int $y): array
    {
        $start = $iX;
        $number = $this->data[$y][$iX];
        for($x = $iX - 1; $x >= 0; $x--) {
            $char = $this->data[$y][$x];
            if (is_numeric($char)) {
                $start = $x;
                $number = $char . $number;
            } else {
                break;
            }
        }
        for($x = $iX + 1; $x < count($this->data[$y]); $x++) {
            $char = $this->data[$y][$x];
            if (is_numeric($char)) {
                $number .= $char;
            } else {
                break;
            }
        }

        return [(int)$number, "{$start},{$y}"];
    }

    protected function checkIsPartNumber(int $x, int $y): bool
    {
        foreach (range($x - 1, $x + 1) as $iX) {
            foreach (range($y - 1, $y + 1) as $iY) {
                $char = $this->data[$iY][$iX] ?? '.';
                if (is_numeric($char)) {
                    continue;
                }
                if ($char === '.') {
                    continue;
                }
                return true;
            }
        }
        return false;
    }
}