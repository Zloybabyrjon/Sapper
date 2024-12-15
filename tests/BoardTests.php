<?php

namespace Tests;

use Egor\Sapper\Board;
use PHPUnit\Framework\TestCase;

class BoardTest extends TestCase
{
    public function testBoardCreation()
    {
        $board = new Board(5, 5, 5);
        $this->assertEquals(5, $board->getRows());
        $this->assertEquals(5, $board->getCols());

        $mineCount = 0;
        for ($row = 0; $row < 5; $row++) {
           for ($col = 0; $col < 5; $col++){
               if($board->getCell($row, $col)->isMine()){
                   $mineCount++;
               }
           }
        }
        $this->assertEquals(5, $mineCount);
    }
    
     public function testRevealCell()
    {
        $board = new Board(3, 3, 0);
        $cell = $board->getCell(1,1);
        $this->assertFalse($cell->isRevealed());
        $board->revealCell(1,1);
        $this->assertTrue($cell->isRevealed());
    }
    
    public function testIsGameOver()
    {
        $board = new Board(3, 3, 3);
       for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if($board->getCell($row, $col)->isMine()){
                     $board->revealCell($row, $col);
                     $this->assertTrue($board->isGameOver());
                     return;
                }
            }
        }
        
        $this->assertFalse($board->isGameOver());
    }
    
    public function testIsGameWon()
    {
         $board = new Board(2, 2, 0);
         $this->assertFalse($board->isGameWon());
        for ($row = 0; $row < 2; $row++) {
            for ($col = 0; $col < 2; $col++) {
                 $board->revealCell($row, $col);
            }
        }
         
       $this->assertTrue($board->isGameWon());
    }
}