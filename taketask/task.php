<?php
require '../connection/config.php';
session_start();

$user_id = $_SESSION["user_id"] ?? null;

$plans = [];

if ($user_id) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY date ASC");
    $stmt->execute([$user_id]);
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📅 ClearPlans</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        h1 {
            color: #90caf9;
            margin-bottom: 20px;
            font-size: 32px;
            font-weight: 600;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .calendar-header button {
            background: #2c2c2c;
            color: #fff;
            border: 1px solid #444;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        .calendar-header button:hover {
            background-color: #64b5f6;
            color: #121212;
            box-shadow: 0 0 10px rgba(100, 181, 246, 0.3);
        }

        .calendar-header span {
            font-size: 20px;
            font-weight: bold;
            color: #bbdefb;
        }

        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            max-width: 700px;
            width: 100%;
        }

        .day {
            background: #1f1f1f;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .day:hover {
            background: #2c2c2c;
            border-color: #64b5f6;
            box-shadow: 0 0 10px rgba(100, 181, 246, 0.25);
            transform: translateY(-2px);
        }

        .day.disabled {
            background: #2a2a2a;
            color: #777;
            cursor: not-allowed;
        }

        .day.selected {
            border: 2px solid #90caf9;
            background: #1e1e1e;
        }

        .day.has-plan {
            background: #ef5350;
            color: white;
            box-shadow: 0 0 10px rgba(239, 83, 80, 0.3);
        }

        /* Sidebar (Plan Panel) */
        #planPanel {
            position: fixed;
            top: 0;
            right: -400px;
            width: 300px;
            height: 100%;
            background: #1f1f1f;
            box-shadow: -4px 0 10px rgba(0,0,0,0.5);
            padding: 20px;
            transition: right 0.4s ease;
            z-index: 999;
        }

        #planPanel.active {
            right: 0;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            margin-bottom: 12px;
            background: #2a2a2a;
            color: white;
            font-size: 14px;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus, textarea:focus {
            outline: none;
            border: 1px solid #64b5f6;
            box-shadow: 0 0 8px rgba(100, 181, 246, 0.4);
        }

        button {
            background: #64b5f6;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            color: #121212;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(100, 181, 246, 0.2);
        }

        button:hover {
            background: #90caf9;
            box-shadow: 0 6px 16px rgba(144, 202, 249, 0.3);
        }


        .plan-list {
            margin-top: 30px;
            max-width: 700px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .plan-box {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #64b5f6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.4);
            position: relative;
            transition: 0.2s ease;
        }

        .plan-box h4 {
            margin: 0 0 8px 0;
            color: #90caf9;
        }

        .plan-box p {
            margin: 5px 0;
            color: #ccc;
        }

        .plan-box.favorited {
            border: 2px solid #ffee58;
            box-shadow: 0 0 15px rgba(255, 238, 88, 0.3);
        }

        .plan-box.flagged {
            border-left: 5px solid #ef5350;
            box-shadow: 0 0 10px rgba(239, 83, 80, 0.2);
        }

        .icons {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 8px;
            font-size: 18px;
            cursor: pointer;
        }

        .star,
        .flag {
            transition: transform 0.2s ease, color 0.3s ease;
            color: #aaa;
        }

        .star:hover,
        .flag:hover {
            transform: scale(1.2);
            color: #64b5f6;
        }

        /* Search Bar */
        #searchInput {
            width: 100%;
            max-width: 700px;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid #333;
            font-size: 16px;
            background: #1f1f1f;
            color: white;
            outline: none;
            transition: 0.3s ease;
            box-shadow: 0 0 6px rgba(100, 181, 246, 0.2);
        }

        #searchInput:focus {
            border-color: #64b5f6;
            box-shadow: 0 0 10px rgba(100, 181, 246, 0.5);
        }

        #searchInput::placeholder {
            color: #aaa;
        }


        .custom-btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            color: #ffffff;
            background-color: transparent;
            border: 2px solid #64b5f6;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .custom-btn:hover {
            background-color: #64b5f6;
            color: #121212;
            box-shadow: 0 0 10px rgba(100, 181, 246, 0.5);
        }


    </style>
</head>
<body>
<h1>📅 Plan Your Month</h1>

