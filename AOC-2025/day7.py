# with lots of help from father <3

file = open('day7.txt', 'r')
lines = file.readlines()

current = [0]*len(lines[0])
current[lines[0].index('S')]=1
part1 = 0
part2 = 1

for i in lines[1:]:
    for col in range(len(current)):
       if current[col] > 0 and i[col] == "^":
          part1 += 1
          part2 += current[col]

          current[col-1] += current[col]
          current[col+1] += current[col]
          
          current[col] = 0

print("Part 1: " + str(part1))

print("Part 2: " + str(part2))