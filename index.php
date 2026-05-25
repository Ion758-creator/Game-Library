<?php
$games = ["GTA V", "Minecraft", "Valorant", "FIFA 24"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game Library</title>
</head>
<body>

<h1>Game Library</h1>

<ul>
    <?php foreach ($games as $game): ?>
        <li><?php echo $game; ?></li>
    <?php endforeach; ?>
</ul>

</body>
</html>