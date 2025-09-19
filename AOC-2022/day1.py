max = 0
current = 0
largest_nums = []

file = open('day1.txt', 'r')
lines = file.readlines()

# PART ONE
for line in lines:
    line = line.strip()

    if line != '':
        # It's not blank, add to the running total
        current += int(line)
    else:
        # It's blank, update the max (if necessary) and reset the current (always)
        if current > max:
            max = current
            # PART TWO
            largest_nums.append(max)
        current = 0
    

print('Part one: ' + str(max))

# DOES NOT WORK
maxes = sorted(largest_nums)
sum_maxes = maxes[-1] + maxes[-2] + maxes[-3]
print('Part two: ' + str(sum_maxes))