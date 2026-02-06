# largest palindrome product

def isPal(n):
    # [::-1 makes it reverse]
    return str(n) == str(n)[::-1]

digits = 3
maxPal = 0
n = ""

for i in range(digits):
    n += str(9)

n = int(n)

for i in range(999, 99, -1):
    for j in range(i, 99, -1):
        total = i*j

        if total <= maxPal:
            break
        if isPal(total):
            maxPal = total
            break

print(maxPal)