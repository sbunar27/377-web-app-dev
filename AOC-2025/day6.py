file = open('day6.txt', 'r')
lines = file.readlines()

lines = [line.strip().split() for line in lines]

total = 0

# PART 1

for i in range(len(lines[0])):
    problem = []
    for line in lines:
        problem.append(line[i])

    operator = problem.pop()

    problem = operator.join(problem)
    
    total += eval(problem)

print(total)

# PART 2 - way too overcomplicated :(

lines = open("day6.txt", 'r')
rows = [line.strip("\n") for line in lines]

H = len(rows)
W = max(len(r) for r in rows)

# google: makes all rows have equal width
rows = [r.ljust(W) for r in rows]

# take out the columns as strings
columns = []
for c in range(W):
    column = ""
    for r in range(H):
        column += rows[r][c]
    columns.append(column)

problems = []
current = []

# sets up all the problems
for col in columns:
    if col.strip() == "":
        if current:
            problems.append(current)
            current = []
    else:
        current.append(col)

if current:
    problems.append(current)


def solve(block):
    # find operator at bottom of column
    op = None
    for col in reversed(block):
        last = col.strip()[-1]
        if last in "+*":
            op = last
            break

    nums = []
    for col in block:
        s = col.strip()
        # google: endswith checks if s ends with an operator
        if s.endswith(op):
            digits = s[:-1].strip()
        else:
            digits = s.strip()

        # checks to see if it ends with a number
        if digits.isdigit():
            nums.append(int(digits))

    if op == "+":
        return sum(nums)

    product = 1
    for n in nums:
        product *= n
    return product

# found on google, gets it done quicker than a full loop <3
total = sum(solve(problem) for problem in problems)
print(total)