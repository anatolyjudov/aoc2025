star1, star2 = 0, 0

file = open(0).read().splitlines()
ops = file[len(file) - 1].split()

numbers = list(map(lambda x: list(map(int, x.split())), file[:-1]))
for i, nums in enumerate(zip(*numbers)):
	res = 0
	for n in nums:
		res = n if res == 0 else res + n if ops[i] == '+' else res * n
	star1 += res

op, res = 0, 0
for n in map(lambda n: ''.join(n), zip(*map(lambda s: list(s + ' '), file[:-1]))):
	if n.strip() == '':
		star2 += res
		res = 0
		op = op + 1
	else:
		res = int(n) if res == 0 else res + int(n) if ops[op] == '+' else res * int(n)

print(f'Star 1: {star1}\nStar2: {star2}')