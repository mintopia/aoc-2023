<?php
namespace Mintopia\Aoc2023;

use Mintopia\Aoc2023\Helpers\Result;

class Day7 extends Day
{
    protected const TITLE = 'Camel Cards';
    protected array $hands = [];

    const HIGH_CARD = 1;
    const ONE_PAIR = 2;
    const TWO_PAIR = 3;
    const THREE_OF_A_KIND = 4;
    const FULL_HOUSE = 5;
    const FOUR_OF_A_KIND = 6;
    const FIVE_OF_A_KIND = 7;

    protected function loadData(): void
    {
        parent::loadData();
        foreach($this->data as $line) {
            [$hand, $bid] = explode(' ', $line);
            $hand = str_split($hand);
            $this->hands[] = [
                $hand, $bid
            ];
        }
    }

    protected function getStrength(array $values): int
    {
        if (in_array(5, $values)) {
            return self::FIVE_OF_A_KIND;
        } elseif (in_array(4, $values)) {
            return self::FOUR_OF_A_KIND;
        } elseif (in_array(3, $values) && in_array(2, $values)) {
            return self::FULL_HOUSE;
        } elseif (in_array(3, $values)) {
            return self::THREE_OF_A_KIND;
        } elseif (in_array(2, $values) && count($values) === 3) {
            return self::TWO_PAIR;
        } elseif (in_array(2, $values)) {
            return self::ONE_PAIR;
        }
        return self::HIGH_CARD;
    }

    protected function part1(): Result
    {
        $hands = [];
        foreach($this->hands as [$hand, $bid]) {
            $values = array_count_values($hand);
            $strength = $this->getStrength($values);
            $hands[] = [
                'hand' => $hand,
                'bid' => $bid,
                'strength' => $strength,
            ];
        }
        $values = array_flip([2, 3, 4, 5, 6, 7, 8, 9, 'T', 'J', 'Q', 'K', 'A']);
        $answer = $this->scoreHands($values, $hands);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $hands = [];
        foreach ($this->hands as [$hand, $bid]) {
            $values = array_count_values(array_filter($hand, function ($val) { return $val !== 'J'; }));
            if (!$values) {
                $values = ['J' => 5];
            }
            $toAdd = count($hand) - array_sum($values);
            asort($values);
            $key = array_key_last($values);
            $values[$key] += $toAdd;

            $strength = $this->getStrength($values);
            $hands[] = [
                'hand' => $hand,
                'bid' => $bid,
                'strength' => $strength,
            ];
        }
        $values =
        $values = array_flip(['J', 2, 3, 4, 5, 6, 7, 8, 9, 'T', 'Q', 'K', 'A']);
        $answer = $this->scoreHands($values, $hands);
        return new Result(Result::PART2, $answer);
    }

    protected function scoreHands(array $values, array $hands): int
    {
        $hands = $this->sortHands($values, $hands);
        $answer = 0;
        foreach ($hands as $i => $hand) {
            $answer += (($i + 1) * $hand['bid']);
        }
        return $answer;
    }

    protected function sortHands(array $values, array $hands): array
    {
        usort($hands, function($alpha, $bravo) use ($values) {
            if ($alpha['strength'] === $bravo['strength']) {
                foreach ($alpha['hand'] as $i => $alphaCard) {
                    if ($values[$alphaCard] > $values[$bravo['hand'][$i]]) {
                        return 1;
                    } elseif ($values[$alphaCard] < $values[$bravo['hand'][$i]]) {
                        return -1;
                    }
                }
                return 1;
            }
            return $alpha['strength'] <=> $bravo['strength'];
        });
        return $hands;
    }
}