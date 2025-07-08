'''
# task 1
# initializing array
asci = []
for i in range(0,128,1):
    asci.append(0)

# opening file
f = open("gm.txt", "r+")
s = f.read()
f.close()

#calculator characters

for i in range(0, len(s)):
    n = ord(s[i])
    asci[n] = asci[n]+1

# printing end result
for i in range (32,128,1):
    if asci[i] > 0:
        print(chr(i)," --> ",asci[i])

# task 1 end
'''


# task 2

# opening file
f = open("gm.txt", "r+")
s = f.read()
f.close()
# extracting text and closing file

# spliting all words from text
words = s.split()

# unique word array and their freq: array used in text
word = []
freq = []

# calculating word + their freq
for i in range(0,len(words),1):
    if words[i] in word:
        indexOfWord = word.index(words[i])
        freq[indexOfWord] = freq[indexOfWord]+1
    else:
        word.append(words[i])
        freq.append(1)

# printing end result
for i in range(0,len(word)):
    print(word[i]," --> ",freq[i])

# task 2 end