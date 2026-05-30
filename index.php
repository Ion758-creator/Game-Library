<?php
include 'php/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library</title>

   <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="container">

    <aside class="sidebar">

        <div class="logo">
            <i class="fa-solid fa-gamepad"></i>

            <div>
                <h2>MY LIBRARY</h2>
                <p>Game Collection</p>
            </div>
        </div>

        <ul class="menu">
         <li><i class="fa-solid fa-house"></i> Library</li>
            <li><i class="fa-solid fa-gamepad"></i> Playing</li>
            <li><i class="fa-solid fa-circle-check"></i> Completed</li>
            <li><i class="fa-solid fa-clock"></i> Backlog</li>
            <li><i class="fa-solid fa-table-cells"></i> All Games</li>
        </ul>

        <ul class="menu bottom">
            <li><i class="fa-solid fa-circle-plus"></i> Add Game</li>
            <li><i class="fa-solid fa-gear"></i> Settings</li>
        </ul>

        <div class="sidebar-card">
            <i class="fa-solid fa-gamepad big-icon"></i>

            <h3>Enjoy your games!</h3>

            <p>Keep track of your adventures 💜</p>
        </div>

    </aside>

    <main class="main">

        <div class="header">

            <div class="title">
                <h1></i> Game Library</h1>
                <p>All your games in one place</p>
            </div>

            <div class="header-right">

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search games...">
                </div>

                <button class="add-btn">
                    <i class="fa-solid fa-plus"></i>
                    Add Game
                </button>

            </div>

        </div>

        <div class="filters">

            <button class="filter active">All</button>
            <button class="filter playing">Playing</button>
            <button class="filter completed">Completed</button>
            <button class="filter backlog">Backlog</button>

            <span class="total">
                Total: <?php echo count($games); ?> games
            </span>

        </div>

        <div class="games-grid">

            <?php foreach($games as $game) { ?>

                <div class="card">

                    <img src="<?php echo $game['image']; ?>" alt="">

                    <div class="card-content">

                        <span class="status <?php echo strtolower($game['status']); ?>">
                            <?php echo $game['status']; ?>
                        </span>

                        <h2><?php echo $game['title']; ?></h2>

                        <div class="rating">
                            ⭐⭐⭐⭐⭐
                            <span>(<?php echo $game['rating']; ?>)</span>
                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

        <footer>
            Made with ❤️ by Ion Șaptefrați
        </footer>

    </main>

</div>

</body>
</html>