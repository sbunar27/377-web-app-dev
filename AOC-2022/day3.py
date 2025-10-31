# //Day 3 Part 1

file = open('day3.txt', 'r')
lines = file.readlines()

rucksack = "vJrwpWtwJgWrhcsFMMfFFhFp"

alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"

first_half = rucksack[:len(rucksack) // 2]
second_half = rucksack[len(rucksack) // 2:]
print(first_half)
print(second_half)

same_letter = None
score = 0
for letter in first_half:
    if letter in second_half:
        same_letter = letter
        print(alphabet.index(letter) + 1)
        break
print("Found common letter: " + same_letter)

for line in lines:
    line = line.strip()

    first_half = line[:len(line) // 2]
    second_half = line[len(line) // 2:]
    
    for letter in first_half:
        if letter in second_half:
            same_letter = letter
            score += alphabet.index(letter) + 1
            break
print("Part 1: " + str(score))


# //Day 3 Part 2


def findBadge(group):
    for letter in group[0]:
        if letter in group[1] and letter in group[2]:
            return letter


def findDuplicate(word1, word2):
    for letter in word1:
        if letter in word2:
            return letter


def getPriority(letter):
    priority = ord(letter)

    if (priority > 96): # lowercase
        priority -= 96
    else:               # uppercase
        priority -= 38

    return priority


def part1():
    sum = 0

    file = open('day3.txt', 'r')
    lines = file.readlines()

    for line in lines:
        line = line.strip()
        half = int(len(line) / 2)
        c1 = line[:half]
        c2 = line[half:]

        letter = findDuplicate(c1, c2)
        sum += getPriority(letter)

    print("Part 1: " + str(sum))


def part2():
    sum = 0

    file = open('day3.txt', 'r')
    lines = file.readlines()

    group = []
    counter = 0
    for line in lines:
        line = line.strip()

        if (counter > 0 and counter % 3 == 0):
            letter = findBadge(group)
            sum += getPriority(letter)
            group.clear()

        group.append(line)
        counter = counter + 1

    # Check the last group
    letter = findBadge(group)
    sum += getPriority(letter)

    print("Part 2: " + str(sum))


part1()
part2()