<!DOCTYPE html>
<html lang="ro" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Library | Reset Password</title>

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
      font-size: 28px;
      margin-bottom: 10px;
      color: #8b5cf6;
      text-shadow: 0 0 8px rgba(139,92,246,0.6);
    }

    .form-box p.subtitle {
      font-size: 14px;
      color: #bbb;
      margin-bottom: 25px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-bottom: 25px;
    }

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

    .btn-reset {
      width: 100%;
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
      margin-bottom: 15px;
    }

    .btn-reset:hover {
      background: #7c3aed;
      box-shadow: 0 0 15px rgba(139,92,246,0.6);
    }

    .back-link {
      font-size: 13px;
      color: #bbb;
    }

    .back-link a {
      color: #8b5cf6;
      text-decoration: none;
    }

    .back-link a:hover { text-decoration: underline; }

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

    @media (max-width:480px) { .container { width:95vw; } }
  </style>
</head>
<body>

<div class="container">
  <div class="form-box">
    <h1>Reset Password</h1>
    <p class="subtitle">Enter your username and a new password below.</p>

    <?php
    $file = __DIR__ . "/../data/users.json";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username    = trim($_POST['username'] ?? '');
      $newPassword = trim($_POST['new_password'] ?? '');
      $confirm     = trim($_POST['confirm_password'] ?? '');

      if (empty($username) || empty($newPassword) || empty($confirm)) {
        $error = "All fields are required.";
      } elseif ($newPassword !== $confirm) {
        $error = "Passwords do not match.";
      } elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters.";
      } else {
        $users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($users)) $users = [];

        $found = false;
        foreach ($users as &$user) {
          if ($user['username'] === $username) {
            $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $found = true;
            break;
          }
        }
        unset($user);

        if (!$found) {
          $error = "Username not found.";
        } else {
          file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
          $success = "Password updated! You can now login.";
        }
      }
    }
    ?>

    <?php if (!empty($success)): ?>
      <div class="msg success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="msg error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($success)): ?>
    <form method="POST">
      <div class="input-group">
        <div class="input-field">
          <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-field">
          <input type="password" name="new_password" placeholder="New Password" required>
        </div>
        <div class="input-field">
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        </div>
      </div>
      <button type="submit" class="btn-reset">Reset Password</button>
    </form>
    <?php endif; ?>

    <p class="back-link">Remember your password? <a href="login.php">Back to Login</a></p>
  </div>
</div>

<script>
const _s = localStorage.getItem("gamelib_settings");
if (_s) { try { const p=JSON.parse(_s); if(p.theme) document.documentElement.setAttribute("data-theme",p.theme); if(p.accentColor) document.documentElement.style.setProperty("--accent",p.accentColor); } catch{} }
</script>
</body>
</html>
