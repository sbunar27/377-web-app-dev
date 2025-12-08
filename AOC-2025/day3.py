file = open('day3.txt', 'r')
lines = file.readlines()

# PARTS 1 AND 2 WITH NUMNUMS

total = 0

for line in lines:
    line = line.strip()
    num = ''
    numNums = 12
    index = -1

    for j in range(1, numNums + 1):
        maxNumNumNums = 0
        for i in range(index+1, len(line) - numNums + j):
            numNumNum = int(line[i])
            if numNumNum > maxNumNumNums:
                maxNumNumNums = numNumNum
                index = i
        num+=str(maxNumNumNums)

    total += int(num)


print(total)