<?php

namespace Tests;

use Egor\Sapper\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function testCellCreation()
    {
        $cell = new Cell();
        $this->assertFalse($cell->isMine());
        $this->assertEquals(0, $cell->getAdjacentMines());
        $this->assertFalse($cell->isRevealed());

        $mineCell = new Cell(true);
        $this->assertTrue($mineCell->isMine());
    }
    
     public function testSetAndGetAdjacentMines()
    {
        $cell = new Cell();
        $cell->setAdjacentMines(3);
        $this->assertEquals(3, $cell->getAdjacentMines());
    }

    public function testRevealCell()
    {
        $cell = new Cell();
        $this->assertFalse($cell->isRevealed());
        $cell->reveal();
        $this->assertTrue($cell->isRevealed());
    }

    public function testGetDisplayValue()
    {
        $cell = new Cell();
        $this->assertEquals("#", $cell->getDisplayValue());

        $cell->reveal();
        $this->assertEquals(" ", $cell->getDisplayValue());

        $cell->setAdjacentMines(2);
        $this->assertEquals("2", $cell->getDisplayValue());

        $mineCell = new Cell(true);
        $mineCell->reveal();
        $this->assertEquals("*", $mineCell->getDisplayValue());
    }
}