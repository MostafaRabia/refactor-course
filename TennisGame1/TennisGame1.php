<?php

declare(strict_types=1);

namespace TennisGame1;

use Base\TennisGame;

class TennisGame1 implements TennisGame
{
    // We could move it to enums, but here for simplicity
    public const LOVE_ALL = 'Love-All';
    public const FIFTEEN_ALL = 'Fifteen-All';
    public const THIRTY_ALL = 'Thirty-All';
    public const DEUCE = 'Deuce';
    public const ADVANTAGE_PLAYER_1 = 'Advantage player1';
    public const ADVANTAGE_PLAYER_2 = 'Advantage player2';
    public const WIN_FOR_PLAYER_1 = 'Win for player1';
    public const WIN_FOR_PLAYER_2 = 'Win for player2';
    public const LOVE = 'Love';
    public const FIFTEEN = 'Fifteen';
    public const THIRTY = 'Thirty';
    public const FORTY = 'Forty';

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
            return $this->getScoreLabelOfEqualScores();
        }

        if ($this->m_score1 >= 4 || $this->m_score2 >= 4) {
            return $this->getScoreLabelForScoreThatMoreThanOrEqualToFour();
        }

        return $this->handleLoop();
    }

    private function getScoreLabelOfEqualScores(): string
    {
        return match ($this->m_score1) {
            0 => self::LOVE_ALL,
            1 => self::FIFTEEN_ALL,
            2 => self::THIRTY_ALL,
            default => self::DEUCE,
        };
    }

    private function getScoreLabelForScoreThatMoreThanOrEqualToFour(): string
    {
        $minusResult = $this->m_score1 - $this->m_score2;

        return match ($minusResult) {
            1 => self::ADVANTAGE_PLAYER_1,
            -1 => self::ADVANTAGE_PLAYER_2,
            default => $minusResult >= 2 ? self::WIN_FOR_PLAYER_1 : self::WIN_FOR_PLAYER_2,
        };
    }

    private function handleLoop(): string
    {
        $score = '';

        for ($i = 1; $i < 3; $i++) {
            [$tempScore, $score] = $this->appendToScore($i, $score);

            $score = $this->getScoreLabelBasedOnScore($tempScore, $score);
        }

        return $score;
    }

    private function appendToScore(int $i, string $score): array
    {
        if ($i === 1) {
            $tempScore = $this->m_score1;

            return [$tempScore, $score];
        }

        $score .= '-';
        $tempScore = $this->m_score2;

        return [$tempScore, $score];
    }

    private function getScoreLabelBasedOnScore(mixed $tempScore, string $score): string
    {
        return match ($tempScore) {
            0 => $score . self::LOVE,
            1 => $score . self::FIFTEEN,
            2 => $score . self::THIRTY,
            3 => $score . self::FORTY,
        };
    }
}
