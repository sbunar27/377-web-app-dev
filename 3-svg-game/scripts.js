let deck = [];

function Card(suit, rank, value) {
    this.suit = suit;
    this.rank = rank;
    this.value = value;
}

const suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
const ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];
const values = [2, 3, 4, 5, 6, 7, 8, 9, 10, 10, 10, 10, 'DECIDE'];

function createDeck() {
    // CREATES AND SHUFFLES THE DECK

    $('.start').css('visibility', 'hidden');
    $('.game').css('visibility', 'visible');

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

function hit() {
    newCard = findCard()
    cardRank = newCard[1]
    cardSuit = newCard[2]
    cardValue = newCard[3]

    $('#' + cardRank + cardSuit).css('visibility', 'visible')

    if (cardValue == 'DECIDE') {
        if ((pscore + 11) > 21){
            pscore += 1
        } else {
            pscore += 11
        }
    } else {
        pscore += cardValue
    }
}