<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day1 extends Day
{
    protected const TITLE = 'Title';

    protected function part1(): Result
    {
        $answer = $this->getAnswer($this->data);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        // To handle overlapping words, replace each word with its number inside the first and last chars
        $replacements = [
            'one' => 'o1e',
            'two' => 't2o',
            'three' => 't3e',
            'four' => 'f4r',
            'five' => 'f5e',
            'six' => 's6x',
            'seven' => 's7n',
            'eight' => 'e8t',
            'nine' => 'n9e',
        ];
        $data = array_map(function(string $line) use ($replacements) {
            return str_replace(array_keys($replacements), $replacements, $line);
        }, $this->data);
        $answer = $this->getAnswer($data);
        return new Result(Result::PART2, $answer);
    }

    protected function getAnswer(array $input): int
    {
        return array_reduce($input, function(int $answer, string $line) {
            // Remove all non-numeric characters
            $numbersOnly = preg_replace('/[^\d]/', '', $line);
            $combined = substr($numbersOnly, 0, 1) . substr($numbersOnly, -1);
            return $answer + (int)$combined;
        }, 0);
    }
}