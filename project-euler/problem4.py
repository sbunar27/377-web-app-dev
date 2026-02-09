# largest palindrome product

def isPal(n):
    # [::-1 makes it reverse]
    return str(n) == str(n)[::-1]

digits = 3
maxPal = 0
n = ""

# starts at 999, goes down to 100
for i in range(999, 99, -1):
    # starts at current value of i and goes down to 100
    for j in range(i, 99, -1):
        total = i*j

        # if total is smaller than highest palindrome so far, no point in continuing the loop
        if total <= maxPal:
            break
        if isPal(total):
            maxPal = total
            break

print(maxPal)

# 906609