file = open('day9.txt', 'r')
lines = file.readlines()
lines = (line.strip() for line in lines)

coords = []

coords.append(line.split(',') for line in lines)
print(coords)