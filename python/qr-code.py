# Step 1: Install the qrcode and pillow library using 
# pip3 install "qrcode[pil]"

import qrcode

qr = qrcode.QRCode(
    version=1,
    box_size=5,
    border=10,
)

data = 'https://mail.google.com'

qr.add_data(data)
qr.make(fit=True)

img = qr.make_image()
img.save('qr-gmail.png')




# # Example: +1 (555) 123-4567 becomes tel:+15551234567
# phone_number_uri = "tel:+13392357757" 

# # Generate the QR code
# img = qrcode.make(phone_number_uri)

# # Save the QR code as an image file
# img.save("phone_call_qrcode.png")
# print("QR code saved as phone_call_qrcode.png")


# Example: make an event
import datetime as dt

# --- 1. Define your event details ---
event_summary = "Tech support"
event_location = "Barstow Village"
event_description = "Tech support for unc"

# Dates and times must be in UTC or include a timezone, in 'YYYYMMDDTHHMMSS' format.
# For simplicity, we use naive datetime objects here. You should use a library like 
# `python-dateutil` or `zoneinfo` for proper timezone handling in a real application.
event_start = dt.datetime(2026, 3, 18, 15, 0, 0) 
event_end = dt.datetime(2026, 3, 18, 17, 0, 0)

# Format the datetime objects to the required string format
start_time_str = event_start.strftime("%Y%m%dT%H%M%S")
end_time_str = event_end.strftime("%Y%m%dT%H%M%S")

# --- 2. Construct the iCalendar string payload ---
ical_data = f"""BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
SUMMARY:{event_summary}
DTSTART:{start_time_str}
DTEND:{end_time_str}
LOCATION:{event_location}
DESCRIPTION:{event_description}
END:VEVENT
END:VCALENDAR"""

# --- 3. Generate the QR Code ---
qr = qrcode.QRCode(
    version=1,
    error_correction=qrcode.constants.ERROR_CORRECT_H,
    box_size=10,
    border=4,
)
qr.add_data(ical_data)
qr.make(fit=True)

img = qr.make_image(fill_color="black", back_color="white")

# --- 4. Save the QR Code image ---
img.save("calendar_event_qr.png")

print("QR code for the calendar event was generated and saved as 'calendar_event_qr.png'")
