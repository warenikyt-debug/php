<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

$message = '';
$error = '';

if (!file_exists('uploads')) mkdir('uploads', 0777, true);
if (!file_exists('thumbs')) mkdir('thumbs', 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes)) {
            
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;
            $uploadPath = 'uploads/' . $filename;
            $thumbPath = 'thumbs/' . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                list($width, $height) = getimagesize($uploadPath);
                copy($uploadPath, $thumbPath);
                
                $stmt = $pdo->prepare("INSERT INTO images (filename, original_name, filepath, thumbnail_path, filesize, width, height, views, likes) VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0)");
                
                if ($stmt->execute([$filename, $file['name'], $uploadPath, $thumbPath, $file['size'], $width, $height])) {
                    $message = "✅ Файл успешно загружен!";
                } else {
                    $error = "❌ Ошибка при сохранении в базу данных";
                }
            } else {
                $error = "❌ Ошибка при сохранении файла";
            }
        } else {
            $error = "❌ Разрешены только JPG, PNG и GIF файлы";
        }
    } else {
        $error = "❌ Ошибка загрузки файла";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Загрузка фото</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .nav {
            text-align: center;
            margin: 20px 0;
        }
        .nav a {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }
        .upload-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #999;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📤 Загрузка фотографий</h1>
        
        <div class="nav">
            <a href="index.php">← Главная</a>
            <a href="gallery.php">🖼️ Галерея</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="upload-form">
            <form method="POST" enctype="multipart/form-data">
                <label>Выберите изображение:</label>
                <input type="file" name="image" accept="image/*" required>
                <button type="submit">Загрузить</button>
            </form>
        </div>
    </div>
</body>
</html>
