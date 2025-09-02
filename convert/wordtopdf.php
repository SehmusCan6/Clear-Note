<?php
require_once '../vendor/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

function extractDocxText($filePath) {
    $zip = new ZipArchive;
    if ($zip->open($filePath) === true) {
        if (($xml = $zip->getFromName('word/document.xml')) !== false) {
            $zip->close();
            $xml = preg_replace('/<w:.*?>/', '', $xml);
            $xml = strip_tags($xml, "<p>");
            $xml = str_replace(['<p>', '</p>'], ["", "\n"], $xml);
            return $xml;
        } else {
            return "⚠️ This DOCX file is missing 'word/document.xml'. It might be corrupted or invalid.";
        }
    } else {
        return "⚠️ This file is not a valid DOCX ZIP archive.";
    }
}

/**
 * DOC dosyasından tahmini düz metin çıkar (HTML kalıntıları temizlenmiş)
 */
function extractDocText($filePath) {
    $content = file_get_contents($filePath);
    $text = preg_replace("/[^(\x20-\x7F)]*/", '', $content); // Binary temizliği
    $text = substr($text, strpos($text, "text") ?: 0); // Başlangıcı tahmin et
    $text = preg_replace('/<[^>]*>/', '', $text); // HTML benzeri yapıları sil
    return wordwrap(trim($text), 120);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['word_file'])) {
    $file = $_FILES['word_file']['tmp_name'];
    $filename = $_FILES['word_file']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, ['doc', 'docx', 'txt'])) {
        die("❌ Only .doc, .docx or .txt files are supported.");
    }

    if ($_FILES['word_file']['size'] > 5 * 1024 * 1024) {
        die("❌ File size must be under 5MB.");
    }

    if ($ext === 'docx') {
        $content = extractDocxText($file);
        if (str_starts_with($content, "⚠️")) {
            die($content);
        }
    } elseif ($ext === 'txt') {
        $content = file_get_contents($file);
    } elseif ($ext === 'doc') {
        $content = extractDocText($file);
    }

    // PDF oluştur
    $html = "<h2 style='color:#444;'>Converted Document</h2><div style='white-space: pre-wrap; font-family:Arial, sans-serif; font-size:14px;'>"
          . htmlspecialchars($content)
          . "</div>";

    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();

    $outName = pathinfo($filename, PATHINFO_FILENAME) . ".pdf";
    $pdf->stream($outName, ["Attachment" => true]);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Convert Word to PDF</title>
    <style>
        body {
            background-color: #121212;
            font-family: 'Segoe UI', sans-serif;
            color: white;
            text-align: center;
            padding-top: 60px;
        }

        form {
            background: #1e1e1e;
            display: inline-block;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(144, 202, 249, 0.2);
        }

        input, button {
            margin: 12px 0;
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
        }

        input[type="file"] {
            background: #2a2a2a;
            color: white;
        }

        button {
            background: #90caf9;
            color: #121212;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #64b5f6;
        }
    </style>
</head>
<body>

<h1>📄 Convert Word to PDF</h1>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="word_file" accept=".doc,.docx,.txt" required><br>
    <button type="submit">Convert Now</button>
</form>

</body>
</html>