<div class="calendar-header">
    <button onclick="changeMonth(-1)">⬅️</button>
    <span id="monthLabel">Month Year</span>
    <button onclick="changeMonth(1)">➡️</button>
</div>

<div class="calendar" id="calendar"></div>

<div style="margin: 30px 0; width: 100%; display: flex; justify-content: center;">
    <input type="text" id="searchInput" placeholder="🔍 Search plans...">
</div>
<div id="planPanel">
    <h3>📝 New Plan</h3>
    <p id="selectedDate" style="font-size: 14px; color: #aaa;"></p>
    <form id="planForm"  method="POST"  >
        <input type="hidden" name="date" id="formDate">
        <input type="text" name="title" placeholder="Title" required>
        <input type="text" name="subject" placeholder="Subject">
        <textarea name="description" placeholder="Description" rows="5"></textarea>
        <input type="hidden" name="plan_id" id="planId">
        <button type="submit">Save Plan</button>
        <button id="updateBtn" type="submit" style="background: #4caf50; margin-top: 5px;">
            🔁 Update Plan
        </button>
        <button id="deleteBtn" onclick="handleDeleteNote()" style="background: #ff4d4d; margin-top: 10px;">🗑️ Delete</button>
        <button style="background: gray;" type="button" onclick="planPanel.classList.remove('active')">Cancel</button>
        <button id="remindBtn" type="button" style="background: #2196f3; margin-top: 10px; width: 100%;">
            ⏰ Reminder( Only E-mail)
        </button>
        <p id="formSaveMsg" style="display:none; color:lightgreen; margin-top:10px; text-align:center;">✅ Plan saved successfully!</p>
    </form>

</div>

<div id="planList" class="plan-list">
    <?php foreach ($plans as $plan): ?>
        <div class="plan-box" data-id="<?= $plan['id'] ?>" data-title="<?= htmlspecialchars($plan['title']) ?>" data-subject="<?= htmlspecialchars($plan['subject']) ?>" data-description="<?= htmlspecialchars($plan['description']) ?>" data-date="<?= $plan['date'] ?>">

            <div class="icons">
                <span class="star" onclick="toggleFavorite(this)">⭐</span>
                <span class="flag" onclick="toggleFlag(this)">🚩</span>
            </div>

            <h4><?= htmlspecialchars($plan['title']) ?></h4>
            <?php if (!empty($plan['subject'])): ?>
                <p><strong>Subject:</strong> <?= htmlspecialchars($plan['subject']) ?></p>
            <?php endif; ?>
            <?php if (!empty($plan['description'])): ?>
                <p><?= nl2br(htmlspecialchars($plan['description'])) ?></p>
            <?php endif; ?>
            <p style="font-size: 12px; color: #999;">📅 <?= htmlspecialchars($plan['date']) ?></p>

            <button class="edit-btn" style="margin-top: 10px;">🖊️ Edit</button>
        </div>
    <?php endforeach; ?>
</div>



<a href="../index.php" class="custom-btn">🏠 Home Page</a>
<script>


    document.getElementById("remindBtn").addEventListener("click", function () {
        const form = document.getElementById("planForm");
        const formData = new FormData(form);

        fetch("task_mail.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(result => {
                alert(result); // Geri bildirim göster
            })
            .catch(error => {
                alert("Hata oluştu: " + error);
            });
    });
    function toggleFavorite(el) {
        el.classList.toggle("active");
        const note = el.closest(".plan-box");
        if (note) {
            note.classList.toggle("favorited");
        }
    }

    function toggleFlag(el) {
        el.classList.toggle("active");
        const note = el.closest(".plan-box");
        if (note) {
            note.classList.toggle("flagged");
        }
    }

    document.getElementById("searchInput").addEventListener("input", function () {
        const query = this.value.toLowerCase();
        const plans = document.querySelectorAll(".plan-box");

        plans.forEach(plan => {
            const text = plan.innerText.toLowerCase();
            if (text.includes(query)) {
                plan.style.display = "block";
            } else {
                plan.style.display = "none";
            }
        });
    });




</script>










<script src="../taketask/calander.js"></script>
<script src="../taketask/AJAXPLAN.js"></script>

</body>
</html>
