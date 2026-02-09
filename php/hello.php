<html>
    <head>
        <title>PHP Lesson 1</title>
    </head>

    <body>
        <h1>PHP Lesson 1</h1>
        <p>
            This is the first PHP lesson with simple markup. PHP runs on the server side, not the client side.
            <?php 

                # every variable has to start with a dollar sign
                for ($i = 0; $i < 10; $i ++){
                echo 'Hello<br>'; 
                }

                $firstName = "Dennis";
                $lastName = "Whitaker";

                // use . to concatenate strings
                $fullName = $firstName . " " . $lastName;

                echo $fullName;

                // string concatenation
                echo "<p>" . $fullName . " is in The Pitt." . "</p>";

                // string interpolation -- still works!
                echo "<p> $fullName is in The Pitt. </p>";

                // string interpolation only works within double quotes
                echo '<p> $fullName is in The Pitt. </p>';
            ?>
        </p>
    </body>
</html>