<?php

namespace Egor\Sapper;

class Cell
{
    private bool $isMine;
    private int $adjacentMines;
    private bool $isRevealed = false;
    private bool $isFlagged = false;

    public function __construct(bool $isMine = false)
    {
        $this->isMine = $isMine;
        $this->adjacentMines = 0;
    }

    public function isMine(): bool
    {
        return $this->isMine;
    }

    public function setAdjacentMines(int $count): void
    {
        $this->adjacentMines = $count;
    }

    public function getAdjacentMines(): int
    {
        return $this->adjacentMines;
    }

    public function isRevealed(): bool
    {
        return $this->isRevealed;
    }

    public function reveal(): void
    {
        $this->isRevealed = true;
    }

    public function isFlagged(): bool
    {
        return $this->isFlagged;
    }
    
    public function toggleFlag(): void
    {
        $this->isFlagged = !$this->isFlagged;
    }

    public function getDisplayValue(): string
    {
        if ($this->isFlagged) {
             return "🚩"; // Flagged cell
        }

        if (!$this->isRevealed) {
            return "#"; // Hidden cell
        }

        if ($this->isMine) {
            return "*"; // Mine revealed
        }

        return $this->adjacentMines > 0 ? (string) $this->adjacentMines : " ";
    }
}