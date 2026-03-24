<html>

<?php
$theme = 'brownSugar';
if (isset($_COOKIE['selectedTheme'])) {
    $allowed = ['brownSugar', 'taro', 'matcha', 'strawberry', 'butterfly'];
    if (in_array($_COOKIE['selectedTheme'], $allowed)) {
        $theme = $_COOKIE['selectedTheme'];
    }
}
?>
    <head>
        <title>book nook</title>
        <link rel="stylesheet" href="style.css?v=<?php echo rand(); ?>">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link rel="icon" type="image/png" href="favicon.png">
        <link href='https://fonts.googleapis.com/css?family=Playfair Display' rel='stylesheet'>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet"> 
        <script>
            function showAlert(type, title, message) {
                $('#alert').hide();
                $('#alert').removeClass('alert-success alert-info alert-warning alert-danger').addClass('alert-' + type);
                $('#alertTitle').text(title);
                $('#alertMessage').html(message);
                $('#alert').fadeIn();
            }
        </script>
    </head>
    <body data-theme="<?php echo htmlspecialchars($theme); ?>">
        <a href="index.php?content=list"><h1 id="icon"><span style="font-size: 24px;">&#9982;</span> book nook</h1></a>

        <!-- alert box for success/error messages -->
        <div id="alert" class="alert alert-position alert-success">
            <strong id="alertTitle">Success!</strong> <span id="alertMessage"> Success message.</span>
            <!-- NOT WORKING -->
            <a class="close" style="color: #0000005d; cursor: pointer;" onclick="$('#alert').fadeOut()"><span aria-hidden="true">close</span></a>
        </div>

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