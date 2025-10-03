# In the first round, your opponent will choose Rock (A), 
# and you should choose Paper (Y). This ends in a win for 
# you with a score of 8 (2 because you chose Paper + 6 because 
# you won).
# In the second round, your opponent will choose Paper (B),
# and you should choose Rock (X). This ends in a loss for you 
# with a score of 1 (1 + 0).
# The third round is a draw with both players choosing Scissors,
# giving you a score of 3 + 3 = 6.

file = open('day2.txt', 'r')
lines = file.readlines()

lose = 0
draw = 3
win = 6

rock = 1
paper = 2
scissors = 3

score = 0

for line in lines:
    throws = line.strip().split(' ')
    print(throws)

    # YOU choose rock
    if throws[0] == 'A':
        if throws[1] == 'X':
            score += rock
            score += draw
        elif throws[1] == 'Y':
            score += rock
            score += lose
        elif throws[1] == 'Z':
            score += rock
            score += win

    # YOU choose paper
    if throws[0] == 'B':
        if throws[1] == 'X':
            score += paper
            score += win
        elif throws[1] == 'Y':
            score += paper
            score += draw
        elif throws[1] == 'Z':
            score += paper
            score += lose

    # YOU choose scissors
    if throws[0] == 'C':
        if throws[1] == 'X':
            score += scissors
            score += lose
        elif throws[1] == 'Y':
            score += scissors
            score += win
        elif throws[1] == 'Z':
            score += scissors
            score += draw

print(score)
    
