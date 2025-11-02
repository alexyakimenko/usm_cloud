<?php
$db = new PDO('sqlite:uploads.db');

$stmt = $db->query("SELECT * FROM files ORDER BY uploaded_at DESC");
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Uploaded Files</h1>
<ul>
<?php foreach ($files as $file): ?>
    <li style="margin-bottom: 20px;">
        <strong>Original:</strong> <?= htmlspecialchars($file['original_name']) ?><br>
        <strong>Uploaded at:</strong> <?= $file['uploaded_at'] ?><br>
        <strong>URL:</strong> <a href="<?= htmlspecialchars($file['url']) ?>" target="_blank"><?= htmlspecialchars($file['url']) ?></a><br>
        <img src="<?= htmlspecialchars($file['url']) ?>" alt="<?= htmlspecialchars($file['original_name']) ?>" style="max-width:200px; max-height:200px; margin-top:5px;">
    </li>
<?php endforeach; ?>
</ul>
