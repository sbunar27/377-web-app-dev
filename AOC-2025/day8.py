file = open('day8.txt', 'r')
lines = file.readlines()
lines = (line.strip('\n') for line in lines)

coordinates = []

for line in lines:
    coord = line.split(',')
    coordinates.append(coord)


def euclideanCalculate(p1, p2):
    # found online
    return (sum((i-j)**2 for i,j in zip(p1,p2)))**0.5