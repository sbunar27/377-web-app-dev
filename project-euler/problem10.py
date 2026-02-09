# Find the sum of all the primes below two million.

import math

total = 0

def is_prime(number):
    if number <= 1:
        return False
    for i in range(2, int(math.sqrt(number)) + 1):
        if number % i == 0:
            return False
    return True

for i in range(2, 2000000):
    if is_prime(i):
        total += i

print(total)

import time
start_time = time.time()
# Your code here
print("Process finished --- %s seconds ---" % (time.time() - start_time))
