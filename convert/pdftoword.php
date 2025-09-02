<?php

spl_autoload_register(function ($class) {
    $prefix = 'Smalot\\PdfParser\\';
    $base_dir = __DIR__ . '/../vendor/pdfparser-master/src/Smalot/PdfParser/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use Smalot\PdfParser\Parser;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $file = $_FILES['pdf_file']['tmp_name'];
    $filename = $_FILES['pdf_file']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if ($ext !== 'pdf') {
        die("❌ Only PDF files are supported.");
    }

    // Dosya boyutu sınırı (2MB)
    if ($_FILES['pdf_file']['size'] > 2 * 1024 * 1024) {
        die("❌ File size must be under 2MB.");
    }

    // PDF içeriğini çözümle
    $parser = new Parser();
    try {
        $pdf = $parser->parseFile($file);
        $text = $pdf->getText();
    } catch (Exception $e) {
        die("❌ PDF could not be parsed. Error: " . $e->getMessage());
    }

    // DOC çıktısı oluştur
    $wordFile = tempnam(sys_get_temp_dir(), 'doc');
    $docContent = "<html><body><pre style='font-family:Arial,sans-serif; font-size:14px;'>" . htmlspecialchars($text) . "</pre></body></html>";
    file_put_contents($wordFile . ".doc", $docContent);

    $outputName = pathinfo($filename, PATHINFO_FILENAME) . ".doc";
    header("Content-Type: application/msword");
    header("Content-Disposition: attachment; filename=\"$outputName\"");
    readfile($wordFile . ".doc");
    unlink($wordFile . ".doc");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Convert PDF to Word</title>
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

<h1>📄 Convert PDF to Word</h1>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf_file" accept=".pdf" required><br>
    <button type="submit">Convert Now</button>
</form>

</body>
</html>
