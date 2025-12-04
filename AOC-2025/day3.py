file = open('day3.txt', 'r')
lines = file.readlines()

# PARTS 1 AND 2 WITH NUMNUMS

total = 0

for line in lines:
    line = line.strip()
    num = ''
    numNums = 12
    index = -1

    for j in range(1, numNums + 1):
        maxNumNumNums = 0
        for i in range(index+1, len(line) - numNums + j):
            numNumNum = int(line[i])
            if numNumNum > maxNumNumNums:
                maxNumNumNums = numNumNum
                index = i
        num+=str(maxNumNumNums)

    total += int(num)


print(total)

# max_1 = 0
# max_2 = 0
# answer = 0

# max_nums = []

# for line in lines:
#     final_num = []
#     temp_list = []

#     line = line.strip()
#     numbers = [int(x) for x in line]
#     print(numbers)

#     max_1 = numbers[0]
#     max_1_index = 0
#     # FIND MAX AND INDEX
#     for i in range(1, len(numbers)):
#         if numbers[i] > max_1:
#             if i != (len(numbers)-1):
#                 max_1 = numbers[i]
#                 max_1_index = i
#             else:
#                 break
#     print(max_1)

#     chopped = numbers[max_1_index+1:]
#     print(chopped)

#     max_2 = chopped[0]
#     # FIND LARGEST NUM POSSIBLE
#     for i in range(len(chopped)):
#         if chopped[i] > max_2:
#             max_2 = chopped[i]
#             max_2_index = i
#     print(max_2)
    
#     final_num.append(str(max_1))
#     final_num.append(str(max_2))

#     final_num = ''.join(final_num)
#     print(final_num)
#     final_num = int(final_num)

#     max_nums.append(final_num)
#     print(final_num)


# for num in max_nums:
#     answer += num

# print("Part 1: " + str(answer))