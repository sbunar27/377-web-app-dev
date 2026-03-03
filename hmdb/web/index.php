<html>
    <head>
        <title>hMDb</title>
        <!-- favicon -->
        <link rel="icon" type="image/x-icon" href="favicon_io/favicon.ico"/>   
        <link rel="stylesheet" href="style.css"/>   
    </head>
    <body>
        <h1>hMDb: The Hanover Movie Database</h1>

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
