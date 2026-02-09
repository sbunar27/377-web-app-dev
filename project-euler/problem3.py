# LARGEST PRIME FACTOR OF A NUMBER

# Mr. Ciccolo's way
import math

def is_prime(number):
    for i in range(2, round(math.sqrt(number))):
        if number % i == 0:
            return False
        
    return True

n = 600851475143
max = 0
j = 2

while j < math.sqrt(n):
    if n % j == 0 and is_prime(j):
        max = j

    j += 1

print(max)





# my way
n = 600851475143
maxFactor = 1

# check all even nums 2 is the only even prime number, the code gets all the 2s out of n first
while n % 2 == 0:
    maxFactor = 2
    n //= 2

# check all odd nums
i = 3
# number must have a factor less than or equal to its square root
while i*i <= n:
    # it divides it out until it can't anymore, makes sure that when it moves to the next i it's only ever finding prime factors
    while n % i == 0:
        maxFactor = i
        n //= i
    i += 2

# if the remaining n is greater than 2 it means the leftover value is the largest prime number 
if n > 2:
    maxFactor = n


print(maxFactor)