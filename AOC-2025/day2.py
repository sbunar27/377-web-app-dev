file = open('day2.txt', 'r')
lines = file.readlines()

lines = "".join(lines)
lines = lines.split(",")

print(lines)


# PART 1
total = 0

for line in lines:
    start, end = (int(x) for x in line.split('-'))

    for num in range(start, end+1):
        num = str(num)
        len_num = len(num)
        half_num = len_num//2

        if num[:half_num] == num[half_num:]:
            total += int(num)

print(total)
    

# PART 2
total = 0

for line in lines:
    start, end = (int(x) for x in line.split('-'))
    for num in range(start, end+1):
        num = str(num)
        n = num
        len_num = len(num)
        half_num = len_num//2

        for l in range(1, len_num):
            if len_num % l == 0:
                chunks = [num[i:i+l] for i in range(0, len(num), l)]
                if len(set(chunks)) == 1:
                    total += int(num)
                    break

print(total)