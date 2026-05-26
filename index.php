<?php
$games = ["GTA V", "Minecraft", "Valorant", "FIFA 24"];
?>

<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Library</title>
</head>

<body>

    <h1>Game Library</h1>

    <ul>
        <?php foreach ($games as $game): ?>
            <li><?php echo $game; ?></li>
        <?php endforeach; ?>
    </ul>

    <script>
        console.log("Pagina principala functioneaza!");
    </script>

</body>

</html>