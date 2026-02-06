# consider the terms in the fibonacci sequence whose values
# do not exceed 4 million, find the sum of the even-valued terms

a = 1
b = 2
total = 2
limit = 4000000

while (a+b) < limit:
    nextFib = a+b
    if nextFib % 2 == 0:
        total += nextFib

    a = b
    b = nextFib

print(total)