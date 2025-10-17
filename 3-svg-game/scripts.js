let deck = [];
let bust = false

function Card(suit, rank, value) {
    this.suit = suit;
    this.rank = rank;
    this.value = value;
}

const suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
const ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];
const values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10, 'DECIDE'];

function createDeck() {
    // changes the screen to the actual game
    $('.start').css('visibility', 'hidden');
    $('.game').css('visibility', 'visible');
    $('#score').css('visibility', 'visible');
    $('#dealer-deck').css('visibility', 'visible');

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
pscore = 0
// dealer's score
dscore = 0

oldCardRank = 0
oldCardSuit = 0

function hit() {
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
    
    $('#score').html("Your Score: " + pscore);
    console.log("Player: " + pscore)

    // finds the card given to the dealer (if their score is under 17)
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
    }

    console.log("Dealer: " + dscore)

    // if the player's score is over 21, end the game
    if (pscore > 21) {
        bust = true
        endGame()
    }

    oldCardRank = newCard[0];
    oldCardSuit = newCard[1];
    
}

function stand() {
    if (pscore > 21) {
        bust = true
        endGame()
    }

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
    }
}

function endGame() {
    $(".game").prop("disabled", true);
    $('#dealer-deck').css('visibility', 'hidden');
    $('.cards').css('visibility', 'hidden');
    $('#dealer-deck').css('visibility', 'hidden');
    $('#start-fog').css('visibility', 'visible');
    $('#score').css('visibility', 'hidden');

    if (bust) {
        $('#message').html("BUST! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');
    } else if (Math.abs(21 - pscore) < (Math.abs(21 - dscore))) {
        $('#message').html("YOU WIN! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');
    } else {
        $('#message').html("YOU LOSE! Dealer's score: " + dscore);
        $('#message').css('visibility', 'visible');
    }
}