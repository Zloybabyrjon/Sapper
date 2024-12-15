<?php

require __DIR__ . '/../vendor/autoload.php';

use Egor\Sapper\Game;

$rows = 10;
$cols = 10;
$mines = 10;

$game = new Game($rows, $cols, $mines);

echo "Welcome to Minesweeper!\n";
echo $game->getBoard()->displayBoard();
while (!$game->isGameOver() && !$game->isGameWon()) {
    $input = readline("Enter row and column (e.g., 1 2): ");
    if (empty($input)) {
        continue;
    }
    [$row, $col] = explode(" ", $input);
    $game->play((int)$row, (int)$col);
}