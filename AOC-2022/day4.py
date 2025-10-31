file = open('day4.txt', 'r')
lines = file.readlines()

fully_contained = 0

overlaps = 0

for line in lines:
    e1, e2 = line.strip().split(",")
    print(e1)
    print(e2)
    e1_range = e1.split("-")
    # print(e1_range)
    e2_range = e2.split("-")
    # print(e2_range)

    e1_start = int(e1_range[0])
    # print(e1_start)
    e1_end = int(e1_range[-1])
    # print(e1_end)
    e2_start = int(e2_range[0])
    # print(e2_start)
    e2_end = int(e2_range[-1])
    # print(e2_end)

    if (e1_start >= e2_start and e1_end <= e2_end) or (e2_start >= e1_start and e2_end <= e1_end):
        fully_contained += 1
        print("CONTAINED")

    if not (e1_end < e2_start or e2_end < e1_start):
        overlaps += 1
        print("OVERLAP")
    
print("Fully contained: " + str(fully_contained))
print("Overlaps: " + str(overlaps))
    

