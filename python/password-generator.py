# This program generates random passwords using the criteria specified by the user.

import random
from sys import argv

script, l, p = argv

# step 1: get input
if len(argv) == 3:
    length = int(argv[1])
else:
    length = int(input("What is the required length of the password? "))
    includeNum = input("Should it include a number? [Y/N] ").upper()[0] == "Y"
    includeLower = input("Should it include lowercase characters? [Y/N] ").upper()[0] == "Y"
    includeUpper = input("Should it include uppercase characters? [Y/N] ").upper()[0] == "Y"
    includeSpecial = input("Should it include special characters? [Y/N] ").upper()[0] == "Y"

#step 2: create password
password = []
specials = "!@#$%^&*()-=_+{}[]|;:'<>,./?~"

numNums = 0
numCheck = 0
numLower = 0
numUpper = 0
numSpecial = 0

# take care of requirements before the loop
if includeNum:
    password.append(str(random.randint(0, 9)))
if includeUpper:
    password.append(chr(ord("A") + random.randint(0, 25)))
if includeLower:
    password.append(chr(ord("a") + random.randint(0, 25)))
if includeSpecial:
    password.append(random.choice(specials))

while len(password) < length:
    path = random.randint(1,4)

    if includeNum and path == 1:
        password.append(str(random.randint(0, 9)))
    if includeUpper and path == 2:
        password.append(chr(ord("A") + random.randint(0, 25)))
    if includeLower and path == 3:
        password.append(chr(ord("a") + random.randint(0, 25)))
    if includeSpecial and path == 4:
        password.append(random.choice(specials))

random.shuffle(password)
print("".join(password))
    
