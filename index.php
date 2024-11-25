<?php
session_start(); 


if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = array_fill(0, 9, ''); 
    $_SESSION['turn'] = 'X'; 
    $_SESSION['winner'] = null; 
}

// reseta o jogo
if (isset($_POST['reset'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit(); 
}

// jogada
if (isset($_POST['cell']) && $_SESSION['winner'] === null) {
    $cell = $_POST['cell'];
    if ($_SESSION['board'][$cell] === '') { 
        $_SESSION['board'][$cell] = $_SESSION['turn']; 
        $_SESSION['turn'] = ($_SESSION['turn'] === 'X') ? 'O' : 'X'; 
    }
}

// Verifica w
$winning_combinations = [
    [0, 1, 2], [3, 4, 5], [6, 7, 8], // Linhas
    [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colunas
    [0, 4, 8], [2, 4, 6]             // Diagonais
];

// vencedor
foreach ($winning_combinations as $combo) {
    if ($_SESSION['board'][$combo[0]] &&
        $_SESSION['board'][$combo[0]] === $_SESSION['board'][$combo[1]] &&
        $_SESSION['board'][$combo[1]] === $_SESSION['board'][$combo[2]]) {
        $_SESSION['winner'] = $_SESSION['board'][$combo[0]]; 
    }
}

// empate
if (!in_array('', $_SESSION['board']) && $_SESSION['winner'] === null) {
    $_SESSION['winner'] = 'Empate'; 
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Velha</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='45' fill='%23d4acf0'/%3E%3Cpath d='M35 35 L65 65 M65 35 L35 65' stroke='%23fff' stroke-width='8' stroke-linecap='round'/%3E%3C/svg%3E">
</head>
<body>
    <div class="container">
        <h1>Jogo da Velha</h1>
        <div class="board">
            <form method="post">
                <?php for ($row = 0; $row < 3; $row++): ?>
                    <div class="row">
                        <?php for ($col = 0; $col < 3; $col++): 
                            $i = ($row * 3) + $col; ?>
                            <button 
                                class="cell" 
                                type="submit" 
                                name="cell" 
                                value="<?= $i ?>" 
                                <?= $_SESSION['board'][$i] !== '' || $_SESSION['winner'] !== null ? 'disabled' : '' ?>>
                                <?= $_SESSION['board'][$i] ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </form>
        </div>
        <?php if ($_SESSION['winner']): ?>
            <div class="message-box">
                <h2>
                    <?= $_SESSION['winner'] === 'Empate' ? 'O jogo terminou em empate!' : 'O vencedor é: ' . $_SESSION['winner'] ?>
                </h2>
            </div>
        <?php else: ?>
            <div class="message-box">
                <h2>Vez do jogador: <?= $_SESSION['turn'] ?></h2>
            </div>
        <?php endif; ?>
        <form method="post">
            <button name="reset">Reiniciar Jogo</button>
        </form>
    </div>
</body>
</html>
