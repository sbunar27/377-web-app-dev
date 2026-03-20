import tkinter as tk
import random

def on_button_click():
    password = []
    specials = "!@#$%^&*()-=_+{}[]|;:'<>,./?~"

    # take care of requirements before the loop
    if includeNum.get() == 1:
        password.append(str(random.randint(0, 9)))
    if includeUpper.get() == 1:
        password.append(chr(ord("A") + random.randint(0, 25)))
    if includeLower.get() == 1:
        password.append(chr(ord("a") + random.randint(0, 25)))
    if includeSpecial.get() == 1:
        password.append(random.choice(specials))

    while len(password) < int(length.get()):
        path = random.randint(1,4)

        if includeNum.get() == 1 and path == 1:
            password.append(str(random.randint(0, 9)))
        if includeUpper.get() == 1 and path == 2:
            password.append(chr(ord("A") + random.randint(0, 25)))
        if includeLower.get() == 1 and path == 3:
            password.append(chr(ord("a") + random.randint(0, 25)))
        if includeSpecial.get() == 1 and path == 4:
            password.append(random.choice(specials))

    random.shuffle(password)
    password = "".join(password)
    passwordVar.set(password)

root = tk.Tk()
root.title("Python Fun Password Generator!")

passwordVar = tk.StringVar(value="Your password will appear here!")
passwordLabel = tk.Label(root, textvariable=passwordVar)
passwordLabel.grid(row=6, column=0, columnspan=2)
length = tk.IntVar(value=8)
includeNum = tk.IntVar(value=0)
includeUpper = tk.IntVar(value=0)
includeLower = tk.IntVar(value=0)
includeSpecial = tk.IntVar(value=0)

tk.Label(root, text="Length").grid(row=0, column=0)
length = tk.Entry(root)
length.grid(row=0, column=1)
tk.Checkbutton(root, text="Include Number?", variable=includeNum).grid(row=1, sticky=tk.W)
tk.Checkbutton(root, text="Include Uppercase Character?", variable=includeUpper).grid(row=2, sticky=tk.W)
tk.Checkbutton(root, text="Include Lowercase Character?", variable=includeLower).grid(row=3, sticky=tk.W)
tk.Checkbutton(root, text="Include Special Character?", variable=includeSpecial).grid(row=4, sticky=tk.W)


button = tk.Button(root, text="Generate Password!", width=25, command=on_button_click)
button.grid(row=5, column=0, columnspan=2)

root.mainloop()