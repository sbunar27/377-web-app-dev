import tkinter as tk

def on_button_click():
    ourMessage = "Hello " + entry1.get() + " " + entry2.get() + "! \n"
    messageVar = tk.Message(root, text=ourMessage)
    messageVar.config(bg="lightgreen")
    messageVar.grid(row=5, column=0, columnspan=2)

root = tk.Tk()
root.title("Python Fun Friday!")

# Widgets are added here
tk.Label(root, text="First Name").grid(row=0, column=0)
tk.Label(root, text="Last Name").grid(row=1, column=0)

entry1 = tk.Entry(root)
entry2 = tk.Entry(root)

entry1.grid(row=0, column=1)
entry2.grid(row=1, column=1)

var1 = tk.IntVar()
var2 = tk.IntVar()

tk.Checkbutton(root, text="Fun", variable=var1).grid(row=2, sticky=tk.W)
tk.Checkbutton(root, text="Friday", variable=var2).grid(row=3, sticky=tk.W)

button = tk.Button(root, text="Say Hello!", width=25, command=on_button_click)
button.grid(row=4, column=0, columnspan=2)

button = tk.Button(root, text="Quit", width=25, command=root.destroy)
button.grid(row=6, column=0, columnspan=2)

root.mainloop()