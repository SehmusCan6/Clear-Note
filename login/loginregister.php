

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .container-box {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Ortak stil */
        .login-box, .register-box {
            width: 380px;
            height: 500px;
            background: linear-gradient(135deg, #1e1e1e, #2b2b2b);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
            padding: 30px;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-box:hover, .register-box:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 28px rgba(252, 163, 17, 0.25);
        }

        h2 {
            margin-bottom: 20px;
            color: #fca311;
            font-weight: bold;
        }

        .form-group {
            width: 100%;
            margin-bottom: 16px;
            display: none;
        }

        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            background: #2c2c2c;
            color: white;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border: 1px solid #fca311;
            box-shadow: 0 0 8px rgba(252, 163, 17, 0.3);
        }

        /* Butonlar */
        .btn-toggle {
            background: #fca311;
            color: #121212;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            display: none;
            width: 100%;
            transition: background 0.3s, transform 0.2s;
        }

        .btn-toggle:hover {
            background: #ffb347;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(252, 163, 17, 0.3);
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 22px;
            color: #ccc;
            cursor: pointer;
            display: none;
            transition: color 0.2s;
        }

        .close-btn:hover {
            color: #fca311;
        }

        a {
            color: #bb86fc;
            text-decoration: none;
            font-size: 17px;
            margin-top: 12px;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #d6afff;
        }

        .visible {
            display: block !important;
        }

    </style>
</head>
<body>


<div class="container-box">
    <div class="login-box" id="loginBox">
        <h2>Login</h2>
        <a href="#" onclick="showLoginFields(); return false;" id="loginLink">Login</a>
        <button class="close-btn" onclick="hideLoginFields()">&times;</button>

        <form method="POST" action="register.php">
            <div class="form-group">
                <input type="text" name="user_input" placeholder="Username or Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="loginPassword" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="checkbox" onclick="togglePassword('loginPassword')"> Show Password
            </div>
            <button class="btn-toggle" id="loginButton" type="submit">Login</button>
            <a href="forgot-password.php" style="margin-top: 10px; display:block;">Forgot your password?</a>

        </form>

        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </div>



    <div class="register-box" id="registerBox">
        <h2>Register</h2>
        <a href="#" onclick="showRegisterFields(); return false;" id="registerLink">Register</a>
        <button class="close-btn" onclick="hideRegisterFields()">&times;</button>
        <form id="registerForm" method="POST" onsubmit="return validateRegisterForm();" action="register.php">
            <div class="form-group"><input type="text" id="name" name="name" placeholder="Name" required></div>
            <div class="form-group"><input type="text" id="surname" name="surname" placeholder="Surname" required></div>
            <div class="form-group"><input type="text" id="username" name="username" placeholder="Username" required></div>
            <div class="form-group"><input type="email" id="email" name="email" placeholder="Email" required></div>
            <div class="form-group"><input type="password" id="registerPassword" name="password" placeholder="Password" required></div>
            <div class="form-group">
                <input type="checkbox" onclick="togglePassword('registerPassword')"> Show Password
            </div>
            <button class="btn-toggle" id="registerButton" type="submit" >Register</button>
        </form>


        <?php if (isset($_SESSION['register_error'])): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Kayıt Başarısız!',
                    text: '<?= $_SESSION['register_error']; ?>',
                    confirmButtonText: 'Tamam'
                });
            </script>
            <?php unset($_SESSION['register_error']); ?>
        <?php endif; ?>

        <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Kayıt Başarılı!',
                    text: 'Lütfen e-posta adresinizi kontrol edin.',
                    confirmButtonText: 'Tamam'
                });
            </script>
        <?php endif; ?>
    </div>

</div>

<script>

        function togglePassword(id) {
        const field = document.getElementById(id);
        if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
    }



    function showLoginFields() {
        document.querySelectorAll('#loginBox .form-group').forEach(field => field.classList.add('visible'));
        document.getElementById('loginButton').style.display = 'block';
        document.getElementById('loginLink').style.display = 'none';
        document.querySelector('#loginBox .close-btn').style.display = 'block';
    }

    function hideLoginFields() {
        document.querySelectorAll('#loginBox .form-group').forEach(field => field.classList.remove('visible'));
        document.getElementById('loginButton').style.display = 'none';
        document.getElementById('loginLink').style.display = 'block';
        document.querySelector('#loginBox .close-btn').style.display = 'none';
    }

    function showRegisterFields() {
        document.querySelectorAll('#registerBox .form-group').forEach(field => field.classList.add('visible'));
        document.getElementById('registerButton').style.display = 'block';
        document.getElementById('registerLink').style.display = 'none';
        document.querySelector('#registerBox .close-btn').style.display = 'block';
    }

    function hideRegisterFields() {
        document.querySelectorAll('#registerBox .form-group').forEach(field => field.classList.remove('visible'));
        document.getElementById('registerButton').style.display = 'none';
        document.getElementById('registerLink').style.display = 'block';
        document.querySelector('#registerBox .close-btn').style.display = 'none';
    }

        function validateLoginForm() {
            const userInput = document.querySelector('input[name="user_input"]').value.trim();
            const password = document.getElementById("loginPassword").value.trim();

            if (userInput.length < 4) {
                Swal.fire("Invalid Input", "Please enter a valid username or email.", "error");
                return false;
            }

            if (password.length < 6) {
                Swal.fire("Invalid Password", "Password must be at least 6 characters long.", "error");
                return false;
            }

            return true;
        }



        function validateRegisterForm() {
            const name = document.getElementById("name").value.trim();
            const surname = document.getElementById("surname").value.trim();
            const username = document.getElementById("username").value.trim();
            const email = document.getElementById("email").value.trim();
            const password = document.getElementById("registerPassword").value;

            const namePattern = /^[A-Za-zğüşöçİĞÜŞÖÇ\s]+$/;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;

            if (!namePattern.test(name)) {
            Swal.fire("Invalid Name", "Name should only contain letters.", "error");
            return false;
        }

            if (!namePattern.test(surname)) {
            Swal.fire("Invalid Surname", "Surname should only contain letters.", "error");
            return false;
        }

            if (username.length < 4 || !/^[a-zA-Z0-9_]+$/.test(username)) {
            Swal.fire("Invalid Username", "Username must be at least 4 characters and contain only letters, numbers, or underscores.", "error");
            return false;
        }

            if (!emailPattern.test(email)) {
            Swal.fire("Invalid Email", "Please enter a valid email address.", "error");
            return false;
        }

            if (!passwordPattern.test(password)) {
            Swal.fire("Invalid Password", "Password must be at least 6 characters and include both letters and numbers.", "error");
            return false;
        }


            return true;
        }




</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_SESSION['register_error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'RegisterEror!',
            text: '<?= $_SESSION['register_error']; ?>',
            confirmButtonText: 'Tamam'
        });
    </script>
    <?php unset($_SESSION['register_error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['login_error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Fail!',
            text: '<?= $_SESSION['login_error']; ?>',
            confirmButtonText: 'Tamam'
        });
    </script>
    <?php unset($_SESSION['login_error']); ?>
<?php endif; ?>

</body>
</html>