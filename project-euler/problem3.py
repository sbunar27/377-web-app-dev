# largest prime factor

n = 600851475143
maxFactor = 1

# check all even nums
while n % 2 == 0:
    maxFactor = 2
    n //= 2

# check all odd nums
i = 3
while i*i <= n:
    while n % i == 0:
        maxFactor = 1
        n //= i
    i += 2

if n > 2:
    maxFactor = n


print(maxFactor)