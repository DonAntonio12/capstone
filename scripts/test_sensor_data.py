#!/usr/bin/env python3
"""
Test Sensor Data Script
Generates mock sensor data and sends it to Laravel API for testing.
"""

import json
import requests
import time
import random
from datetime import datetime
from typing import Dict

class TestSensorData:
    def __init__(self):
        self.api_base_url = "http://localhost:8000/api"
        
    def generate_mock_data(self) -> Dict:
        """Generate realistic mock sensor data"""
        # Generate random but realistic NPK and pH values
        n = random.uniform(30, 120)
        p = random.uniform(20, 80)
        k = random.uniform(100, 300)
        ph = random.uniform(5.5, 8.0)
        
        return {
            'n': round(n, 2),
            'p': round(p, 2),
            'k': round(k, 2),
            'ph': round(ph, 2),
            'temperature': round(random.uniform(20, 35), 1),
            'humidity': round(random.uniform(40, 80), 1),
            'readings_count': random.randint(5, 15),
            'timestamp': datetime.now().isoformat()
        }
    
    def determine_soil_type(self, n: float, p: float, k: float, ph: float) -> str:
        """Determine soil type based on NPK and pH values"""
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
    
    def send_to_laravel(self, data: Dict, location: Dict = None) -> bool:
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
            
            # Add location data if provided
            if location:
                data['location'] = location
            
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
    
    def run_test(self, location: Dict = None) -> bool:
        """Run the test with mock data"""
        try:
            print("Generating mock sensor data...")
            
            # Generate mock data
            mock_data = self.generate_mock_data()
            print(f"Generated data: {mock_data}")
            
            # Send to Laravel
            success = self.send_to_laravel(mock_data, location)
            
            return success
            
        except Exception as e:
            print(f"Test failed: {e}")
            return False

def main():
    print("Starting test sensor data generation...")
    
    # Example location data (Philippines coordinates)
    location_data = {
        'lat': 14.5995,
        'lng': 120.9842,
        'address': '14.599500, 120.984200'
    }
    
    try:
        test = TestSensorData()
        success = test.run_test(location_data)
        
        if success:
            print("Test completed successfully!")
        else:
            print("Test failed!")
            
    except Exception as e:
        print(f"Script error: {e}")

if __name__ == "__main__":
    main() 