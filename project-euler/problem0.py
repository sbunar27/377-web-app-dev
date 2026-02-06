# login problem

total = 0

for i in range(749001):
    num = i*i
    if i % 2 == 1:
        total += num

print(total)