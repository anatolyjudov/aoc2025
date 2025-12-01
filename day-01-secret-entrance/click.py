star1, star2 = 0, 0
pos = 50

with open("input.txt", "r") as file:
    for line in file:
        was0 = pos == 0
        pos += (1 if line[0] == 'R' else -1) * int(line[1:])
        if pos > 99:
            star2 += pos // 100
            pos = pos % 100
        elif pos <= 0:
            star2 += -pos // 100 + (0 if was0 else 1)
            pos = (100 - -pos % 100) % 100
        star1 += (1 if pos == 0 else 0)

print (f"Star 1: {star1}\nStar 2: {star2}")