# grid = open("day4.txt").read().splitlines()
file = open('day4.txt', 'r')
lines = file.readlines()
grid = []

for line in lines:
    row = [x for x in line.strip()]
    grid.append(row)

H, W = len(grid), len(grid[0])

# PART 1
ans = 0
removable = 0
for r in range(len(grid)):
    for c in range(len(grid[0])):
        if grid[r][c] == '@':
            n = 0
            # count the 8 neighbors
            if r>0 and grid[r-1][c]  != '.': 
                n += 1      # up
            if r<H-1 and grid[r+1][c] != '.': 
                n += 1      # down
            if c>0 and grid[r][c-1]   != '.': 
                n += 1      # left
            if c<W-1 and grid[r][c+1] != '.': 
                n += 1      # right
            if r>0 and c>0 and grid[r-1][c-1] != '.': 
                n += 1        # up-left
            if r>0 and c<W-1 and grid[r-1][c+1] != '.': 
                n += 1      # up-right
            if r<H-1 and c>0 and grid[r+1][c-1] != '.': 
                n += 1      # down-left
            if r<H-1 and c<W-1 and grid[r+1][c+1] != '.': 
                n += 1    # down-right

            if n < 4:
                ans += 1

print("Part 1: " + str(ans))

# PART 2
total_removed = 0

while True:
    to_remove = []
    found = True
    for r in range(len(grid)):
        for c in range(len(grid[0])):
            if grid[r][c] == '@':
                n = 0
                # count the 8 neighbors
                if r>0 and grid[r-1][c]  != '.': 
                    n += 1      # up
                if r<H-1 and grid[r+1][c] != '.': 
                    n += 1      # down
                if c>0 and grid[r][c-1]   != '.': 
                    n += 1      # left
                if c<W-1 and grid[r][c+1] != '.': 
                    n += 1      # right
                if r>0 and c>0 and grid[r-1][c-1] != '.': 
                    n += 1        # up-left
                if r>0 and c<W-1 and grid[r-1][c+1] != '.': 
                    n += 1      # up-right
                if r<H-1 and c>0 and grid[r+1][c-1] != '.': 
                    n += 1      # down-left
                if r<H-1 and c<W-1 and grid[r+1][c+1] != '.': 
                    n += 1    # down-right

                if n < 4:
                    ans += 1
                    grid[r][c] = "x"
                    to_remove.append((r, c))
        
    if to_remove == []:
        break

    for r, c in to_remove:
        grid[r][c] = '.'

    total_removed += len(to_remove)

print("Part 2: " + str(total_removed))