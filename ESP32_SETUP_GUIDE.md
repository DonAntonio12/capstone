# 🔌 ESP32-WROOM Sensor Integration Guide

## Overview
This guide will help you connect your NPK and pH sensors to your Laravel soil monitoring system using ESP32-WROOM.

## 📋 Prerequisites

### Hardware Requirements
- ESP32-WROOM development board
- NPK Sensor (Modbus RTU)
- pH Sensor (Modbus RTU)
- MAX485 RS485 to TTL converter
- Jumper wires
- Breadboard

### Software Requirements
- Arduino IDE
- Required Libraries:
  - `ModbusMaster` by Doc Walker
  - `WiFi` (built-in)
  - `HTTPClient` (built-in)
  - `ArduinoJson` by Benoit Blanchon

## 🔧 Setup Instructions

### 1. Install Required Libraries
In Arduino IDE, go to **Tools > Manage Libraries** and install:
- ModbusMaster
- ArduinoJson

### 2. Hardware Connections

#### ESP32-WROOM Pin Connections:
```
MAX485 Module:
- DE (Driver Enable) → GPIO 4
- RE (Receiver Enable) → GPIO 4
- DI (Driver Input) → GPIO 17 (TX)
- RO (Receiver Output) → GPIO 16 (RX)
- VCC → 3.3V
- GND → GND

NPK Sensor:
- A+ → MAX485 A+
- B- → MAX485 B-
- VCC → 12V (external power)
- GND → GND

pH Sensor:
- A+ → MAX485 A+
- B- → MAX485 B-
- VCC → 12V (external power)
- GND → GND
```

### 3. Configure ESP32 Code

1. Open `esp32_sensor_communication.ino` in Arduino IDE
2. Update the following variables:

```cpp
// WiFi credentials - UPDATE THESE
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";

// Laravel API endpoint - UPDATE THIS TO YOUR ACTUAL URL
const char* apiUrl = "http://localhost/capstone/public/api/sensor-readings/realtime";

// Authentication token - UPDATE THIS
String authToken = "YOUR_AUTH_TOKEN_HERE";
```

### 4. Generate Authentication Token

#### Option A: Using Laravel Tinker
```bash
php artisan tinker
```

```php
$user = \App\Models\User::find(1); // Replace with your user ID
$token = $user->createToken('ESP32 Device')->plainTextToken;
echo $token;
```

#### Option B: Using API Endpoint
```bash
curl -X POST http://localhost/capstone/public/api/arduino-token \
  -H "Content-Type: application/json" \
  -d '{
    "device_name": "ESP32 NPK Sensor",
    "user_id": 1
  }'
```

### 5. Update ESP32 Code with Token
Replace `YOUR_AUTH_TOKEN_HERE` in the ESP32 code with the token you generated.

### 6. Upload Code to ESP32
1. Select **ESP32 Dev Module** in Arduino IDE
2. Select the correct port
3. Click **Upload**

## 🔍 Testing the Connection

### 1. Monitor Serial Output
Open Arduino IDE Serial Monitor (115200 baud) to see:
- WiFi connection status
- Sensor readings
- HTTP requests to Laravel
- Response from Laravel

### 2. Check Laravel Application
1. Go to your Laravel application
2. Navigate to **Testing** page
3. Click "🔌 Test ESP32 Connection"
4. You should see real sensor data

### 3. Verify Database
Check if sensor readings are being saved:
```bash
php artisan tinker
\App\Models\SensorReading::latest()->first();
```

## 🛠️ Troubleshooting

### Common Issues:

#### 1. WiFi Connection Failed
- Check WiFi credentials
- Ensure ESP32 is in range
- Try restarting the device

#### 2. Sensor Readings Failed
- Check wiring connections
- Verify sensor power supply
- Check Modbus slave ID (default: 1)

#### 3. HTTP Request Failed
- Check API URL (should be: `http://localhost/capstone/public/api/sensor-readings/realtime`)
- Verify authentication token
- Check Laravel application is running
- Ensure CORS is configured properly

#### 4. pH Sensor Not Working
- Ensure sensor is properly immersed in soil
- Check if readings are within valid range (3.0-10.0)
- Verify sensor calibration

### Debug Commands:

#### Check Sensor Communication:
```cpp
// Add this to your ESP32 code for debugging
Serial.println("Testing NPK sensor...");
if (readNPK(N, P, K)) {
    Serial.println("NPK sensor OK");
} else {
    Serial.println("NPK sensor failed");
}
```

#### Check HTTP Response:
```cpp
// Add this to see detailed HTTP response
if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Response: " + response);
} else {
    Serial.println("HTTP Error: " + http.errorToString(httpResponseCode));
}
```

## 📊 Expected Output

### Serial Monitor Output:
```
🔧 Initializing ESP32 sensors...
Connecting to WiFi...
✅ WiFi connected!
IP Address: 192.168.1.100
📡 Ready to send data to Laravel app...

N: 25.5mg/kg
P: 18.2mg/kg
K: 150.3mg/kg
pH level: 6.8 → Neutral soil

✅ Final averaged values:
N: 24.8mg/kg
P: 17.9mg/kg
K: 149.7mg/kg
✅ Final averaged pH level: 6.75 → Neutral soil

📤 Sending live data to Laravel...
JSON: {"farm_id":1,"nitrogen":24.8,"phosphorus":17.9,"potassium":149.7,"ph_level":6.75,"soil_temperature":25.0,"soil_moisture":60.0,"latitude":14.6091,"longitude":121.0223,"collection_duration":30,"session_id":"esp32_live_123456","notes":"Live ESP32 sensor reading"}
✅ HTTP Response code: 201
Response: {"reading":{"id":123,"farm_id":1,"user_id":1,"nitrogen":"24.80","phosphorus":"17.90","potassium":"149.70","ph_level":"6.75",...},"analysis":{...},"session_id":"esp32_live_123456"}
✅ Data sent successfully to web app
```

## 🔄 Real-time Integration

Once connected, your ESP32 will:
1. Read sensor data every 3 seconds
2. Average 3 samples for accuracy
3. Send data to Laravel via HTTP POST
4. Laravel processes and stores the data
5. Web interface shows real-time updates

## 📱 Mobile Integration

You can also create a mobile app that:
- Connects to the same Laravel API
- Displays real-time sensor data
- Shows historical trends
- Provides notifications for critical readings

## 🔒 Security Considerations

1. **Token Security**: Keep your ESP32 token secure
2. **Network Security**: Use HTTPS in production
3. **Data Validation**: Laravel validates all incoming data
4. **Rate Limiting**: Consider implementing rate limiting for ESP32 requests

## 📈 Next Steps

1. **Add More Sensors**: Temperature, moisture, EC sensors
2. **GPS Integration**: Add GPS module for precise location
3. **Battery Power**: Add solar panel for outdoor deployment
4. **Data Analytics**: Implement advanced ML predictions
5. **Alerts**: Add email/SMS notifications for critical readings

## 🆘 Support

If you encounter issues:
1. Check the troubleshooting section above
2. Verify all connections and configurations
3. Check Laravel logs: `tail -f storage/logs/laravel.log`
4. Monitor ESP32 Serial output for error messages

---

**Happy Farming! 🌱** 