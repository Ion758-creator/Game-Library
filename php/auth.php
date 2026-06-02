<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Register</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="container">

    <div class="auth-box">

        <h1>Game Library</h1>
        <p>Login or create an account</p>

        <form action="process.php" method="POST">

            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password" required>

            <div class="buttons">
                <button type="submit" name="login">Login</button>

                <button type="submit" name="register">
                    Register
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>