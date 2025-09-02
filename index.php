
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_SESSION['register_error'])) {
    echo "<pre style='color:red; font-weight:bold;'>🛑 REGISTER ERROR: " . $_SESSION['register_error'] . "</pre>";
    unset($_SESSION['register_error']);
}


?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLEAR - NOTES</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style/css.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">

        <a class="navbar-brand" href="#">Clear Notes</a>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="mainNavbar">

            <!-- Sol Menü -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link btn-hover" href="#choice-container">Notes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-hover" href="#choice-container">Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-hover" href="#contact-us">Contact Us</a>
                </li>
            </ul>


            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['email'])): ?>
                    <li class="nav-item d-flex align-items-center text-white me-3">
                        👋 <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>
                        (<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>)
                    </li>
                    <li class="nav-item">
                        <button id="accountBtn" class="btn btn-outline-light me-2">
                            👤 <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </button>

                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="./login/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="./login/loginregister.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light" href="./login/loginregister.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>


<?php if (isset($_GET['mail']) && $_GET['mail'] === 'successMAIL'): ?>
<?php endif; ?>

<?php if (isset($_GET['login']) && $_GET['login'] === 'success'): ?>
    <div class="alert alert-success text-center mt-3">
        Login successful! Welcome  <?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?> 🎉
    </div>
<?php endif; ?>

<li class="nav-item dropdown position-relative ">
    <div id="accountBox" class="account-box d-none">
        <h5 class="mb-2">👤 Account Details</h5>
        <p><strong>ID:</strong> <?php echo $_SESSION['user_id']??'';  ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']??''); ?></p>
        <p><strong>Surname:</strong> <?php echo htmlspecialchars($_SESSION['surname']??''); ?></p>
        <p><strong>User Name:</strong> <?php echo htmlspecialchars($_SESSION['username']??''); ?></p>
        <p><strong>E-mail:</strong> <?php echo htmlspecialchars($_SESSION['email']??''); ?></p>
        <hr class="text-secondary">
        <a href="./Account/account.php" class="btn btn-sm btn-outline-secondary w-100 mb-2">Settings</a>
        <a href="./login/logout.php" class="btn btn-sm btn-danger w-100">Log Out</a>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("accountBtn");
            const box = document.getElementById("accountBox");

            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                box.classList.toggle("d-none");
            });

            document.addEventListener("click", function (e) {
                if (!box.contains(e.target) && e.target !== btn) {
                    box.classList.add("d-none");
                }
            });
        });
    </script>
</li>


<!-- Welcome Section -->
<section class="section-container" id="description">
    <h2 id="colorful-title">Welcome to ClearNotes</h2>
    <div style="display: flex; align-items: center; gap: 15px;">
        <p>
            Say goodbye to cluttered thoughts and scattered notebooks.
            <strong>ClearNotes</strong> is your all-in-one digital companion for capturing ideas, managing tasks, and staying organized — effortlessly.
            <br><br>
            Whether you're a student, a professional, or a creative mind, ClearNotes adapts to your workflow with precision, speed, and style.
            <br><br>
            <em>Start organizing your mind — one note at a time.</em>
        </p>
        <img src="asset/picture1-removebg-preview.png" alt="ClearNotes Icon" style="width: 45%; height: auto;">
    </div>
</section>


<!-- About Section -->
<section class="section-about">
    <h2>About ClearNotes</h2>
    <p>
        Built for simplicity, optimized for productivity — <strong>ClearNotes</strong> redefines the way you take and manage notes.
        <br><br>
        Featuring real-time synchronization, seamless cloud access, and a distraction-free interface, ClearNotes empowers you to focus on what matters most: your thoughts.
        <br><br>
        With smart tagging, intuitive organization, and collaborative features, it's more than a note-taking app — it's a clarity tool for modern minds.
    </p>
</section>


<div class="choice-container" id="choice-container">
    <h2>Make Your Choice</h2>
    <div class="circle-container">

        <div class="circle note-bg">
            <a href="takenot/notes.php">Take Note</a>
        </div>
        
        <div class="circle pdftoword-bg">
            <a href="convert/maincon.php">Convert File</a>
        </div>

        <div class="circle plan-bg">
            <a href="taketask/task.php">Take Plan</a>
        </div>

    </div>
</div>


<!-- Chatbot Butonu -->
<div class="chatbot-button" onclick="toggleChatbot()">💬</div>

