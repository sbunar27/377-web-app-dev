# What is the smallest positive number that is evenly 
# divisible by all of the numbers from 1 to 20?

# minNum = 0
# i = 1

# while True:
#     if i % 1 == 0 and i % 2 == 0 and i % 3 == 0 and i % 4 == 0 and i % 5 == 0 and i % 6 == 0 and i % 7 == 0 and i % 8 == 0 and i % 9 == 0 and i % 10 == 0 and i % 11 == 0 and i % 12 == 0 and i % 13 == 0 and i % 14 == 0 and i % 15 == 0 and i % 16 == 0 and i % 17 == 0 and i % 18 == 0 and i % 19 == 0 and i % 20 == 0:
#         minNum = i
#         break
#     i += 1

# print(minNum)


# mr ciccolo's way

def checkFact(x, start, end):
    for i in range(start, end):
        if x % i != 0:
            return False
    return True


number = 20

def strategy1():
    number = 20

    while True:
        if checkFact(number, 2, 21):
            break
        number +=1

    return number

def strategy2():
    number = 2520

    while True:    
        if checkFact(number, 11, 21):
            break
        number += 2520

    return number

strategy1()
strategy2()