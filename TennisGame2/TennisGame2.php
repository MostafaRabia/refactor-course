<?php

declare(strict_types=1);

namespace TennisGame2;

use Base\TennisGame;

class TennisGame2 implements TennisGame
{
    private int $P1point = 0;

    private int $P2point = 0;

    private string $P1res = '';

    private string $P2res = '';

    public function __construct(
        private string $player1Name,
        private string $player2Name
    ) {
    }

    public function getScore(): string
    {
        $score = '';
        $score = $this->handleEqualAndLessThan3($score);

        $score = $this->handleEqualAndMoreThanOrEqualThree($score);

        $score = $this->handleAdvantage($score);

        $score = $this->handleWin($score);

        $score = $this->handleAnotherCases($score);

        return $score;
    }

    public function wonPoint(string $player): void
    {
        if ($player === 'player1') {
            $this->P1Score();
        } else {
            $this->P2Score();
        }
    }

    private function SetP1Score(int $number): void
    {
        for ($i = 0; $i < $number; $i++) {
            $this->P1Score();
        }
    }

    private function SetP2Score(int $number): void
    {
        for ($i = 0; $i < $number; $i++) {
            $this->P2Score();
        }
    }

    private function P1Score(): void
    {
        $this->P1point++;
    }

    private function P2Score(): void
    {
        $this->P2point++;
    }

    private function handleEqualAndLessThan3(string $score): string
    {
        if ($this->P1point !== $this->P2point || $this->P1point >= 3) {
            return $score;
        }

        return match ($this->P1point) {
            0 => 'Love',
            1 => 'Fifteen',
            2 => 'Thirty',
        }.'-All';
    }

    private function handleEqualAndMoreThanOrEqualThree(string $score): string
    {
        return match (true) {
            $this->P1point === $this->P2point && $this->P1point >= 3 => 'Deuce',
            default => $score,
        };
    }

    private function handleAnotherCases(string $score): string
    {
        if (!empty($score)) {
            return $score;
        }

        $this->P1res = match ($this->P1point) {
            0 => 'Love',
            1 => 'Fifteen',
            2 => 'Thirty',
            3 => 'Forty',
            default => '',
        };

        $this->P2res = match ($this->P2point) {
            0 => 'Love',
            1 => 'Fifteen',
            2 => 'Thirty',
            3 => 'Forty',
            default => '',
        };

        return "{$this->P1res}-{$this->P2res}";
    }

    private function handleAdvantage(string $score): string
    {
        return match (true) {
            $this->P1point > $this->P2point && $this->P2point >= 3 => 'Advantage player1',
            $this->P2point > $this->P1point && $this->P1point >= 3 => 'Advantage player2',
            default => $score,
        };
    }

    private function handleWin(string $score): string
    {
        return match (true) {
            $this->P1point >= 4 && $this->P1point - $this->P2point >= 2 => 'Win for player1',
            $this->P2point >= 4 && $this->P2point - $this->P1point >= 2 => 'Win for player2',
            default => $score,
        };
    }
}
