file = open('day1.txt', 'r')
lines = file.readlines()

count = 0
total = 50


# PART 1
for line in lines:
    direction = line[0]
    letter = line[0]
    number = int(line[1:])

    print("Direction: " + str(direction))
    print("Rotation: " + str(number))


    if direction == "L":
        number *= -1
    total += number
    if total % 100 == 0:
        count += 1

    print(total%100)

# print(count)

# PART 2
count = 0
position = 50

for line in lines:
    direction = line[0]
    number = int(line[1:])

    if direction == "R":
        count += ((position + number)//100)
        position = position + number
    else:
        if position == 0:
            count += (position + number)//100
        else:
            count += ((100 - position + number)//100)
        
        position += 100 - (number % 100)
    position = position % 100

print(count)