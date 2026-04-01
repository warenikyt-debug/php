<?php
// ЭТОТ ФАЙЛ ОБРАБАТЫВАЕТ POST-ЗАПРОС И ПЕРЕНАПРАВЛЯЕТ НА GET

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = isset($_POST['url']) ? $_POST['url'] : '';
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';
    
    if ($url && $query) {
        // Перенаправляем на search.php с GET-параметрами
        header("Location: search.php?url=" . urlencode($url) . "&query=" . urlencode($query));
        exit;
    }
}

// Если что-то пошло не так
header("Location: index.php");
exit;
?>
