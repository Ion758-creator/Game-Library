<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Library | Login & Register</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif; }

    body {
      background: #050816;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      width: 380px;
      background: rgba(139,92,246,0.05);
      border: 1px solid rgba(139,92,246,0.2);
      backdrop-filter: blur(12px);
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(139,92,246,0.2);
      overflow: hidden;
    }

    .form-box {
      padding: 40px;
      text-align: center;
      color: #fff;
    }

    .form-box h1 {
      font-size: 30px;
      margin-bottom: 25px;
      color: #8b5cf6;
      text-shadow: 0 0 8px rgba(139,92,246,0.6);
      transition: 0.3s;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-bottom: 25px;
    }

    .input-field {
      position: relative;
      overflow: hidden;
      max-height: 60px;
      transition: max-height 0.5s ease;
    }

    #nameField { max-height: 0; }

    .input-field input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #8b5cf6;
      background: rgba(139,92,246,0.05);
      border-radius: 10px;
      outline: none;
      color: #fff;
      font-size: 14px;
    }

    .input-field input::placeholder { color: #aaa; }

    .input-field input:focus {
      background: rgba(139,92,246,0.1);
      box-shadow: 0 0 0 3px rgba(139,92,246,0.15);
    }

    .input-group p { font-size: 13px; color: #bbb; }
    .input-group p a { color: #8b5cf6; text-decoration: none; }
    .input-group p a:hover { text-decoration: underline; }

    .btn-field {
      display: flex;
      justify-content: space-between;
    }

    .btn-field button {
      flex-basis: 48%;
      background: #8b5cf6;
      color: #fff;
      height: 45px;
      border-radius: 25px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 15px;
      box-shadow: 0 0 10px rgba(139,92,246,0.4);
      transition: 0.3s ease;
    }

    .btn-field button:hover {
      background: #7c3aed;
      box-shadow: 0 0 15px rgba(139,92,246,0.6);
    }

    .btn-field button.disable {
      background: rgba(255,255,255,0.08);
      color: #aaa;
      box-shadow: none;
    }

    .btn-field button.disable:hover {
      background: rgba(139,92,246,0.15);
      color: #fff;
    }

    .msg {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 14px;
      margin-bottom: 20px;
      text-align: left;
    }

    .msg.success {
      background: rgba(74,222,128,0.1);
      border: 1px solid #4ade80;
      color: #4ade80;
    }

    .msg.error {
      background: rgba(239,68,68,0.1);
      border: 1px solid #ef4444;
      color: #ef4444;
    }

    @media (max-width: 420px) {
      .container { width: 90%; }
      .form-box { padding: 30px; }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-box">
    <h1 id="title">Login</h1>

    <?php if(isset($_GET['success'])): ?>
      <div class="msg success">Account created! You can now login.</div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
      <div class="msg error">
        <?php
          $err = $_GET['error'];
          if ($err === 'exists')  echo "This username already exists!";
          elseif ($err === 'empty') echo "Username and password cannot be empty!";
          elseif ($err === 'login_required') echo "You must login to access the library.";
          elseif ($err === 'write') echo "Server error: could not save user.";
          else echo "Invalid username or password!";
        ?>
      </div>
    <?php endif; ?>

    <form action="process.php" method="POST" id="loginForm">
      <div class="input-group">

        <div class="input-field" id="nameField">
          <input type="text" name="fullname" placeholder="Full name" id="nameInput">
        </div>

        <div class="input-field">
          <input type="text" name="username" placeholder="Username" id="usernameInput" required>
        </div>

        <div class="input-field">
          <input type="password" name="password" placeholder="Password" id="passwordInput" required>
        </div>

        <p>Forgot your password? <a href="#">Reset here</a></p>
      </div>

      <div class="btn-field">
        <button type="submit" name="login" id="signinBtn">Login</button>
        <button type="button" class="disable" id="signupBtn">Register</button>
      </div>
    </form>
  </div>
</div>

<script>
const signinBtn = document.getElementById("signinBtn");
const signupBtn = document.getElementById("signupBtn");
const nameField = document.getElementById("nameField");
const title     = document.getElementById("title");
const form      = document.getElementById("loginForm");

signupBtn.onclick = function() {
  nameField.style.maxHeight = "60px";
  title.textContent = "Register";
  signupBtn.classList.remove("disable");
  signinBtn.classList.add("disable");
  // Switch button to submit register
  signinBtn.removeAttribute("name");
  signupBtn.setAttribute("type", "submit");
  signupBtn.setAttribute("name", "register");
};

signinBtn.onclick = function() {
  if (title.textContent === "Register") {
    // Back to login view
    nameField.style.maxHeight = "0";
    title.textContent = "Login";
    signupBtn.classList.add("disable");
    signinBtn.classList.remove("disable");
    signupBtn.setAttribute("type", "button");
    signupBtn.removeAttribute("name");
    signinBtn.setAttribute("name", "login");
  }
  // else: normal form submit (login)
};
</script>

</body>
</html>
