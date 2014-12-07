const int ledPin = 13;

void setup() {
  Serial.begin(115200);
  pinMode(ledPin, OUTPUT);
}

void loop() {
  Serial.println("------------------");
  delay(1200);
  Serial.print("+++");
  delay(1200);
  bool bOK = false;
  while (Serial.available() > 0) {
    Serial.write(Serial.read());
    bOK = true;
  }
  
  if(bOK)
  {
    Serial.println();
    
    Serial.println("ATSH");
    delay(100);
    while (Serial.available() > 0) {
      Serial.write(Serial.read());
    }
    Serial.println();
    
    Serial.println("ATSL");
    delay(100);
    while (Serial.available() > 0) {
      Serial.write(Serial.read());
    }
    Serial.println();
    
    Serial.println("ATCN");
    delay(100);
    while (Serial.available() > 0) {
      Serial.write(Serial.read());
    }
    Serial.println();
    
  } else {
    
    Serial.println("ATCN");
    delay(100);
    while (Serial.available() > 0) {
      Serial.write(Serial.read());
    }
    
    Serial.println("Hello");
    
  }
  
  digitalWrite(ledPin, HIGH);
  delay(1000);
  digitalWrite(ledPin, LOW);
  delay(2000);
  
}
