<?php

declare(strict_types=1);

namespace TennisGame1;

use Base\TennisGame;

class TennisGame1 implements TennisGame
{
    private int $m_score1 = 0;

    private int $m_score2 = 0;

    public function __construct(
        private string $player1Name,
        private string $player2Name
    ) {
    }

    public function wonPoint(string $playerName): void
    {
        $playerName === $this->player1Name
            ? $this->m_score1++
            : $this->m_score2++;
    }

    public function getScore(): string
    {
        if ($this->m_score1 === $this->m_score2) {
            return $this->scoreLabelOfEqualScores();
        }

        if ($this->m_score1 >= 4 || $this->m_score2 >= 4) {
            return $this->handleScoreMoreThanOrEqualToFour();
        }

        return $this->handleLoop();
    }

    private function scoreLabelOfEqualScores(): string
    {
        return match ($this->m_score1) {
            0 => 'Love-All',
            1 => 'Fifteen-All',
            2 => 'Thirty-All',
            default => 'Deuce',
        };
    }

    private function handleScoreMoreThanOrEqualToFour(): string
    {
        $minusResult = $this->m_score1 - $this->m_score2;

        return match ($minusResult) {
            1 => 'Advantage player1',
            -1 => 'Advantage player2',
            default => $minusResult >= 2 ? 'Win for player1' : 'Win for player2',
        };
    }

    private function handleLoop(): string
    {
        $score = '';

        for ($i = 1; $i < 3; $i++) {
            if ($i === 1) {
                $tempScore = $this->m_score1;
            } else {
                $score .= '-';
                $tempScore = $this->m_score2;
            }
            switch ($tempScore) {
                case 0:
                    $score .= 'Love';
                    break;
                case 1:
                    $score .= 'Fifteen';
                    break;
                case 2:
                    $score .= 'Thirty';
                    break;
                case 3:
                    $score .= 'Forty';
                    break;
            }
        }
        return $score;
    }
}
