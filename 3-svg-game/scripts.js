let deck = [];
let bust = false

let hits = 0
let dhits = 0

const MINIMUM_BET = 5;
const STARTING_FUNDS = 50;
let winnings = STARTING_FUNDS;
let bet = 0;

const suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
const ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];
const values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10, 'DECIDE'];

function createDeck() {
    pscore = 0;
    dscore = 0;
    hits = 0;
    bust = false;
    deck = [];

    $('#score').text("Your Score: " + pscore);

    // changes the screen to the actual game
    $('#player-money').html('Your money: ' + winnings);
    $('.start').css('visibility', 'hidden');
    $('.game').css('visibility', 'visible');
    $('.endings').css('visibility', 'hidden');
    $('#score').css('visibility', 'visible');
    $('#dealer-deck').css('visibility', 'visible');
    $('#hits').css('visibility', 'visible');
    $('#bet-message').css('visibility', 'visible');
    // $('.table').css('visibility', 'hidden');
    $('.game').prop('disabled', false);

    // CREATES AND SHUFFLES THE DECK
    for (let i = 0; i < suits.length; i++) {
        for (let j = 0; j < ranks.length; j++) {
            const card = { rank: ranks[j], suit: suits[i] };
            deck.push(card);
        }
    }

    // Shuffles deck
    deck.sort(() => Math.random() - 0.5);

    console.log(deck)

    dhits = 0;

    if (dhits < 1) {
        dealerCard = findCard();
        dealerCardRank = dealerCard[0];
        dealerCardSuit = dealerCard[1];
        dealerCardValue = dealerCard[2];

        if (dealerCardValue == 'DECIDE') {
            if ((dscore + 11) > 21){
                dscore += 1;
            } else {
                dscore += 11;
            }
        } else {
            dscore += parseInt(dealerCardValue);
        }

        $('#' + dealerCardRank + dealerCardSuit).css('y', 50);
        $('#dealer-deck').css('x', 200);
        $('#' + dealerCardRank + dealerCardSuit).css('visibility', 'visible');

        dhits += 1
    }
}

function findCard() {
    // CHOOSES A CARD FROM THE DECK

    let card = deck.pop();
    let shownCard = [];

    // 1. Find rank
    for (let i = 0; i < ranks.length; i++) {
        if (card.rank == ranks[i]) {
            shownCard.push(ranks[i]);
        }
    }

    // 2. Find suit
    if (card.suit == 'Hearts') {
        shownCard.push('H');
    } else if (card.suit == 'Diamonds') {
        shownCard.push('D');
    } else if (card.suit == 'Clubs') {
        shownCard.push('C');
    } else if (card.suit == 'Spades') {
        shownCard.push('S');
    }

    // 3. Assign value

    for (let i = 0; i < values.length; i++) {
        if (card.rank == values[i]) {
            shownCard.push(ranks[i]);
        } else if (card.rank == "T") {
            shownCard.push(10)
            break
        } else if (card.rank == "J") {
            shownCard.push(10)
            break
        } else if (card.rank == "K") {
            shownCard.push(10)
            break
        } else if (card.rank == "Q") {
            shownCard.push(10)
            break
        } else if (card.rank == "A") {
            shownCard.push('DECIDE')
            break
        }
    }

    console.log(shownCard);

    return shownCard;
}

// player's score
let pscore = 0
// dealer's score
let dscore = 0

let oldCardRank = 0
let oldCardSuit = 0

let playerCards = []

function hit() {
    if (validateBet()) {
        hits += 1

        if (hits >= 2){
            $('#hits').css('visibility', 'hidden')
        }
        $('#bet-message').css('visibility', 'hidden')

        // figure out the player's new score based on the card they got
        newCard = findCard();
        console.log(newCard)
        cardRank = newCard[0];
        cardSuit = newCard[1];
        cardValue = newCard[2];

        if (oldCardRank != 0) {
            $('#' + oldCardRank + oldCardSuit).css('visibility', 'hidden');
        }
        $('#' + cardRank + cardSuit).css('y', 300);
        $('#' + cardRank + cardSuit).css('visibility', 'visible');
        console.log(cardRank + cardSuit)

        if (cardValue == 'DECIDE') {
            if ((pscore + 11) > 21){
                pscore += 1;
            } else {
                pscore += 11;
            }
        } else {
            pscore += parseInt(cardValue);
        }
        
        $('#score').text("Your Score: " + pscore);
        console.log("Player: " + pscore)

        // if the player's score is over 21, end the game
        if (pscore > 21) {
            bust = true
            endGame()
        }

        oldCardRank = newCard[0];
        oldCardSuit = newCard[1];
    } else {
        $('#bet-message').css('visibility', 'visible')
        $('#bet-message').html('Please enter a valid bet.')
    }
}

