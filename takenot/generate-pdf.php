<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../vendor/dompdf/autoload.inc.php';
require '../connection/config.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid note ID.");
}

$noteId = $_GET['id'];

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

// Notu al
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = :id AND user_id = :user_id");
$stmt->execute([
    ":id" => $noteId,
    ":user_id" => $_SESSION['user_id']
]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    die("Note not found.");
}

// PDF ayarları
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// HTML içeriği
// HTML içeriği (şık stillerle)
$html = "
    <html>
    <head>
        <style>
            body {
                font-family: 'Helvetica', sans-serif;
                padding: 40px;
                color: #333;
            }
            h1 {
                text-align: center;
                font-size: 28px;
                color: #4A90E2;
                margin-bottom: 30px;
            }
            .content {
                font-size: 14px;
                line-height: 1.6;
                text-align: justify;
            }
            .footer {
                margin-top: 50px;
                font-size: 10px;
                text-align: right;
                color: #777;
            }
        </style>
    </head>
    <body>
        <h1>" . htmlspecialchars($note['title']) . "</h1>
        <div class='content'>" . nl2br(htmlspecialchars($note['content'])) . "</div>
        <div class='footer'>Created at: {$note['created_at']}</div>
    </body>
    </html>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// PDF çıktısı
$filename = preg_replace('/[^a-z0-9]/i', '_', $note['title']) . '.pdf';
$dompdf->stream($filename, ["Attachment" => true]);
