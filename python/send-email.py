email_username = None
email_password = open('password.txt' , 'r').readlines()[0].strip()

import smtplib

# import email modules we need
from email.message import EmailMessage

# send via google
with smtplib.SMTP_SSL('smtp.gmail.com', 465) as server:
    print('Authenticating...')
    server.login(email_username, email_password)
    print('Sending...')

    lines = open('send-email-data.csv', 'r').readlines()

    for line in lines:
        pieces = line.strip().split(',')

        to = pieces[0]
        first_name = pieces[1]
        first_name = pieces[2]
        order_num = pieces[3]


        # create text/plain message
        msg = EmailMessage()
        
        msg.set_content('Dear' + first_name + ',\nOrder #' + order_num + ' is on its way!')
        msg['Subject'] = 'Order #' + order_num + ' Shipped'
        msg['From'] = email_username
        msg['To'] = to

    server.send_message(msg)
    server.quit()
    print('Message sent!')
