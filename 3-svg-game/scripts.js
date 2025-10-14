let deck = [];

function Card(suit, rank, value) {
    this.suit = suit;
    this.rank = rank;
    this.value = value;
}

const suits = ['Hearts', 'Diamonds', 'Clubs', 'Spades'];
const ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'Jack', 'Queen', 'King', 'Ace'];
const values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', '10', '10', '10', 'DECIDE'];

function createDeck() {
    for (let i = 0; i < suits.length; i++) {
        for (let j = 0; j < ranks.length; j++) {
            const card = { rank: ranks[j], suit: suits[i] };
            deck.push(card);
        }
    }

    // Shuffles deck
    deck.sort(() => Math.random() - 0.5)
}

function findCard() {
    let card = deck.pop()
    let shownCard = []

    // 2. Find suit
    if (card.suit == 'Hearts') {
        shownCard.append('H')
    } else if (card.suit == 'Diamonds') {
        shownCard.append('D')
    } else if (card.suit == 'Clubs') {
        shownCard.append('C')
    } else if (card.suit == 'Spades') {
        shownCard.append('S')
    }

    // 1. Find rank
    for (let i = 0; i < ranks.length; i++) {
        if (card.rank == ranks[i]) {
            shownCard.append(ranks[i])
        }
    }
}