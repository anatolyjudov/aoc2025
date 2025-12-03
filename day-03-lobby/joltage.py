def find(line:str, ignore:int = 0):
    m = max(list(line[:-ignore]) if ignore else line)
    return str(m), line[line.index(m) + 1:]

def power(line:str, length:int = 2):
    power, rest = find(line, length - 1)
    for i in range(length - 2, -1, -1):
        digit, rest = find(rest, i)
        power += digit
    return int(power)

star1, star2 = 0, 0

for battery in open(0).read().splitlines():
    star1 += power(battery, 2)
    star2 += power(battery, 12)

print(f'Star1: {star1}\nStar2: {star2}')