<!-- Chatbot Penceresi -->
<div id="chatbot" class="chatbot-container" style="display:none;">
    <div class="chatbot-header">
        <h3>ClearNotes Chatbot</h3>
        <span class="close-btn" onclick="toggleChatbot()">&times;</span>
    </div>
    <div id="chatbot-messages" class="chatbot-messages"></div>
    <div class="chatbot-options" id="chatbot-options">
        <button onclick="sendChat('What is Clear Notes')">What is Clear Notes, and how does it work?</button>
        <button onclick="sendChat('Which devices can I use it on?')">Which devices can I use it on?</button>
        <button onclick="sendChat('Is the app free, or does it have a paid version?')">Is the app free, or does it have a paid version?</button>
        <button onclick="sendChat('Can I organize my notes into categories?')">Can I organize my notes into categories?</button>
        <button onclick="sendChat('How can I contact customer support?')">How can I contact customer support?</button>
    </div>
</div>


<!-- Contact Form -->
<div class="container-contact" id="contact-us" >
    <h2>Contact Form</h2>
    <form action="./contact_part/contact_mail.php" method="POST">

        <label for="name">Name:</label>
        <input
                type="text"
                id="name"
                name="name"
                value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>"
            <?php echo isset($_SESSION['name']) ? 'readonly' : 'required'; ?>
        >

        <label for="email">Email:</label>
        <input
                type="email"
                id="email"
                name="email"
                value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>"
            <?php echo isset($_SESSION['email']) ? 'readonly' : 'required'; ?>
        >

        <label for="about">About You:</label>
        <textarea id="about" name="about" rows="4" required></textarea>

        <button type="submit" class="send">Submit</button>
    </form>
</div>


<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h2>ClearNotes</h2>
            <p>Your best note-taking companion.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#" style="text-decoration: none">Home Page</a></li>
                <li><a href="#" style="text-decoration: none">About Us</a></li>
                <li><a href="#" style="text-decoration: none">Take Notes</a></li>
                <li><a href="#" style="text-decoration: none">Plan</a></li>
                <a href="./admin/loginadmin.php">Admin Panel</a>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram"></a>
            </div>
        </div>
    </div>
    <p class="footer-bottom">© 2025 ClearNotes. All rights reserved. </p>
</footer>

<div id="cookieBanner" class="cookie-banner">
    <p>Our website uses cookies to provide you with a better experience. You must accept cookies before continuing. <a href="cookies-details.html" target="_blank">More Info</a>
    </p>
    <button id="acceptCookies">Accept</button>
    <button id="rejectCookies">Cancel</button>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    let cookieBanner = document.getElementById("cookieBanner");
    let acceptBtn = document.getElementById("acceptCookies");
    let rejectBtn = document.getElementById("rejectCookies");

    // Çerezi oku
    function getCookie(name) {
        const value = "; " + document.cookie;
        const parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    }

    const cookieStatus = getCookie("cookiesAccepted");

    if (cookieStatus === "true") {
        cookieBanner.style.display = "none"; 
    } else {
        cookieBanner.style.display = "block"; 
    }

    acceptBtn.addEventListener("click", function () {
        document.cookie = "cookiesAccepted=true; path=/; max-age=" + 60 * 60 * 24 * 30; 
        cookieBanner.style.display = "none";
    });

    rejectBtn.addEventListener("click", function () {
        cookieBanner.style.display = "none";
    });
});

</script>

<script>
    const title = document.getElementById("colorful-title");

    const colorPalette = [
        { color: "#fca311", shadow: "#fca311" },
        { color: "#ff595e", shadow: "#ff6b6b" },
        { color: "#1982c4", shadow: "#3a86ff" },
        { color: "#8ac926", shadow: "#baff29" },
        { color: "#6a4c93", shadow: "#9d4edd" },
        { color: "#ffca3a", shadow: "#ffe066" }
    ];

    let i = 0;

    setInterval(() => {
        const current = colorPalette[i];
        title.style.color = current.color;
        title.style.textShadow = `0 0 15px ${current.shadow}, 0 0 30px ${current.shadow}`;
        i = (i + 1) % colorPalette.length;
    }, 1000);
</script>

<script>
    document.querySelectorAll('.navbar-collapse .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            const navbarCollapse = document.querySelector('.navbar-collapse');
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        });
    });
</script>




<script src="JavaScriptCode/chatbot.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

