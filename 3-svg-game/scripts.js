let deck = [];
let bust = false;

let dealerBust = false;
let playerTurn = true;

const MINIMUM_BET = 5;
const STARTING_FUNDS = 50;
let winnings = STARTING_FUNDS;
let bet = 0;

const suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
const ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];
const values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10, 'DECIDE'];

function createDeck() {
    pscore = 0
    dscore = 0

    // resets score for returning player
    $('#score').text("Your Score: " + pscore);

    // changes the screen to the actual game
    $('#player-money').html('Your money: ' + winnings);
    $('.start').css('visibility', 'hidden');
    $('#beginning').css('visibility', 'visible');
    $('.game').css('visibility', 'visible');
    $('.endings').css('visibility', 'hidden');
    $('#score').css('visibility', 'visible');
    $('#dealer-deck').css('visibility', 'visible');
    $('#bet-message').css('visibility', 'visible');
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

let hits = 0

function hit() {
    hits += 1

    if (hits > 2) {
        $('#beginning').css('visibility', 'hidden');
    }

    if (validateBet()) {

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
        if (hits > 2 && playerTurn == false) {
        // finds the card given to the dealer (if their score is under 17 and the player's turn is over)
            // if (dscore < 17) {
            //     dealerCard = findCard();
            //     dealerCardRank = dealerCard[0];
            //     dealerCardSuit = dealerCard[1];
            //     dealerCardValue = dealerCard[2];

            //     console.log(dealerCardRank + dealerCardSuit)

            //     if (dealerCardValue == 'DECIDE') {
            //         if ((dscore + 11) > 21){
            //             dscore += 1;
            //         } else {
            //             dscore += 11;
            //         }
            //     } else {
            //         dscore += parseInt(dealerCardValue);
            //     }

            //     if (dscore > 21) {
            //         dealerBust = true;
            //     }

            //     $('#dealer-move').text("Dealer chose: Hit");
            // } else {
            //     $('#dealer-move').text("Dealer chose: Stand")
            // }

            console.log("Dealer: " + dscore)
        }

        // if the player's score is over 21, end the game
        if (pscore > 21) {
            bust = true
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

    if (validateBet()) {
        // if (hits > 2 && playerTurn == false) {
        //     // lets the dealer hit (if their score is under 17)
        //     if (dscore < 17) {
        //         dealerCard = findCard();
        //         dealerCardRank = dealerCard[0];
        //         dealerCardSuit = dealerCard[1];
        //         dealerCardValue = dealerCard[2];

        //         console.log(dealerCardRank + dealerCardSuit)

        //         if (dealerCardValue == 'DECIDE') {
        //             if ((dscore + 11) > 21){
        //                 dscore += 1;
        //             } else {
        //                 dscore += 11;
        //             }
        //         } else {
        //             dscore += parseInt(dealerCardValue);
        //         }
        //         console.log("Dealer: " + dscore)

        //         $('#dealer-move').text("Dealer chose: Hit")

        //         if (dscore > 21) {
        //             dealerBust = true;
        //         }
        //     } else {
        //         $('#dealer-move').text("Dealer chose: Stand")
        //     }
        // }
    } else {
        $('#bet-message').css('visibility', 'visible')
        $('#bet-message').html('Please enter a valid bet.')
    }
}

function dealerTurn() {
    while (not (dealerBust)) {
        if (not (playerTurn)) {
            // lets the dealer hit (if their score is under 17)
            if (dscore < 17) {
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
                console.log("Dealer: " + dscore)

                if (dscore > 21) {
                    dealerBust = true;
                }
            } else {
                if (dscore > 21) {
                    dealerBust = true;
                }
            }
        }
    }

    endGame();
}

function endTurn() {
    playerTurn = false
    dealerTurn()
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

    if (bust) {
        $('#message').text("BUST! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings -= bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: " + winnings);
    } else if (Math.abs(21 - pscore) < (Math.abs(21 - dscore)) || (dealerBust)) {
        $('#message').text("YOU WIN! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings += bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: $" + winnings);
    } else if (Math.abs(21 - pscore) == (Math.abs(21 - dscore)) && (dscore < 21)) {
        $('#message').text("DRAW! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        $("#winnings").text("Winnings: None");
    } else {
        $('#message').text("YOU LOSE! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');

        winnings -= bet;
        $("#winnings").text("Total Payout: $" + winnings);
        console.log("Winnings: None");
    }

    $('#winnings').css('visibility', 'visible');
    $('#start-button').css('visibility', 'visible');
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