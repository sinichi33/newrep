# voucher code generator
# hendy@mataharimall.com
# usage: $python voucher.py length prefix iteration output_file_name
import random, string, os, sys

if len(sys.argv) < 5 :
	print ("usage: python voucher.py length prefix iteration output_file_name")
	sys.exit()

chars = (string.ascii_uppercase.replace('I','').replace('O', '').replace('1', '').replace('0','') + string.digits[1:]+ string.digits[1:]+ string.digits[1:]+ string.digits[1:])

random.seed = (os.urandom(1024))
# (1)
# ---> Change this to the number of digit (exclude, the prefix)
#
length = int(sys.argv[1])

# ---> prefix for the code
#
prefix = str(sys.argv[2])
# (3)
# ---> number of voucher to generate
#
iteration = int(sys.argv[3])
results = []
storage = []
print ("generating ... ")
# initialize empty storage array
i = 0
for i in range(0,100):
    storage.append([])

i = 1
while i <= iteration:
    # get random string
    randstr = ''.join(random.choice(chars) for _ in range(length))
    redo = False
        
    # get first char of the string
    storeindex = ord(randstr[0])

    # if we already have strings stored
    if len(storage[storeindex]) > 0:
        # iterate to check duplicates
        storelist = storage[storeindex]
        for s in storelist:
            if s == randstr:
                redo = True
                
        if redo == False:
            storage[storeindex].append(randstr)
            results.append(randstr)
            i += 1
        else:
            print ("redo %d %r %r ") % (storeindex, s , randstr)
                
    # else append
    else:
        storage[storeindex].append(randstr)
        results.append(randstr)
        i += 1

f = open(str(sys.argv[4]), 'w')
i = int(1)
for result in results:
    s = "%s%s" % (prefix,result)
    f.write(str(s))
    f.write("\n")    
    i += 1
    print ("%r \n" % i)
f.close()
