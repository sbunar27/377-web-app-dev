/*
 * Prepares the game with an initial die roll so there isn't a
 * strange 7-pip roll displayed.
 */
$(document).ready(function () {
    rollDie();
});

function rollDice() {
    rollDie(1);
    rollDie(2);
}

/*
 * Rolls the die to show a random number between 1 and 6
 */
function rollDie(dieNum) {
    // Step 1: hide every pip
    $("#d" + dieNum + " ~ .pip").css("visibility", "hidden");

    // Step 2: generate a random number between 1 and 6 (inclusive)
    var roll = Math.ceil(Math.random() * 6);
    console.log(roll);

    // Step 3: show the appropriate pips based on the roll
    if (roll == 1) {
        $("#d" + dieNum + "p4").css("visibility", "visible");
    } else if (roll == 2) {
        $("#d" + dieNum + "p1").css("visibility", "visible");
        $("#d" + dieNum + "p7").css("visibility", "visible");
    } else if (roll == 3) {
        $("#d" + dieNum + "p1").css("visibility", "visible");
        $("#d" + dieNum + "p4").css("visibility", "visible");
        $("#d" + dieNum + "p7").css("visibility", "visible");
    } else if (roll == 4) {
        $("#d" + dieNum + "p1").css("visibility", "visible");
        $("#d" + dieNum + "p3").css("visibility", "visible");
        $("#d" + dieNum + "p5").css("visibility", "visible");
        $("#d" + dieNum + "p7").css("visibility", "visible");
    } else if (roll == 5) {
        $("#d" + dieNum + "p1").css("visibility", "visible");
        $("#d" + dieNum + "p3").css("visibility", "visible");
        $("#d" + dieNum + "p4").css("visibility", "visible");
        $("#d" + dieNum + "p5").css("visibility", "visible");
        $("#d" + dieNum + "p7").css("visibility", "visible");
    } else  { // roll == 6
        $("#d" + dieNum + "p1").css("visibility", "visible");
        $("#d" + dieNum + "p2").css("visibility", "visible");
        $("#d" + dieNum + "p3").css("visibility", "visible");
        $("#d" + dieNum + "p5").css("visibility", "visible");
        $("#d" + dieNum + "p6").css("visibility", "visible");
        $("#d" + dieNum + "p7").css("visibility", "visible");
    }
}