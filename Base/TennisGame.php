<?php

declare(strict_types=1);

namespace Base;

interface TennisGame
{
    public function wonPoint(string $playerName): void;

    public function getScore(): string;
}
