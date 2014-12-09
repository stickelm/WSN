const int ledPin = 13; // the pin that the LED is attached to

void setup() {
  // initialize serial communication:
  Serial.begin(115200);
  // initialize the LED pin as an output:
  pinMode(ledPin, OUTPUT);
}

void loop() {
  Serial.println('H');
  digitalWrite(ledPin, HIGH);
  delay(1000);
  Serial.println('L');
  digitalWrite(ledPin, LOW);
  delay(2000);
}
  
