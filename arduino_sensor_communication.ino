#include <ModbusMaster.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// WiFi credentials
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// Laravel API endpoint
const char* apiUrl = "http://your-laravel-app.com/api/sensor-readings/realtime";

// Pin definitions
#define MAX485_DE 4
#define MAX485_RE 4
#define RX_PIN 16
#define TX_PIN 17

// Sensor settings
#define NPK_SLAVE_ID 1
#define NPK_BAUD 9600
#define PH_BAUD 4800
#define READ_INTERVAL 3000  // Reduced to 3 seconds for more frequent updates

// NPK Modbus
ModbusMaster node;

// pH Modbus raw
byte ph_cmd[] = {0x01, 0x03, 0x00, 0x00, 0x00, 0x01, 0x84, 0x0A};
byte ph_response[11];

// Sample storage
#define SAMPLE_COUNT 3  // Reduced sample count for faster response
float phSamples[SAMPLE_COUNT];
float nSamples[SAMPLE_COUNT];
float pSamples[SAMPLE_COUNT];
float kSamples[SAMPLE_COUNT];
int sampleIndex = 0;
bool samplesFilled = false;

// Authentication token (you'll need to get this from your Laravel app)
String authToken = "YOUR_AUTH_TOKEN_HERE";

// Connection status
bool wifiConnected = false;
unsigned long lastDataSent = 0;

void preTransmission() {
  digitalWrite(MAX485_RE, HIGH);
  digitalWrite(MAX485_DE, HIGH);
  delay(1);
}

void postTransmission() {
  delay(1);
  digitalWrite(MAX485_RE, LOW);
  digitalWrite(MAX485_DE, LOW);
}

void setup() {
  Serial.begin(9600);
  pinMode(MAX485_RE, OUTPUT);
  pinMode(MAX485_DE, OUTPUT);
  digitalWrite(MAX485_RE, LOW);
  digitalWrite(MAX485_DE, LOW);

  Serial.println("🔧 Initializing sensors...");
  initNPK();
  
  // Connect to WiFi
  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  wifiConnected = true;
  Serial.println();
  Serial.println("✅ WiFi connected!");
  Serial.print("IP Address: ");
  Serial.println(WiFi.localIP());
  Serial.println("📡 Ready to send data to Laravel app...");
}

void loop() {
  // Check WiFi connection
  if (WiFi.status() != WL_CONNECTED) {
    wifiConnected = false;
    Serial.println("❌ WiFi disconnected. Reconnecting...");
    WiFi.reconnect();
    delay(5000);
    return;
  } else if (!wifiConnected) {
    wifiConnected = true;
    Serial.println("✅ WiFi reconnected!");
  }

  float N, P, K;
  if (readNPK(N, P, K)) {
    nSamples[sampleIndex] = N;
    pSamples[sampleIndex] = P;
    kSamples[sampleIndex] = K;
    Serial.println("N: " + String(N) + "mg/kg");
    Serial.println("P: " + String(P) + "mg/kg");
    Serial.println("K: " + String(K) + "mg/kg");
  } else {
    Serial.println("❌ Failed to read NPK sensor");
  }

  float pH = readPH();
  if (pH > 0) {
    phSamples[sampleIndex] = pH;
    Serial.print("pH level: ");
    Serial.print(pH, 1);
    Serial.print(" → ");
    Serial.println(getPHInterpretation(pH));
  }

  sampleIndex++;
  if (sampleIndex >= SAMPLE_COUNT) {
    sampleIndex = 0;
    samplesFilled = true;
  }

  if (samplesFilled) {
    float avgPH = median(phSamples);
    float avgN = average(nSamples);
    float avgP = average(pSamples);
    float avgK = average(kSamples);

    Serial.println("✅ Final averaged values:");
    Serial.println("N: " + String(avgN, 1) + "mg/kg");
    Serial.println("P: " + String(avgP, 1) + "mg/kg");
    Serial.println("K: " + String(avgK, 1) + "mg/kg");
    Serial.print("✅ Final averaged pH level: ");
    Serial.print(avgPH, 2);
    Serial.print(" → ");
    Serial.println(getPHInterpretation(avgPH));

    // Send data to Laravel application
    if (sendDataToLaravel(avgN, avgP, avgK, avgPH)) {
      Serial.println("✅ Data sent successfully to web app");
      lastDataSent = millis();
    } else {
      Serial.println("❌ Failed to send data to web app");
    }

    samplesFilled = false;
  }

  delay(READ_INTERVAL);
}

