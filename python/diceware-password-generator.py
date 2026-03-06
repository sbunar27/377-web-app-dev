file = open('diceware.txt', 'r')
lines = file.read().strip().splitlines()

dicewareLib = {}

for line in lines:
    parts = line.split()
    num = parts[0]
    word = parts[1]

    dicewareLib[word] = num

import random

for i in range(5):
    word = random.choice(list(dicewareLib))
    print(word)

