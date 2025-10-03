max = 0
current = 0
largest_nums = []

file = open('day1.txt', 'r')
lines = file.readlines()

# PT TWO
totals = []

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
        totals.append(current)
        current = 0
totals.append(current)
    

print('Part one: ' + str(max))

totals.sort(reverse=True)
print(str(totals[0]) + ' ' + str(totals[1]) + ' ' + str(totals[2]))
sum_maxes = sum(totals[0:3])
print('Part two: ' + str(sum_maxes))

















# THE LONG WAY...

# for line in lines:
#     line=line.strip()
#     if line == '':
#         if current > max:
#             temp = max2
#             max2 =max
#             max = current
#             max3 = temp
#         else:
#             if current > max2:
#                 max3=max2
#                 max2=current
#             else:
#                 if current > max3:
#                     max3 = current

#             current = 0
#     else:
#         current += int(line)

# print(str(max1) + ' ' + str(max2) + ' ' + str(max3))