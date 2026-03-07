<html>

<?php
$theme = 'brownSugar';
if (isset($_COOKIE['selectedTheme'])) {
    $allowed = ['brownSugar', 'taro', 'matcha', 'strawberry'];
    if (in_array($_COOKIE['selectedTheme'], $allowed)) {
        $theme = $_COOKIE['selectedTheme'];
    }
}
?>

    <head>
        <title>book nook</title>
        <link rel="stylesheet" href="9-custom-web-app/web/style.css"/>
        <link rel="icon" type="image/png" href="favicon.png">
        <link href='https://fonts.googleapis.com/css?family=Playfair Display' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet"> 
    </head>
    <body data-theme="<?php echo htmlspecialchars($theme); ?>">
        <a href="index.php?content=list"><h1 id="icon"><span style="font-size: 24px;">&#9982;</span> book nook</h1></a>

        <?php

            // include the library
            include("library.php");

            // include the proper content based on nav request parameter
            if (!isset($nav)){
                $nav="list";
            }
            include("$nav.php");
        ?>

    </body>
</html>