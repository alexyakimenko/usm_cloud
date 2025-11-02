<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;

define('BUCKET_NAME', 'bucket-name');

$db = new PDO('sqlite:uploads.db');
$db->exec("CREATE TABLE IF NOT EXISTS files (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    original_name TEXT,
    unique_name TEXT,
    url TEXT,
    uploaded_at TEXT
)");

$s3 = new S3Client([
    'region'  => 'us-central-1',
]);

$file = $_FILES['fileToUpload']['tmp_name'];
$originalName = $_FILES['fileToUpload']['name'];

$uniqueName = time() . '_' . basename($originalName);

$destinationKey = 'avatars/' . $uniqueName;

try {
    $result = $s3->putObject([
        'Bucket'     => BUCKET_NAME,
        'Key'        => $destinationKey,
        'SourceFile' => $file,
        'ACL'        => 'public-read',
    ]);

    $url = $result['ObjectURL'];

    $stmt = $db->prepare("INSERT INTO files (original_name, unique_name, url, uploaded_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([$originalName, $uniqueName, $url, date('Y-m-d H:i:s')]);

    echo "File uploaded successfully. URL: " . $url;
} catch (Aws\Exception\AwsException $e) {
    echo "Error uploading file: " . $e->getMessage();
}
?>
