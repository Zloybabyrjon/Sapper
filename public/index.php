<?php
require __DIR__ . '/../vendor/autoload.php';

use Egor\Sapper\Game;

session_start();

$rows = $_GET['rows'] ?? 10;
$cols = $_GET['cols'] ?? 10;
$mines = $_GET['mines'] ?? 10;

if (!isset($_SESSION['game']) || isset($_GET['reset'])) {
    $_SESSION['game'] = new Game($rows, $cols, $mines);
}

$game = $_SESSION['game'];
$board = $game->getBoard();

// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $row = $_POST['row'];
    $col = $_POST['col'];

    if (isset($_POST['flag'])) {
        $board->getCell($row, $col)->toggleFlag(); // Если флажок
    } else {
        $game->play((int)$row, (int)$col); // Если не флажок
    }
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Сапёр</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Сапёр</h1>
    <div class="options">
        <form action="index.php" method="get">
            <label for="rows">Строки:</label>
            <input type="number" id="rows" name="rows" value="<?= $rows ?>">
            <label for="cols">Столбцы:</label>
            <input type="number" id="cols" name="cols" value="<?= $cols ?>">
            <label for="mines">Мины:</label>
            <input type="number" id="mines" name="mines" value="<?= $mines ?>">
            <button type="submit" name="reset" value="1">Новая игра</button>
        </form>
        <button id="rulesButton">Правила</button>
    </div>
    <div id="rulesModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Правила игры в Сапёр</h2>
            <p>Цель игры в Сапёр — очистить прямоугольное поле, содержащее скрытые «мины», не подорвав ни одной из них.</p>
            <p>
                Каждая клетка на поле может находиться в одном из следующих состояний:
            <ul>
                <li>Скрытая, представлена # на игровом поле</li>
                <li>Отмеченная флажком, предположительно мина, представлена 🚩 на игровом поле</li>
                <li>Открытая и пустая, что означает отсутствие мин рядом с ней</li>
                <li>Открытая и с числом, обозначающим количество мин, прилегающих к ней.</li>
                <li>Открытая и с миной, представлена *.</li>
            </ul>
            </p>
            <p>
                Щелкните левой кнопкой мыши по скрытой клетке, чтобы открыть ее.
                Нажмите F на клавиатуре что бы поставить/убрать флажок.
            </p>
            <p>Если вы откроете клетку с миной, игра окончена. Если вам удастся открыть все клетки, не содержащие мин, вы выигрываете!</p>
        </div>
    </div>
    <div class="board">
        <?php if ($game->isGameOver() || $game->isGameWon()): ?>
            <?php if ($game->isGameOver()): ?>
                <h2 class="game-over-message">Игра окончена! Вы наткнулись на мину.</h2>
            <?php else: ?>
                <h2 class="game-win-message">Вы выиграли!</h2>
            <?php endif; ?>
            <table class="game-table">
                <?php for ($row = 0; $row < $board->getRows(); $row++): ?>
                    <tr>
                        <?php for ($col = 0; $col < $board->getCols(); $col++): ?>
                            <td>
                                <span class="cell <?php if ($board->getCell($row, $col)->isMine()): ?>mine<?php endif; ?>"><?= $board->getCell($row, $col)->getDisplayValue() ?></span>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php else: ?>
            <table class="game-table">
                <?php for ($row = 0; $row < $board->getRows(); $row++): ?>
                    <tr>
                        <?php for ($col = 0; $col < $board->getCols(); $col++): ?>
                            <td>
                                <form action="index.php" method="post" class="cell-form">
                                    <input type="hidden" name="row" value="<?= $row ?>">
                                    <input type="hidden" name="col" value="<?= $col ?>">
                                    <button type="submit" class="cell"> <?= $board->getCell($row, $col)->getDisplayValue() ?></button>
                                </form>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        <?php endif; ?>
    </div>
    <script>
        // Get the modal
        var modal = document.getElementById("rulesModal");
        // Get the button that opens the modal
        var btn = document.getElementById("rulesButton");
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        document.addEventListener('keydown', function(event) {
            if (event.key === 'f' || event.key === 'F') {
                const focusedElement = document.activeElement;
                if (focusedElement && focusedElement.matches(".cell")) {
                    const form = focusedElement.closest('.cell-form');
                    const formData = new FormData(form);
                    formData.append('flag', 'true');
                    fetch(form.action, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                window.location.reload();
                            }
                        });
                }
            }
        });
    </script>
</body>

</html>