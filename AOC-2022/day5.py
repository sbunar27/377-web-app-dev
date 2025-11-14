file = open('day5.txt', 'r')
lines = file.readlines()

stacks = [
    ['H', 'T', 'Z','D'],
    ['Q', 'R', 'W', 'T', 'G', 'C', 'S'],
    ['P', 'B', 'F', 'Q','N','R','C','H'],
    ['L','C','N','F','H','Z'],
    ['G','L','F','Q','S'],
    ['V','P','W','Z','B','R','C','S'],
    ['Z','F','J'],
    ['D','L','V','Z','R','H','Q'],
    ['B','H','G','N','F','Z','L','D']
]

for line in lines:
    line = line.strip().split()
    print(line)

    num_blocks = int(line[1])
    source = int(line[3]) - 1
    destination = int(line[5]) - 1

    print('Move ' + str(num_blocks))
    print('From ' + str(stacks[source]))
    print('To ' + str(stacks[destination]))

    for i in range(num_blocks):
        block = stacks[source].pop()
        stacks[destination].append(block)


for i in range(len(stacks)):
    print(stacks[i][-1], end='')