function stand() {
    if (pscore > 21) {
        bust = true
    }
    if (validateBet() && (hits >= 2)) {
        $('#hits').css('visibility', 'hidden');
    } else {
        $('#bet-message').css('visibility', 'visible')
        $('#bet-message').html('Please enter a valid bet.')
    }
}

function dealerHit() {
    dhits = 0;

    $('#dealer-score').css("visibility", "visible")

    if (dhits < 1) {
        dealerCard = findCard();
        dealerCardRank = dealerCard[0];
        dealerCardSuit = dealerCard[1];
        dealerCardValue = dealerCard[2];

        if (dealerCardValue == 'DECIDE') {
            if ((dscore + 11) > 21){
                dscore += 1;
            } else {
                dscore += 11;
            }
        } else {
            dscore += parseInt(dealerCardValue);
        }

        $('#' + dealerCardRank + dealerCardSuit).css('y', 50);
        $('#' + dealerCardRank + dealerCardSuit).css('x', 200);
        $('#dealer-deck').css('x', 200);
        $('#' + dealerCardRank + dealerCardSuit).css('visibility', 'visible');

        dhits += 1
    }

    if (dscore < 17 && dhits > 2) {
        dealerCard = findCard();
        dealerCardRank = dealerCard[0];
        dealerCardSuit = dealerCard[1];
        dealerCardValue = dealerCard[2];

        console.log(dealerCardRank + dealerCardSuit)

        if (dealerCardValue == 'DECIDE') {
            if ((dscore + 11) > 21){
                dscore += 1;
            } else {
                dscore += 11;
            }
        } else {
            dscore += parseInt(dealerCardValue);
        }
    }
    console.log("Dealer: " + dscore)
    $('#dealer-score').text("Dealer's Score: " + dscore)
}

function endTurn() {
    while (true) {
        dealerHit();
        if (dscore > 17) {
            break;
        }
    }

    $('#turn-notif').html("Dealer's turn is over.")
    $('#end-button').css("visibility", "visible")
}

function validateBet() {
    bet = parseInt($("#bet").val());

    console.log("Bet: " + bet);

    if (isNaN(bet) || bet < MINIMUM_BET || bet > winnings) {
        return false;
    } else {
        $("#bet").prop("disabled", true);
        return true;
    }
}

function endGame() {
    $(".game").prop("disabled", true);
    $('#dealer-deck').css('visibility', 'hidden');
    $('.cards').css('visibility', 'hidden');
    $('#dealer-deck').css('visibility', 'hidden');
    $('#start-fog').css('visibility', 'visible');
    $('#score').css('visibility', 'hidden');
    $('.table').css('visibility', 'hidden');
    $('#dealer').css('visibility', 'hidden');
    $('#end-button').css('visibility', 'hidden');
    $('#turn-notif').css('visibility', 'hidden');

    // if you bust, you immediately lose
    if (bust) {
        $('#message').text("BUST!");
        $('#message').css('visibility', 'visible');

        winnings -= bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: " + winnings);

    // if your score is closer to 21 (and the dealer's score isnt over 21), you win
    } else if (Math.abs(21 - pscore) < (Math.abs(21 - dscore)) && dscore <= 21) {
        $('#message').text("YOU WIN! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings += bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: $" + winnings);

    // if your score is the same as the dealer's, it's a draw
    } else if (Math.abs(21 - pscore) == (Math.abs(21 - dscore)) && dscore <= 21) {
        $('#message').text("DRAW! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        $("#winnings").text("Winnings: None");

    // if your score is further from 21 than the dealer's (and their score is <= 21), you lose
    } else if ((Math.abs(21 - pscore) > Math.abs(21 - dscore)) && dscore <= 21) {
        $('#message').text("YOU LOSE! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings -= bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: None");

    } else if ((Math.abs(21 - pscore) < (Math.abs(21 - dscore)) && dscore > 21)) {
        $('#message').text("YOU WIN! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings += bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: $" + winnings);
    // If you didn't bust and the dealer's score is over 21, you win
    } else if (bust == false && dscore > 21) {
        $('#message').text("YOU WIN! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings += bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: $" + winnings);
    }

    $('#winnings').css('visibility', 'visible');
    $('#start-button').css('visibility', 'visible');
}