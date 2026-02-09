<html>
    <head>
        <title>HHS Jazz Ensemble</title>

        <style>
            a{
                border: solid 3px lightblue;
                background-color: aliceblue;
                color: lightblue;
                text-decoration: none;
                width: 200px;
                padding: 7px;
                border-radius: 5px;
            }

            body{
                background-color: #ffffff;
                color: lightblue;
                font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            }

            td{
                width: 200px;
                border: solid 3px lightblue;
                padding: 10px;
            }

            .selected{
                border: solid 3px aliceblue;
                background-color: lightblue;
                color: aliceblue;
            }

            .header-tb {
                border: solid 3px aliceblue;
                background-color: lightblue;
                color: aliceblue;
            }

            iframe{
                border-radius: 10px;
            }
           
        </style>
    </head>

    <body>
        <!-- section 1: header -->
        <h1>Hanover High School Jazz Ensemble</h1>

        <!-- section 2: menu -->
        <?php
            extract($_REQUEST);
            if (!isset($nav))
            {
                $nav = "home";
            }
        ?>
        <a href="club.php?nav=home" <?php if ($nav=="home") print('class="selected"'); ?>>Home</a>
        <a href="club.php?nav=schedule" <?php if ($nav=="schedule") print('class="selected"'); ?>>Schedule</a>
        <a href="club.php?nav=roster" <?php if ($nav=="roster") print('class="selected"'); ?>>Info</a>
        <a href="club.php?nav=media" <?php if ($nav=="media") print('class="selected"'); ?>>Media</a>

        <br><br><br>

        <!-- section 3: content -->
        
        <?php include("club-$nav.php");?>
    </body>
</html>