def findFuel(mass):
        return mass // 3 - 2

def part1():
    file = open("AOC-2019/day1.txt", "r")
    numbers = file.readlines()

    total = 0

    for num in numbers:
        total += findFuel(int(num))

    print(total)

def part2():
    file = open("AOC-2019/day1.txt", "r")
    numbers = file.readlines()

    total = 0

    for num in numbers:
        fuel = findFuel(int(num))
        total += fuel

        while fuel > 0:
            fuel = findFuel(fuel)

            if fuel > 0:
                total += fuel

    print(total)

part1()
part2()