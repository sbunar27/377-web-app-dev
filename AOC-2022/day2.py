# //Day 2 Parts 1 and 2

file = open('day2.txt', 'r')
lines = file.readlines()



score = 0  
for line in lines:
    line = line.strip()
    line = line.split(" ")
    if line[1] == "X":
        if line[0] == "A":
            score += 4
        elif line[0] == "B":
            score += 1
        else:
            score += 7
    elif line[1] == "Y":
        if line[0] == "A":
            score += 8
        elif line[0] == "B":
            score += 5
        else:
            score += 2
    else:
        if line[0] == "A":
            score += 3
        elif line[0] == "B":
            score += 9 
        else:
            score += 6
print("Part 1: " + str(score))

rock = 1
paper = 2
scissors = 3
score = 0
for line in lines:
    line = line.strip().split(" ")
    if line[1] == "Z":
        score += 6
        if line[0] == "A":
            score += 2
        if line[0] == "B":
            score += 3
        if line[0] == "C":
            score += 1

    elif line[1] == "Y":
        score += 3
        if line[0] == "A":
            score += 1
        if line[0] == "B":
            score += 2
        if line[0] == "C":
            score += 3

    else:
        if line[0] == "A":
            score += 3
        if line[0] == "B":
            score += 1
        if line[0] == "C":
            score += 2
print("Part 2: " + str(score))
