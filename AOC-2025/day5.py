file = open('day5.txt', 'r')
lines = file.readlines()
lines = [line.strip() for line in lines]

# split into ranges and ingredient ids
blank_index = lines.index('')
range_lines = lines[:blank_index]
id_lines = lines[blank_index + 1:]

ranges = []
points = []
for line in range_lines:
    line = line.strip()
    parts = line.split("-")
    a = int(parts[0])
    b = int(parts[1])
    ranges.append((a, b))
    points.append([a, "s"])
    points.append([b, "e"])

points.sort()

# count fresh ids
fresh_count = 0
for line in id_lines:
    x = int(line)
    for a, b in ranges:
        if x >= a and x <= b:
            fresh_count += 1
            break

print("Fresh ingredient count: " + str(fresh_count))


# PART 2

# google! asked it why this part wasn't working so it gave 
# me something else to use
points.sort(key=lambda x: (x[0], 0 if x[1] == "s" else 1))

active = 0
start = None
count = 0

i = 0
while i < len(points):
    pos = points[i][0]
    kind = points[i][1]

    if kind == "s":
        if active == 0:
            start = pos
        active += 1

    else:  # kind == "e"
        active -= 1
        if active == 0:
            end = pos
            count += end - start + 1

    i += 1

print("Possible ranges: " + str(count))