const int ledPin = 13;

void setup() {
  Serial.begin(115200);
  pinMode(ledPin, OUTPUT);
  
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
    
    Serial.println("ATAP 2");
    delay(100);
    while (Serial.available() > 0) {
      Serial.write(Serial.read());
    }
    Serial.println();
    
    Serial.println("ATWR");
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
    
  }
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
    
    Serial.println("ATAP");
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
    Serial.println("Hello");
  }
  
  digitalWrite(ledPin, HIGH);
  delay(1000);
  digitalWrite(ledPin, LOW);
  delay(2000);
  
}
