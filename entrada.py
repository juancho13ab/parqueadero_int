import cv2
import pytesseract
from imutils.video import VideoStream
import imutils
import mysql.connector
import time

# Configura la ruta de Tesseract OCR
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def detect_and_read_plate(frame):
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    gray = cv2.bilateralFilter(gray, 11, 17, 17)
    edged = cv2.Canny(gray, 30, 200)
    cnts = cv2.findContours(edged.copy(), cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
    cnts = imutils.grab_contours(cnts)
    cnts = sorted(cnts, key=cv2.contourArea, reverse=True)[:10]

    plate = None
    for c in cnts:
        peri = cv2.arcLength(c, True)
        approx = cv2.approxPolyDP(c, 0.018 * peri, True)
        if len(approx) == 4:
            plate = approx
            break

    if plate is not None:
        x, y, w, h = cv2.boundingRect(plate)
        roi = gray[y:y + h, x:x + w]
        text = pytesseract.image_to_string(roi, config='--psm 8')
        return text.strip()
    return None

def is_plate_registered(license_plate):
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="parking_db"
    )
    cursor = conn.cursor()
    sql = "SELECT COUNT(*) FROM vehicles WHERE licensePlate = %s"
    cursor.execute(sql, (license_plate,))
    result = cursor.fetchone()
    cursor.close()
    conn.close()
    return result[0] > 0

def save_entry_to_db(license_plate):
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="parking_db"
    )
    cursor = conn.cursor()
    sql = "UPDATE vehicles SET entry_time = NOW() WHERE licensePlate = %s"
    cursor.execute(sql, (license_plate,))
    conn.commit()
    cursor.close()
    conn.close()

vs = VideoStream(src=0).start()
time.sleep(2.0)

start_time = time.time()
while True:
    frame = vs.read()
    frame = imutils.resize(frame, width=600)
    plate = detect_and_read_plate(frame)
    if plate and is_plate_registered(plate):
        save_entry_to_db(plate)
        print(f"Placa detectada y entrada registrada: {plate}")
        break
    if time.time() - start_time > 15:
        print("Tiempo de detección agotado.")
        break
    cv2.imshow("Detector de Placas", frame)
    key = cv2.waitKey(1) & 0xFF
    if key == ord("q"):
        break

cv2.destroyAllWindows()
vs.stop()
