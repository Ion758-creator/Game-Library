<?php
$messagesFile = __DIR__ . '/../data/messages.json';
$messages = file_exists($messagesFile) ? json_decode(file_get_contents($messagesFile), true) : [];
if (!is_array($messages)) $messages = [];

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'empty';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'email';
    } else {
        $messages[] = [
            'name'    => $name,
            'email'   => $email,
            'message' => $message,
            'date'    => date('Y-m-d H:i:s')
        ];
        file_put_contents($messagesFile, json_encode($messages, JSON_PRETTY_PRINT));
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library | Contact</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .contact-wrap { max-width: 600px; margin: 40px auto; }
        .contact-card { background: #0b1020; border: 1px solid #222; border-radius: 20px; padding: 40px; }
        .contact-card h1 { color: var(--accent,#8b5cf6); margin-bottom: 8px; }
        .contact-card p.sub { color:#94a3b8; margin-bottom:30px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display:block; color:#cbd5e1; font-size:14px; margin-bottom:6px; font-weight:500; }
        .form-group input, .form-group textarea {
            width:100%; padding:12px 16px;
            background:rgba(139,92,246,0.05); border:1px solid rgba(139,92,246,0.25);
            border-radius:10px; color:#fff; font-size:14px; outline:none;
            transition:.2s;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color:var(--accent,#8b5cf6);
            box-shadow:0 0 0 3px rgba(139,92,246,0.15);
        }
        .form-group textarea { resize:vertical; min-height:120px; font-family:inherit; }
        .btn-send { background:var(--accent,#8b5cf6); color:#fff; border:none; padding:12px 32px;
            border-radius:25px; font-size:15px; font-weight:600; cursor:pointer; transition:.3s; }
        .btn-send:hover { opacity:.85; }
        .msg-box { padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:14px; }
        .msg-box.success { background:rgba(74,222,128,.1); border:1px solid #4ade80; color:#4ade80; }
        .msg-box.error   { background:rgba(239,68,68,.1);  border:1px solid #ef4444; color:#ef4444; }
        .back-link { display:inline-flex; align-items:center; gap:8px; color:var(--accent,#8b5cf6);
            text-decoration:none; font-weight:600; margin-bottom:20px; }
        .back-link:hover { text-decoration:underline; }
        .req { color:#ef4444; }
    </style>
</head>
<body>
<div style="padding:30px 20px;">
    <div class="contact-wrap">
        <a href="../index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Library</a>
        <div class="contact-card">
            <h1><i class="fa-solid fa-envelope"></i> Contact / Feedback</h1>
            <p class="sub">Have a suggestion or question? Send us a message!</p>

            <?php if ($success): ?>
                <div class="msg-box success"><i class="fa-solid fa-check-circle"></i> Message sent successfully! Thank you.</div>
            <?php elseif ($error === 'empty'): ?>
                <div class="msg-box error"><i class="fa-solid fa-triangle-exclamation"></i> Please fill in all fields.</div>
            <?php elseif ($error === 'email'): ?>
                <div class="msg-box error"><i class="fa-solid fa-triangle-exclamation"></i> Please enter a valid email address.</div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Name <span class="req">*</span></label>
                    <input type="text" name="name" placeholder="Your name" value="<?= htmlspecialchars($_POST['name']??'') ?>" required>
                </div>
                <div class="form-group">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" placeholder="your@email.com" value="<?= htmlspecialchars($_POST['email']??'') ?>" required>
                </div>
                <div class="form-group">
                    <label>Message <span class="req">*</span></label>
                    <textarea name="message" placeholder="Your message..." required><?= htmlspecialchars($_POST['message']??'') ?></textarea>
                </div>
                <button type="submit" class="btn-send"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
            </form>
        </div>
    </div>
</div>
<script>
    const saved = localStorage.getItem('gamelib_settings');
    if (saved) {
        const s = JSON.parse(saved);
        if (s.theme === 'light') document.documentElement.setAttribute('data-theme','light');
        if (s.accentColor) document.documentElement.style.setProperty('--accent', s.accentColor);
    }
</script>
</body>
</html>
