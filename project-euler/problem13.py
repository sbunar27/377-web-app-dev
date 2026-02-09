file = open('problem13.txt', 'r')
lines = file.readlines()

total = 0
for line in lines:
    line = int(line.strip())
    total += line

print(total)

# 5537376230