void initNPK() {
  Serial2.end();
  delay(200);
  Serial2.begin(NPK_BAUD, SERIAL_8N1, RX_PIN, TX_PIN);
  node.begin(NPK_SLAVE_ID, Serial2);
  node.preTransmission(preTransmission);
  node.postTransmission(postTransmission);
  delay(200);
}

bool readNPK(float &N, float &P, float &K) {
  Serial2.end();
  delay(100);
  Serial2.begin(NPK_BAUD, SERIAL_8N1, RX_PIN, TX_PIN);
  node.begin(NPK_SLAVE_ID, Serial2);
  node.preTransmission(preTransmission);
  node.postTransmission(postTransmission);
  delay(100);

  uint8_t result = node.readHoldingRegisters(0x001E, 3);
  if (result == node.ku8MBSuccess) {
    N = node.getResponseBuffer(0);
    P = node.getResponseBuffer(1);
    K = node.getResponseBuffer(2);
    return true;
  }
  return false;
}

float readPH() {
  Serial2.end();
  delay(100);
  Serial2.begin(PH_BAUD, SERIAL_8N1, RX_PIN, TX_PIN);
  delay(100);

  digitalWrite(MAX485_RE, HIGH);
  digitalWrite(MAX485_DE, HIGH);
  delay(10);
  Serial2.write(ph_cmd, sizeof(ph_cmd));
  Serial2.flush();
  digitalWrite(MAX485_RE, LOW);
  digitalWrite(MAX485_DE, LOW);
  delay(100);

  int i = 0;
  while (Serial2.available() && i < 11) {
    ph_response[i] = Serial2.read();
    i++;
  }

  if (i >= 5) {
    int ph_raw = ph_response[3] << 8 | ph_response[4];
    float pH = ph_raw / 10.0;
    if (pH < 3.0 || pH > 10.0) {
      Serial.println("⚠️ Invalid pH reading — sensor may not be in soil.");
      return -1;
    }
    return pH;
  }
  Serial.println("❌ Failed to read pH sensor");
  return -1;
}

String getPHInterpretation(float pH) {
  if (pH < 5.5)
    return "Strongly acidic soil";
  else if (pH < 6.5)
    return "Slightly acidic soil";
  else if (pH <= 7.5)
    return "Neutral soil";
  else if (pH <= 8.5)
    return "Slightly alkaline soil";
  else
    return "Strongly alkaline soil";
}

float average(float *arr) {
  float sum = 0;
  for (int i = 0; i < SAMPLE_COUNT; i++) {
    sum += arr[i];
  }
  return sum / SAMPLE_COUNT;
}

float median(float *arr) {
  float temp[SAMPLE_COUNT];
  memcpy(temp, arr, sizeof(temp));
  for (int i = 0; i < SAMPLE_COUNT - 1; i++) {
    for (int j = i + 1; j < SAMPLE_COUNT; j++) {
      if (temp[i] > temp[j]) {
        float t = temp[i];
        temp[i] = temp[j];
        temp[j] = t;
      }
    }
  }
  if (SAMPLE_COUNT % 2 == 1)
    return temp[SAMPLE_COUNT / 2];
  else
    return (temp[SAMPLE_COUNT / 2 - 1] + temp[SAMPLE_COUNT / 2]) / 2.0;
}

bool sendDataToLaravel(float nitrogen, float phosphorus, float potassium, float ph_level) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(apiUrl);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Authorization", "Bearer " + authToken);
    http.addHeader("Accept", "application/json");

    // Create JSON payload
    StaticJsonDocument<512> doc;
    doc["farm_id"] = 1; // Change this to your actual farm ID
    doc["nitrogen"] = nitrogen;
    doc["phosphorus"] = phosphorus;
    doc["potassium"] = potassium;
    doc["ph_level"] = ph_level;
    doc["soil_temperature"] = 25.0; // Add temperature sensor if available
    doc["soil_moisture"] = 60.0; // Add moisture sensor if available
    doc["latitude"] = 14.6091; // Replace with actual GPS coordinates
    doc["longitude"] = 121.0223; // Replace with actual GPS coordinates
    doc["collection_duration"] = 30;
    doc["session_id"] = "arduino_live_" + String(millis());
    doc["notes"] = "Live Arduino sensor reading";

    String jsonString;
    serializeJson(doc, jsonString);

    Serial.println("📤 Sending live data to Laravel...");
    Serial.println("JSON: " + jsonString);

    int httpResponseCode = http.POST(jsonString);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("✅ HTTP Response code: " + String(httpResponseCode));
      Serial.println("Response: " + response);
      http.end();
      return true;
    } else {
      Serial.println("❌ Error on sending POST: " + String(httpResponseCode));
      http.end();
      return false;
    }
  } else {
    Serial.println("❌ WiFi not connected");
    return false;
  }
} 