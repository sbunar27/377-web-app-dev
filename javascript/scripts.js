function testArray() {
    let grades = [79, 34, 80, 90, 100, 67]
    $('#grades').html('Your grades are: ' + grades)

    let total = 0
    for (let i = 0; i < grades.length; i++) {
        total += grades[i];
    }

    let average = total/grades.length;

    $('#average').html('Your average is: ' + average);
    
}

function Card(suit, rank, value) {
    this.suit = suit;
    this.rank = rank;
    this.value = value;

}

let deck = [];

function shuffleDeck() {
    let aceOfSpades = new Card("Spades", "A", "14")

    let suits = ["Hearts", "Diamonds", "Clubs", "Spades"];

    for (let i = 0; i < 4; i++) {
        for (let rank = 1; rank < 14; rank++) {
            let card = new Card(suits[i], rank, rank);
            deck.push(card)
        }
    }

    // Use a readily available shuffle algorithm (give proper credit to author)
    deck.sort(() => Math.random() - 0.5)
}

function dealCard() {
    let nextCard = deck.pop()
    $('#card').html(nextCard.rank + " of " + nextCard.suit) 
}