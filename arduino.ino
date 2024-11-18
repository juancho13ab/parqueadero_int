#include <Servo.h>

const int sensorEntradaPin = 2;  // Pin para el sensor de entrada
const int sensorSalidaPin = 3;   // Pin para el sensor de salida
const int servoEntradaPin = 9;   // Pin para el servomotor de entrada
const int servoSalidaPin = 10;   // Pin para el servomotor de salida

Servo servoEntrada;
Servo servoSalida;

void setup() {
  Serial.begin(9600);  // Inicia la comunicación serial con Python
  pinMode(sensorEntradaPin, INPUT);  // Configura el sensor de entrada como entrada
  pinMode(sensorSalidaPin, INPUT);   // Configura el sensor de salida como entrada
  
  servoEntrada.attach(servoEntradaPin);  // Conecta el servomotor de entrada
  servoSalida.attach(servoSalidaPin);    // Conecta el servomotor de salida
  
  servoEntrada.write(0);  // Inicializa el servomotor de entrada cerrado
  servoSalida.write(0);   // Inicializa el servomotor de salida cerrado
}

void loop() {
  int estadoSensorEntrada = digitalRead(sensorEntradaPin);  // Lee el estado del sensor de entrada
  int estadoSensorSalida = digitalRead(sensorSalidaPin);    // Lee el estado del sensor de salida

  // Si se detecta movimiento en el sensor de entrada
  if (estadoSensorEntrada == HIGH) {
    Serial.println("Entrada");  // Envia la señal de entrada al script de Python
    delay(1000);  // Espera 1 segundo antes de leer nuevamente el sensor (anti rebote)
  }

  // Si se detecta movimiento en el sensor de salida
  if (estadoSensorSalida == HIGH) {
    Serial.println("Salida");  // Envia la señal de salida al script de Python
    delay(1000);  // Espera 1 segundo antes de leer nuevamente el sensor (anti rebote)
  }

  // Lee la respuesta de Python y actúa en consecuencia
  if (Serial.available() > 0) {
    String command = Serial.readStringUntil('\n');  // Lee el comando de Python hasta el salto de línea
    if (command == "AbrirEntrada") {  // Si la respuesta es 'AbrirEntrada'
      servoEntrada.write(90);  // Abre el servomotor de entrada
      delay(2000);  // Espera 2 segundos
      servoEntrada.write(0);  // Cierra el servomotor de entrada
    } else if (command == "AbrirSalida") {  // Si la respuesta es 'AbrirSalida'
      servoSalida.write(90);  // Abre el servomotor de salida
      delay(2000);  // Espera 2 segundos
      servoSalida.write(0);  // Cierra el servomotor de salida
    }
  }
}