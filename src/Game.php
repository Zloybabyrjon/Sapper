<?php

namespace Egor\Sapper;

class Game
{
    private Board $board;
    private bool $isGameOver = false;
    private bool $isGameWon = false;

    public function __construct(int $rows, int $cols, int $mines)
    {
        $this->board = new Board($rows, $cols, $mines);
    }

    public function play(int $row, int $col): void
    {
        if ($this->isGameOver || $this->isGameWon) {
            return;
        }

        $this->board->revealCell($row, $col);
        if ($this->board->isGameOver()) {
            $this->isGameOver = true;
           $this->revealAllMines();
            return;
        }

        if ($this->board->isGameWon()) {
             $this->isGameWon = true;
            $this->revealAllMines();
           return;
        }
    }
    
    private function revealAllMines() {
        for ($row = 0; $row < $this->board->getRows(); $row++) {
            for ($col = 0; $col < $this->board->getCols(); $col++) {
                 if($this->board->getCell($row, $col)->isMine()){
                    $this->board->revealCell($row, $col);
                }
            }
        }
    }

    public function isGameOver(): bool
    {
        return $this->isGameOver;
    }

    public function isGameWon(): bool
    {
        return $this->isGameWon;
    }

     public function getBoard(): Board
    {
        return $this->board;
    }
}