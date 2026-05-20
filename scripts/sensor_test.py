#!/usr/bin/env python3
"""
ESP32 Sensor Test Script
Communicates with ESP32 via USB Serial, collects sensor data,
averages readings, and sends results to Laravel API.
"""

import serial
import time
import json
import requests
import argparse
import sys
from datetime import datetime
from typing import Dict, List, Optional
import statistics
import serial.tools.list_ports

class SensorTest:
    def __init__(self, duration: int, port: str = None):
        self.duration = duration
        self.port = port or self._find_esp32_port()
        self.serial_connection = None
        self.readings = []
        self.api_base_url = "http://127.0.0.1:8000/api"
        
    def _find_esp32_port(self) -> str:
        """Find ESP32 port automatically (Windows)"""
        # Use pyserial to list only real serial ports
        ports = [port.device for port in serial.tools.list_ports.comports()]
        if not ports:
            raise Exception("No serial ports found. Please connect ESP32.")
        
        # Try to find ESP32 by testing each port
        for port in ports:
            try:
                with serial.Serial(port, 115200, timeout=2) as ser:
                    ser.write(b"TEST\n")
                    response = ser.readline().decode().strip()
                    if "ESP32" in response or "NPK" in response:
                        print(f"Found ESP32 on port: {port}")
                        return port
            except Exception:
                continue
        
        # If no ESP32 found, use first available port
        print(f"ESP32 not found, using first available port: {ports[0]}")
        return ports[0]
    
    def connect_to_esp32(self) -> bool:
        """Connect to ESP32 via USB Serial"""
        try:
            print(f"Connecting to ESP32 on port: {self.port}")
            self.serial_connection = serial.Serial(
                port=self.port,
                baudrate=115200,
                timeout=5,
                write_timeout=5
            )
            
            # Wait for connection to stabilize
            time.sleep(2)
            
            # Send test command
            self.serial_connection.write(b"START_TEST\n")
            response = self.serial_connection.readline().decode().strip()
            
            if "READY" in response or "OK" in response:
                print("ESP32 connected successfully!")
                return True
            else:
                print(f"Unexpected response from ESP32: {response}")
                # Don't fail, just continue with fallback data
                return True
                
        except Exception as e:
            print(f"Failed to connect to ESP32: {e}")
            print("Continuing with fallback data...")
            return True  # Return True to continue with fallback data
    
    def collect_readings(self) -> List[Dict]:
        """Collect sensor readings for specified duration"""
        print(f"Collecting readings for {self.duration} seconds...")
        
        start_time = time.time()
        readings = []
        
        # If no serial connection, DO NOT generate fake data for testing page
        if not self.serial_connection:
            print("No ESP32 connection detected. No readings will be collected.")
            return readings
        
        # For 20 seconds, get 3 readings (every 6-7 seconds)
        readings_count = 3
        interval = 6
        
        for i in range(readings_count):
            try:
                # Request reading from ESP32
                self.serial_connection.write(b"READ\n")
                response = self.serial_connection.readline().decode().strip()
                
                if response and response != "":
                    try:
                        # Parse JSON response from ESP32
                        data = json.loads(response)
                        readings.append(data)
                        print(f"Reading {len(readings)}: {data}")
                    except json.JSONDecodeError:
                        # Invalid JSON: just log and continue, do NOT fake data
                        print(f"Invalid JSON response from ESP32 (ignored): {response}")
                else:
                    # No response from ESP32: log only, do NOT fake data
                    print("No response from ESP32 for READ command (ignored).")
                
                # Wait for the interval (except after the last reading)
                if i < readings_count - 1:
                    time.sleep(interval)
                
            except Exception as e:
                # On error, log and continue, but do NOT generate fake readings
                print(f"Error reading from ESP32 (ignored, no fake data): {e}")
                if i < readings_count - 1:
                    time.sleep(interval)
        
        return readings
    
    def calculate_averages(self, readings: List[Dict]) -> Dict:
        """Calculate average values from collected readings"""
        if not readings:
            # Provide fallback data when no readings are available
            return {
                'n': 50.0,  # Default moderate nitrogen
                'p': 30.0,  # Default moderate phosphorus
                'k': 150.0, # Default moderate potassium
                'ph': 6.5,  # Default neutral pH
                'temperature': 25.0, # Default room temperature
                'humidity': 60.0, # Default moderate humidity
                'readings_count': 0,
                'timestamp': datetime.now().isoformat(),
                'note': 'No valid sensor readings - using default values'
            }
        
        # Extract all values, filter out None values
        n_values = [r.get('n', 0) for r in readings if r.get('n') is not None]
        p_values = [r.get('p', 0) for r in readings if r.get('p') is not None]
        k_values = [r.get('k', 0) for r in readings if r.get('k') is not None]
        ph_values = [r.get('ph', 0) for r in readings if r.get('ph') is not None]
        temperature_values = [r.get('temperature', 0) for r in readings if r.get('temperature') is not None]
        humidity_values = [r.get('humidity', 0) for r in readings if r.get('humidity') is not None]
        
        # If no valid readings for any parameter, use fallback values
        if not n_values and not p_values and not k_values and not ph_values:
            return {
                'n': 50.0,
                'p': 30.0,
                'k': 150.0,
                'ph': 6.5,
                'temperature': 25.0,
                'humidity': 60.0,
                'readings_count': len(readings),
                'timestamp': datetime.now().isoformat(),
                'note': 'No valid sensor readings - using default values'
            }
        
        # Calculate averages with fallbacks for missing data
        result = {
            'n': statistics.mean(n_values) if n_values else 50.0,
            'p': statistics.mean(p_values) if p_values else 30.0,
            'k': statistics.mean(k_values) if k_values else 150.0,
            'ph': statistics.mean(ph_values) if ph_values else 6.5,
            'temperature': statistics.mean(temperature_values) if temperature_values else 25.0,
            'humidity': statistics.mean(humidity_values) if humidity_values else 60.0,
            'readings_count': len(readings),
            'timestamp': datetime.now().isoformat()
        }
        
        return result
    
    def determine_soil_type(self, n: float, p: float, k: float, ph: float) -> str:
        """Determine soil type based on NPK and pH values"""
        # Enhanced soil type determination logic
        if ph < 6.0:
            if n < 50 and p < 30 and k < 150:
                return "Acidic, Low Fertility"
            else:
                return "Acidic, Moderate Fertility"
        elif ph > 7.5:
            if n < 50 and p < 30 and k < 150:
                return "Alkaline, Low Fertility"
            else:
                return "Alkaline, Moderate Fertility"
        else:
            if n >= 100 and p >= 50 and k >= 200:
                return "Neutral, High Fertility"
            elif n >= 50 and p >= 30 and k >= 150:
                return "Neutral, Moderate Fertility"
            else:
                return "Neutral, Low Fertility"
    
    def generate_recommendations(self, n: float, p: float, k: float, ph: float, soil_type: str) -> str:
        """Generate fertilizer recommendations"""
        recommendations = []
        
        if n < 50:
            recommendations.append("Apply nitrogen-rich fertilizer (NPK 20-10-10)")
        if p < 30:
            recommendations.append("Add phosphorus fertilizer (NPK 10-20-10)")
        if k < 150:
            recommendations.append("Apply potassium fertilizer (NPK 10-10-20)")
        
        if ph < 6.0:
            recommendations.append("Add lime to raise pH to 6.0-7.0")
        elif ph > 7.5:
            recommendations.append("Add sulfur to lower pH to 6.0-7.0")
        
        if not recommendations:
            recommendations.append("Soil is well-balanced. Maintain current practices.")
        
        return "; ".join(recommendations)
    
    def get_location_from_session(self) -> Optional[Dict]:
        """Get location data from Laravel session (simulated)"""
        # In a real implementation, you would get this from Laravel session
        # For now, we'll return a default location or None
        try:
            # Try to get location from a file or environment variable
            # This is a simplified approach
            return None
        except:
            return None
    
    def send_to_laravel(self, data: Dict) -> bool:
        """Send processed data to Laravel API"""
        try:
            # Determine soil type and recommendations
            soil_type = self.determine_soil_type(
                data['n'], data['p'], data['k'], data['ph']
            )
            recommendations = self.generate_recommendations(
                data['n'], data['p'], data['k'], data['ph'], soil_type
            )
            
            # Add to data
            data['soil_type'] = soil_type
            data['recommendations'] = recommendations
            
            # Try to get location data
            location_data = self.get_location_from_session()
            if location_data:
                data['location'] = location_data
            
            # Send to Laravel API
            url = f"{self.api_base_url}/sensor-readings"
            headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
            
            print(f"Sending data to Laravel: {data}")
            response = requests.post(url, json=data, headers=headers)
            
            if response.status_code == 201:
                print("Data sent to Laravel successfully!")
                return True
            else:
                print(f"Failed to send data to Laravel: {response.status_code} - {response.text}")
                return False
                
        except Exception as e:
            print(f"Error sending data to Laravel: {e}")
            return False
    
    def run_test(self) -> bool:
        """Run the complete sensor test"""
        try:
            # Connect to ESP32
            if not self.connect_to_esp32():
                return False
            
            # Collect readings
            readings = self.collect_readings()
            
            if not readings:
                print("No readings collected!")
                return False
            
            print(f"Collected {len(readings)} readings")
            
            # Calculate averages
            averages = self.calculate_averages(readings)
            print(f"Calculated averages: {averages}")
            
            # Send to Laravel
            success = self.send_to_laravel(averages)
            
            # Cleanup
            if self.serial_connection:
                self.serial_connection.close()
            
            return success
            
        except Exception as e:
            print(f"Test failed: {e}")
            if self.serial_connection:
                self.serial_connection.close()
            return False

def main():
    parser = argparse.ArgumentParser(description='ESP32 Sensor Test Script')
    parser.add_argument('--duration', type=int, required=True, help='Test duration in seconds')
    parser.add_argument('--port', type=str, help='ESP32 COM port (auto-detected if not specified)')
    parser.add_argument('--web', action='store_true', help='Output readings as JSON for web integration')
    
    args = parser.parse_args()
    
    try:
        test = SensorTest(args.duration, args.port)
        success = test.connect_to_esp32()
        if not success:
            print(json.dumps({'readings': []}))
            sys.exit(1)
        readings = test.collect_readings()
        if args.web:
            # Output readings as JSON for web - even if all readings are null
            print(json.dumps({'readings': readings}))
            sys.exit(0)
        # Normal CLI mode: calculate averages, send to Laravel
        if not readings:
            print("No readings collected!")
            sys.exit(1)
        print(f"Collected {len(readings)} readings")
        averages = test.calculate_averages(readings)
        print(f"Calculated averages: {averages}")
        success = test.send_to_laravel(averages)
        if success:
            print("Sensor test completed successfully!")
            sys.exit(0)
        else:
            print("Sensor test failed!")
            sys.exit(1)
            
    except Exception as e:
        print(f"Script error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main() 