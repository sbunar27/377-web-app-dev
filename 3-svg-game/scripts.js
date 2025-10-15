let deck = [];

function Card(suit, rank, value) {
    this.suit = suit;
    this.rank = rank;
    this.value = value;
}

const suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
const ranks = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];
const values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', '10', '10', '10', 'DECIDE'];

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

    // Gives the player their first card
}

function findCard() {
    // CHOOSES A CARD FROM THE DECK

    let card = deck.pop();
    let shownCard = [];

    // 1. Find rank
    for (let i = 0; i < ranks.length; i++) {
        if (card.rank == ranks[i]) {
            shownCard.append(ranks[i]);
        }
    }

    // 2. Find suit
    if (card.suit == 'Hearts') {
        shownCard.append('H');
    } else if (card.suit == 'Diamonds') {
        shownCard.append('D');
    } else if (card.suit == 'Clubs') {
        shownCard.append('C');
    } else if (card.suit == 'Spades') {
        shownCard.append('S');
    }

    // 3. Assign value

    for (let i = 0; i < values.length; i++) {
        if (card.rank == values[i]) {
            shownCard.append(ranks[i]);
        } else if (card.rank == "T") {
            shownCard.append(10)
        } else if (card.rank == "J") {
            shownCard.append(10)
        } else if (card.rank == "K") {
            shownCard.append(10)
        } else if (card.rank == "Q") {
            shownCard.append(10)
        } else if (card.rank == "A") {
            shownCard.append('DECIDE')
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
    card = findCard()
}