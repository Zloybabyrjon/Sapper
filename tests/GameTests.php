<?php

namespace Tests;

use Egor\Sapper\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testGameCreation()
    {
        $game = new Game(5, 5, 5);
        $this->assertFalse($game->isGameOver());
    }
    
    public function testGamePlay()
    {
        $game = new Game(3,3,1);
        $game->play(0,0);
         $this->assertFalse($game->isGameOver());
        
    }
     public function testGameOver()
    {
        $game = new Game(3,3,1);
         for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if($game->getBoard()->getCell($row, $col)->isMine()){
                     $game->play($row, $col);
                     $this->assertTrue($game->isGameOver());
                      return;
                }
            }
         }
        
    }
    
    public function testGameWon()
    {
        $game = new Game(2,2,0);
          for ($row = 0; $row < 2; $row++) {
            for ($col = 0; $col < 2; $col++) {
                $game->play($row, $col);
            }
         }
         $this->assertTrue($game->isGameWon());
    }
}