<?php

namespace Egor\Sapper;

class Board
{
    private array $cells;
    private int $rows;
    private int $cols;

    public function __construct(int $rows, int $cols, int $mines)
    {
        $this->rows = $rows;
        $this->cols = $cols;
        $this->cells = $this->createBoard($mines);
    }

    private function createBoard(int $mines): array
    {
        $cells = [];
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $cells[$i][$j] = new Cell();
            }
        }
        
        $this->placeMines($cells, $mines);
        $this->calculateAdjacentMines($cells);
        return $cells;
    }

    private function placeMines(array &$cells, int $mines): void
    {
        $minesPlaced = 0;
        while ($minesPlaced < $mines) {
            $row = random_int(0, $this->rows - 1);
            $col = random_int(0, $this->cols - 1);

            if (!$cells[$row][$col]->isMine()) {
                $cells[$row][$col] = new Cell(true);
                $minesPlaced++;
            }
        }
    }

    private function calculateAdjacentMines(array &$cells): void
    {
        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->cols; $col++) {
                if (!$cells[$row][$col]->isMine()) {
                    $count = $this->countAdjacentMines($cells, $row, $col);
                    $cells[$row][$col]->setAdjacentMines($count);
                }
            }
        }
    }
    
    private function countAdjacentMines(array &$cells, int $row, int $col): int
    {
        $count = 0;
        for ($i = $row - 1; $i <= $row + 1; $i++) {
            for ($j = $col - 1; $j <= $col + 1; $j++) {
                if ($i >= 0 && $i < $this->rows && $j >= 0 && $j < $this->cols && ($i !== $row || $j !== $col) && $cells[$i][$j]->isMine()) {
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getCell(int $row, int $col): Cell
    {
        return $this->cells[$row][$col];
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function getCols(): int
    {
        return $this->cols;
    }

    public function displayBoard(): string
    {
        $output = "   ";
        for ($i=0; $i < $this->cols; $i++) {
            $output .= $i." ";
        }

        $output .= PHP_EOL;
        for ($row = 0; $row < $this->rows; $row++) {
            $output .= $row."  ";
            for ($col = 0; $col < $this->cols; $col++) {
                $output .= $this->cells[$row][$col]->getDisplayValue() . " ";
            }
            $output .= PHP_EOL;
        }
        return $output;
    }

    public function revealCell(int $row, int $col): void
    {
        $this->cells[$row][$col]->reveal();

        if ($this->cells[$row][$col]->getAdjacentMines() === 0 && !$this->cells[$row][$col]->isMine()) {
            $this->revealAdjacentCells($row, $col);
        }
    }
    
    private function revealAdjacentCells(int $row, int $col): void
    {
        for ($i = $row - 1; $i <= $row + 1; $i++) {
            for ($j = $col - 1; $j <= $col + 1; $j++) {
                if ($i >= 0 && $i < $this->rows && $j >= 0 && $j < $this->cols && !$this->cells[$i][$j]->isRevealed()) {
                    $this->revealCell($i, $j);
                }
            }
        }
    }

    public function isGameOver(): bool
    {
        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->cols; $col++) {
                if ($this->cells[$row][$col]->isMine() && $this->cells[$row][$col]->isRevealed()) {
                    return true;
                }
            }
        }
        return false;
    }
    
    public function isGameWon(): bool
    {
        $hiddenCellsCount = 0;
        for ($row = 0; $row < $this->rows; $row++) {
            for ($col = 0; $col < $this->cols; $col++) {
               if (!$this->cells[$row][$col]->isRevealed() && !$this->cells[$row][$col]->isMine()) {
                    $hiddenCellsCount++;
                }
            }
        }
        return $hiddenCellsCount === 0;
    }
}