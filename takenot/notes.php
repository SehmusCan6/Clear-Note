<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require '../connection/config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/loginregister.php");
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $notes = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📝 Notes - ClearNotes</title>
    <style>
        body {
            background: #121212;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            color: #90caf9;
            margin-bottom: 20px;
            font-size: 32px;
            font-weight: 600;
        }

        .note-container {
            background: #1e1e1e;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.6);
            width: 100%;
            max-width: 720px;
            transition: 0.3s ease;
        }

        .note-container:hover {
            box-shadow: 0 0 40px rgba(100, 181, 246, 0.2);
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 100%;
            max-width: 400px;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            background: #2a2a2a;
            color: white;
            font-size: 16px;
            transition: 0.2s ease;
            display: block;
            margin: 0 auto;
        }

        .search-bar input:focus {
            outline: 2px solid #64b5f6;
        }

        input, textarea {
            width: 95%;
            padding: 12px 16px;
            margin-bottom: 15px;
            border: none;
            border-radius: 10px;
            background: #2a2a2a;
            color: white;
            font-size: 16px;
            resize: none;
            transition: 0.2s ease;
        }

        input:focus, textarea:focus {
            outline: 2px solid #64b5f6;
            background: #333;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #64b5f6;
            color: #121212;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 10px;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(100, 181, 246, 0.25);
        }

        button:hover {
            background: #90caf9;
            box-shadow: 0 6px 16px rgba(100, 181, 246, 0.4);
        }

        .note-list {
            margin-top: 30px;
            width: 100%;
            max-width: 720px;
        }

        .note {
            background: #2a2a2a;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: 0.25s ease;
        }

        .note:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(100, 181, 246, 0.2);
        }

        .note h3 {
            margin-top: 0;
            color: #90caf9;
            font-size: 20px;
        }

        .note p {
            margin: 10px 0 0 0;
            line-height: 1.5;
        }

        .note small {
            display: block;
            margin-top: 10px;
            font-size: 12px;
            color: #bbbbbb;
        }

        .note .icons {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
        }

        .note .icons span {
            cursor: pointer;
            font-size: 20px;
            opacity: 0.7;
            transition: 0.2s;
        }

        .note .icons span:hover {
            opacity: 1;
            transform: scale(1.2);
        }

        .note.favorited {
            border: 2px solid #ffee58;
            box-shadow: 0 0 15px rgba(255, 238, 88, 0.3);
        }

        .note.flagged {
            border-left: 5px solid #ef5350;
            box-shadow: 0 0 10px rgba(239, 83, 80, 0.2);
        }

        .custom-btn {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            background-color: transparent;
            border: 2px solid #90caf9;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .custom-btn:hover {
            background-color: #90caf9;
            color: #121212;
            box-shadow: 0 0 12px rgba(144, 202, 249, 0.5);
        }


    </style>
</head>
<body>

<h1>📝 Your Notes</h1>

<div class="note-container">
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search notes...">
    </div>

    <form id="noteForm" method="POST"  action="savenotes.php">
        <input type="text" name="title" id="noteTitle" placeholder="Note Title" required>
        <textarea name="content" id="noteContent" placeholder="Write your note..." rows="4" required></textarea>
        <button type="submit">➕ Add Note</button>
    </form>

</div>


<div style="margin: 20px 0;">
    <label for="templateSelector" style="color: #90caf9;">📚 Select a Note Template:</label>
    <select id="templateSelector" style="padding: 10px; margin: 10px 0; width: 100%; max-width: 400px;">
        <option disabled selected>Select a template...</option>
    </select>
    <button type="button" onclick="fillFromTemplate()" style="margin-top: 10px;">📝 Fill Note Form</button>
</div>


<script>
    let templates = [];

    fetch("../takenot/not_templates.php")
        .then(res => res.json())
        .then(data => {
            templates = data;
            const selector = document.getElementById("templateSelector");
            data.forEach((tpl, index) => {
                const option = document.createElement("option");
                option.value = index;
                option.textContent = tpl.title + " — " + tpl.content.slice(0, 30) + "...";
                selector.appendChild(option);
            });
        });

    function fillFromTemplate() {
        const idx = document.getElementById("templateSelector").value;
        if (templates[idx]) {
            document.getElementById("noteTitle").value = templates[idx].title;
            document.getElementById("noteContent").value = templates[idx].content;
        }
    }
</script>

<div class="note-list" id="noteList">
    <?php if (!empty($notes)): ?>
        <?php foreach ($notes as $note): ?>
            <div class="note" data-note-id="<?= $note['id'] ?>">
            <div class="icons">
    <span class="star" onclick="toggleFavorite(this)">⭐</span>
    <span class="flag" onclick="toggleFlag(this)">🚩</span>
    <a href="../takenot/generate-pdf.php?id=<?= $note['id'] ?>" target="_blank" title="Download PDF">📄</a>
</div>

                <h3><?= htmlspecialchars($note['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($note['content'])) ?></p>
                <small><?= $note['created_at'] ?></small>
                <button class="edit-btn">✏️ Edit</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: gray;">You don't have any notes yet.</p>
    <?php endif; ?>
</div>

<div id="editSidebar" style="display: none; position: fixed; top: 0; right: 0; width: 300px; height: 100%; background: #1f1f1f; padding: 20px; box-shadow: -3px 0 15px rgba(0,0,0,0.5); z-index: 999;">
    <h3 style="color:#90caf9 ;">📝 Edit Note</h3>
    <input type="text" id="editTitle" placeholder="Note Title">
    <textarea id="editContent" rows="5" placeholder="Note Content"></textarea>
    <input type="hidden" id="editNoteId">
    <button onclick="saveEdit()">💾 Save</button>
    <button onclick="deleteNote()" style="background: #ff4d4d; margin-top: 10px;">🗑️ Delete</button>
    <button onclick="closeEdit()" style="background: gray; margin-top: 10px;">✖ Close</button>
</div>


<a href="../index.php" class="custom-btn">🏠 Home Page</a>



<script>


    function toggleFavorite(icon) {
        const note = icon.closest('.note');
        note.classList.toggle('favorited');
    }

    function toggleFlag(icon) {
        const note = icon.closest('.note');
        note.classList.toggle('flagged');
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        const notes = document.querySelectorAll('.note');
        notes.forEach(note => {
            const text = note.innerText.toLowerCase();
            note.style.display = text.includes(keyword) ? 'block' : 'none';
        });
    });
</script>
<!--<pre>--><?php //print_r($notes); ?><!--</pre>-->
<script src="../takenot/AJAX.js"></script>
<script src="../takenot/EditMode.js" ></script>
</body>
</html>
