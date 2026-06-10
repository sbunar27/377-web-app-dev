<html>
    <head>
        <title>solace</title>
        <link rel="stylesheet" href="style.css?v=<?php echo rand(); ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet">
        <link rel="icon" type="image/png" href="leaf.png">
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    </head>
    <body>
        <a href="index.php?nav=agenda"><h1 id="icon"><span style="font-size: 24px;">&#127793;</span> solace</h1></a>
        
            <?php
                include_once("library.php");

                $nav = isset($_GET['nav']) ? $_GET['nav'] : 'agenda';

                // only allow these specific files to be included
                $allowed_views = ['agenda', 'detail', 'completed'];
                if (!in_array($nav, $allowed_views)) {
                    $nav = 'agenda';
                }

                include("$nav.php");
            ?>
    </body>
</html>