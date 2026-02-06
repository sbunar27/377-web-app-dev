# a < b < c, so a must be less than 1000/3 (roughly 333) 
# and b must be greater than a but less than c

# a^2 + b^2 = c^2

# a + b + c = 1000
# so c = 1000 - a - b

target = 1000

for a in range(1, target//3):
    for b in range(a+1, target//2):
        c = target - a - b
        if a**2 + b**2 == c**2:
            print(a*b*c)