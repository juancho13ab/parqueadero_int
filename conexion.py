import serial
import time
import subprocess

# Configuración de la conexión serial
arduino = serial.Serial('COM3', 9600)  # Cambia 'COM3' al puerto que estás usando
time.sleep(2)

def verificar_entrada():
    resultado = subprocess.run(['python', 'entrada.py'], capture_output=True, text=True)
    return "AbrirEntrada" if "Placa detectada y entrada registrada:" in resultado.stdout else "AccesoDenegado"

def verificar_salida():
    resultado = subprocess.run(['python', 'salida.py'], capture_output=True, text=True)
    return "AbrirSalida" if "Placa detectada y salida registrada:" in resultado.stdout else "AccesoDenegado"

# Variable para asegurar que solo se ejecuta una vez por cada detección
entrada_detectada = False
salida_detectada = False

while True:
    if arduino.in_waiting > 0:
        mensaje = arduino.readline().decode().strip()
        if mensaje == "Entrada" and not entrada_detectada:
            respuesta = verificar_entrada()
            arduino.write((respuesta + '\n').encode())
            print("Respuesta enviada al Arduino:", respuesta)
            entrada_detectada = True  # Evita que se ejecute nuevamente hasta que se reinicie el estado

        elif mensaje == "Salida" and not salida_detectada:
            respuesta = verificar_salida()
            arduino.write((respuesta + '\n').encode())
            print("Respuesta enviada al Arduino:", respuesta)
            salida_detectada = True  # Evita que se ejecute nuevamente hasta que se reinicie el estado

    time.sleep(1)