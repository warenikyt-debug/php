<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Получаем все фото с актуальными views и likes
$images = $pdo->query("SELECT * FROM images ORDER BY upload_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Фотогалерея</title>
    <style>
        body{font-family:Arial;background:#e6e6e6;padding:20px;}
        .container{max-width:1200px;margin:0 auto;}
        h1{text-align:center;}
        .nav{text-align:center;margin:20px;}
        .nav a{display:inline-block;padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:5px;margin:0 10px;}
        .gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;padding:20px;}
        .gallery-item{background:white;border-radius:10px;overflow:hidden;box-shadow:0 2px 5px rgba(0,0,0,0.2);}
        .gallery-item img{width:100%;height:150px;object-fit:cover;cursor:pointer;}
        .stats{display:flex;justify-content:space-between;padding:10px;background:#f9f9f9;border-top:1px solid #ddd;}
        .views,.likes{display:flex;align-items:center;gap:5px;font-weight:bold;}
        .likes{color:#e74c3c;cursor:pointer;}
        .likes:hover{transform:scale(1.1);}
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:1000;}
        .modal-content{max-width:90%;max-height:80%;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}
        .close{position:absolute;top:20px;right:30px;color:white;font-size:40px;cursor:pointer;}
        .empty{text-align:center;padding:50px;background:white;border-radius:10px;}
    </style>
</head>
<body>
    <div class="container">
        <h1>🖼️ Фотогалерея</h1>
        <div class="nav">
            <a href="index.php">← Главная</a>
            <a href="upload.php">📤 Загрузить фото</a>
        </div>

        <?php if (empty($images)): ?>
            <div class="empty">Нет фотографий. <a href="upload.php">Загрузите первую</a></div>
        <?php else: ?>
            <div class="gallery">
                <?php foreach ($images as $img): ?>
                    <div class="gallery-item" data-id="<?= $img['id'] ?>">
                        <img src="<?= $img['thumbnail_path'] ?>" onclick="openModal('<?= $img['filepath'] ?>', <?= $img['id'] ?>, this)">
                        <div class="stats">
                            <span class="views">👁️ <span id="views-<?= $img['id'] ?>"><?= $img['views'] ?></span></span>
                            <span class="likes" onclick="likePhoto(<?= $img['id'] ?>)">❤️ <span id="likes-<?= $img['id'] ?>"><?= $img['likes'] ?></span></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="myModal" class="modal" onclick="closeModal(event)">
        <span class="close" onclick="closeModal(event)">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        function openModal(src, id, imgElement) {
            document.getElementById('modalImage').src = src;
            document.getElementById('myModal').style.display = 'block';

            // Увеличиваем просмотры
            fetch('update.php?action=view&id=' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('views-' + id).innerText = data.views;
                    }
                });
        }

        function likePhoto(id) {
            fetch('update.php?action=like&id=' + id)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('likes-' + id).innerText = data.likes;
                    }
                });
        }

        function closeModal(event) {
            if (event.target === document.getElementById('myModal') || event.target.className === 'close') {
                document.getElementById('myModal').style.display = 'none';
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.getElementById('myModal').style.display = 'none';
        });
    </script>
</body>
</html>
