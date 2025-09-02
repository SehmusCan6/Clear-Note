<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document Converter</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1000px;
            background: #1e1e1e;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(144, 202, 249, 0.3);
            overflow: hidden;
        }

        .panel {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid #2a2a2a;
        }

        .panel:last-child {
            border-right: none;
        }

        h2 {
            color: #90caf9;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        input[type="file"] {
            background-color: #2a2a2a;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
        }

        button {
            padding: 12px 20px;
            background-color: #90caf9;
            color: #121212;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #64b5f6;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="panel">
        <h2>📄 Word to PDF</h2>
        <form action="../convert/wordtopdf.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="word_file" accept=".doc,.docx,.txt" required>
            <button type="submit">Convert</button>
        </form>
    </div>


    <div class="panel">
        <h2>📄 PDF to Word</h2>
        <form action="../convert/pdftoword.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="pdf_file" accept=".pdf" required>
            <button type="submit">Convert</button>
        </form>
    </div>
</div>

</body>
</html>
