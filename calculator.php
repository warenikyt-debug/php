<?php
require_once 'functions.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $num1 = isset($_POST['num1']) ? $_POST['num1'] : '';
    $num2 = isset($_POST['num2']) ? $_POST['num2'] : '';
    $operation = isset($_POST['operation']) ? $_POST['operation'] : '';
    
    if ($num1 === '' || $num2 === '') {
        $error = "Ошибка: Заполните оба поля";
    } else {
        $num1 = (float)$num1;
        $num2 = (float)$num2;
        
        switch ($operation) {
            case 'add':
                $result = add($num1, $num2);
                break;
            case 'subtract':
                $result = subtract($num1, $num2);
                break;
            case 'multiply':
                $result = multiply($num1, $num2);
                break;
            case 'divide':
                if ($num2 == 0) {
                    $error = "Ошибка: Деление на ноль невозможно";
                } else {
                    $result = divide($num1, $num2);
                }
                break;
            case 'ackermann':
                if (!is_numeric($num1) || !is_numeric($num2) || 
                    floor($num1) != $num1 || floor($num2) != $num2 ||
                    $num1 < 0 || $num2 < 0) {
                    $error = "Ошибка: Требуются целые неотрицательные числа";
                } else {
                    $result = ackermann((int)$num1, (int)$num2);
                }
                break;
            case 'combinations':
                if (!is_numeric($num1) || !is_numeric($num2) ||
                    floor($num1) != $num1 || floor($num2) != $num2 ||
                    $num1 < $num2 || $num1 < 0 || $num2 < 0) {
                    $error = "Ошибка: Требуется n ≥ m ≥ 0, целые числа";
                } else {
                    $result = combinations((int)$num1, (int)$num2);
                }
                break;
            case 'a_function':
                if (!is_numeric($num1) || floor($num1) != $num1 || $num1 < 1) {
                    $error = "Ошибка: Требуется натуральное число";
                } else {
                    $result = a_function((int)$num1);
                }
                break;
            case 'logarithm':
                if ($num1 <= 0 || $num1 == 1 || $num2 <= 0) {
                    $error = "Ошибка: Основание >0 и ≠1, аргумент >0";
                } else {
                    $result = logarithm($num1, $num2);
                }
                break;
            case 'derivative':
                if (!is_numeric($num2) || floor($num2) != $num2) {
                    $error = "Ошибка: Степень должна быть целым числом";
                } else {
                    $result = derivative($num1, (int)$num2);
                }
                break;
            default:
                $error = "Ошибка: Выберите операцию";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Результат</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            padding: 20px;
        }
        .result-box {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }
        .success {
            background: #d4edda;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            font-size: 28px;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            color: #721c24;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="result-box">
        <h1>Результат</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (isset($result)): ?>
            <div class="success"><?php echo htmlspecialchars($result); ?></div>
        <?php endif; ?>
        
        <a href="index.php" class="back-button">← Назад</a>
    </div>
</body>
</html>
