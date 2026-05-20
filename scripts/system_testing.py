import requests
import json
import time
import sys
import os
from datetime import datetime
import pandas as pd
import numpy as np

class SystemTester:
    def __init__(self):
        self.bug_reports = []
        self.test_results = {}
        self.timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
        
    def test_fastapi_service(self):
        """Test FastAPI ML service"""
        print("\n" + "="*50)
        print("TESTING FASTAPI ML SERVICE")
        print("="*50)
        
        # Test data
        test_cases = [
            {
                'name': 'Normal Case',
                'data': {
                    'nitrogen': 0.15,
                    'phosphorus': 45.0,
                    'potassium': 180.0,
                    'ph': 6.5,
                    'organic_carbon': 1.2
                }
            },
            {
                'name': 'Low NPK Case',
                'data': {
                    'nitrogen': 0.05,
                    'phosphorus': 15.0,
                    'potassium': 80.0,
                    'ph': 5.0,
                    'organic_carbon': 0.8
                }
            },
            {
                'name': 'High NPK Case',
                'data': {
                    'nitrogen': 0.25,
                    'phosphorus': 80.0,
                    'potassium': 250.0,
                    'ph': 7.5,
                    'organic_carbon': 2.0
                }
            }
        ]
        
        for test_case in test_cases:
            print(f"\nTesting: {test_case['name']}")
            try:
                response = requests.post(
                    'http://127.0.0.1:8001/predict',
                    json=test_case['data'],
                    timeout=10
                )
                
                if response.status_code == 200:
                    result = response.json()
                    print(f"✓ Success - Status: {response.status_code}")
                    print(f"  Soil Type: {result.get('soil', {}).get('soil_type', 'N/A')}")
                    print(f"  Texture: {result.get('soil', {}).get('texture', 'N/A')}")
                    
                    # Check for required fields
                    if not result.get('soil', {}).get('soil_type'):
                        self.bug_reports.append({
                            'component': 'FastAPI Service',
                            'severity': 'High',
                            'issue': f"Missing soil_type in response for {test_case['name']}",
                            'test_case': test_case['name'],
                            'timestamp': self.timestamp
                        })
                    
                else:
                    print(f"✗ Failed - Status: {response.status_code}")
                    self.bug_reports.append({
                        'component': 'FastAPI Service',
                        'severity': 'High',
                        'issue': f"HTTP {response.status_code} error for {test_case['name']}",
                        'test_case': test_case['name'],
                        'timestamp': self.timestamp
                    })
                    
            except requests.exceptions.ConnectionError:
                print("✗ Connection Error - FastAPI service not running")
                self.bug_reports.append({
                    'component': 'FastAPI Service',
                    'severity': 'Critical',
                    'issue': 'FastAPI service not accessible',
                    'test_case': test_case['name'],
                    'timestamp': self.timestamp
                })
            except Exception as e:
                print(f"✗ Error: {str(e)}")
                self.bug_reports.append({
                    'component': 'FastAPI Service',
                    'severity': 'Medium',
                    'issue': f"Unexpected error: {str(e)}",
                    'test_case': test_case['name'],
                    'timestamp': self.timestamp
                })
    
    def test_laravel_web_interface(self):
        """Test Laravel web interface"""
        print("\n" + "="*50)
        print("TESTING LARAVEL WEB INTERFACE")
        print("="*50)
        
        # Test endpoints
        endpoints = [
            {'url': 'http://127.0.0.1:8000/testing', 'name': 'Testing Page'},
            {'url': 'http://127.0.0.1:8000/dashboard', 'name': 'Dashboard'},
            {'url': 'http://127.0.0.1:8000/history', 'name': 'History Page'}
        ]
        
        for endpoint in endpoints:
            print(f"\nTesting: {endpoint['name']}")
            try:
                response = requests.get(endpoint['url'], timeout=10)
                
                if response.status_code == 200:
                    print(f"✓ Success - Status: {response.status_code}")
                else:
                    print(f"✗ Failed - Status: {response.status_code}")
                    self.bug_reports.append({
                        'component': 'Laravel Web Interface',
                        'severity': 'Medium',
                        'issue': f"HTTP {response.status_code} for {endpoint['name']}",
                        'endpoint': endpoint['url'],
                        'timestamp': self.timestamp
                    })
                    
            except requests.exceptions.ConnectionError:
                print("✗ Connection Error - Laravel not running")
                self.bug_reports.append({
                    'component': 'Laravel Web Interface',
                    'severity': 'Critical',
                    'issue': f'Laravel service not accessible for {endpoint["name"]}',
                    'endpoint': endpoint['url'],
                    'timestamp': self.timestamp
                })
            except Exception as e:
                print(f"✗ Error: {str(e)}")
                self.bug_reports.append({
                    'component': 'Laravel Web Interface',
                    'severity': 'Medium',
                    'issue': f"Unexpected error: {str(e)}",
                    'endpoint': endpoint['url'],
                    'timestamp': self.timestamp
                })
    
    def test_model_files(self):
        """Test if model files exist and are valid"""
        print("\n" + "="*50)
        print("TESTING MODEL FILES")
        print("="*50)
        
        required_files = [
            'resources/ann_soil_classification_model.h5',
            'resources/ann_fertilizer_model.h5',
            'resources/soil_class_labels.csv',
            'resources/X_test_soil.csv',
            'resources/y_test_soil.csv',
            'resources/X_train_soil.csv',
            'resources/y_train_soil.csv'
        ]
        
        for file_path in required_files:
            print(f"\nChecking: {file_path}")
            if os.path.exists(file_path):
                file_size = os.path.getsize(file_path)
                print(f"✓ Exists - Size: {file_size} bytes")
                
                if file_size == 0:
                    self.bug_reports.append({
                        'component': 'Model Files',
                        'severity': 'High',
                        'issue': f'Empty file: {file_path}',
                        'file': file_path,
                        'timestamp': self.timestamp
                    })
            else:
                print(f"✗ Missing: {file_path}")
                self.bug_reports.append({
                    'component': 'Model Files',
                    'severity': 'Critical',
                    'issue': f'Missing required file: {file_path}',
                    'file': file_path,
                    'timestamp': self.timestamp
                })
    
    def test_data_quality(self):
        """Test data quality and consistency"""
        print("\n" + "="*50)
        print("TESTING DATA QUALITY")
        print("="*50)
        
        try:
            # Test soil classification data
            df_soil = pd.read_csv('resources/Soil_Classification_Dataset_PH.csv')
            print(f"\nSoil Classification Dataset:")
            print(f"  Rows: {len(df_soil)}")
            print(f"  Columns: {len(df_soil.columns)}")
            print(f"  Missing values: {df_soil.isnull().sum().sum()}")
            
            # Check for data quality issues
            if df_soil.isnull().sum().sum() > 0:
                self.bug_reports.append({
                    'component': 'Data Quality',
                    'severity': 'Medium',
                    'issue': 'Missing values found in soil classification dataset',
                    'missing_count': df_soil.isnull().sum().sum(),
                    'timestamp': self.timestamp
                })
            
            # Check for outliers in numerical columns
            numerical_cols = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
            for col in numerical_cols:
                if col in df_soil.columns:
                    Q1 = df_soil[col].quantile(0.25)
                    Q3 = df_soil[col].quantile(0.75)
                    IQR = Q3 - Q1
                    outliers = df_soil[(df_soil[col] < Q1 - 1.5*IQR) | (df_soil[col] > Q3 + 1.5*IQR)]
                    if len(outliers) > 0:
                        print(f"  Outliers in {col}: {len(outliers)}")
                        self.bug_reports.append({
                            'component': 'Data Quality',
                            'severity': 'Low',
                            'issue': f'Outliers detected in {col}',
                            'outlier_count': len(outliers),
                            'column': col,
                            'timestamp': self.timestamp
                        })
            
        except Exception as e:
            print(f"✗ Error reading data: {str(e)}")
            self.bug_reports.append({
                'component': 'Data Quality',
                'severity': 'High',
                'issue': f'Error reading dataset: {str(e)}',
                'timestamp': self.timestamp
            })
    
    def test_prediction_consistency(self):
        """Test prediction consistency"""
        print("\n" + "="*50)
        print("TESTING PREDICTION CONSISTENCY")
        print("="*50)
        
        test_input = {
            'nitrogen': 0.15,
            'phosphorus': 45.0,
            'potassium': 180.0,
            'ph': 6.5,
            'organic_carbon': 1.2
        }
        
        predictions = []
        for i in range(5):  # Test 5 times
            try:
                response = requests.post(
                    'http://127.0.0.1:8001/predict',
                    json=test_input,
                    timeout=10
                )
                
                if response.status_code == 200:
                    result = response.json()
                    soil_type = result.get('soil', {}).get('soil_type', 'N/A')
                    predictions.append(soil_type)
                    print(f"  Run {i+1}: {soil_type}")
                else:
                    print(f"  Run {i+1}: Failed (HTTP {response.status_code})")
                    
            except Exception as e:
                print(f"  Run {i+1}: Error - {str(e)}")
        
        # Check consistency
        unique_predictions = set(predictions)
        if len(unique_predictions) > 1:
            print(f"✗ Inconsistent predictions: {unique_predictions}")
            self.bug_reports.append({
                'component': 'Prediction Consistency',
                'severity': 'Medium',
                'issue': f'Inconsistent predictions for same input: {unique_predictions}',
                'predictions': list(unique_predictions),
                'timestamp': self.timestamp
            })
        else:
            print(f"✓ Consistent predictions: {list(unique_predictions)[0]}")
    
    def generate_bug_report(self):
        """Generate comprehensive bug report"""
        print("\n" + "="*50)
        print("GENERATING BUG REPORT")
        print("="*50)
        
        # Create results directory
        os.makedirs('system_testing_results', exist_ok=True)
        
        # Categorize bugs by severity
        critical_bugs = [bug for bug in self.bug_reports if bug['severity'] == 'Critical']
        high_bugs = [bug for bug in self.bug_reports if bug['severity'] == 'High']
        medium_bugs = [bug for bug in self.bug_reports if bug['severity'] == 'Medium']
        low_bugs = [bug for bug in self.bug_reports if bug['severity'] == 'Low']
        
        # Generate JSON report
        report = {
            'timestamp': self.timestamp,
            'summary': {
                'total_bugs': len(self.bug_reports),
                'critical': len(critical_bugs),
                'high': len(high_bugs),
                'medium': len(medium_bugs),
                'low': len(low_bugs)
            },
            'bugs_by_component': {},
            'all_bugs': self.bug_reports
        }
        
        # Group by component
        for bug in self.bug_reports:
            component = bug['component']
            if component not in report['bugs_by_component']:
                report['bugs_by_component'][component] = []
            report['bugs_by_component'][component].append(bug)
        
        # Save JSON report
        with open(f'system_testing_results/bug_report_{self.timestamp}.json', 'w') as f:
            json.dump(report, f, indent=2)
        
        # Generate markdown report
        md_content = f"""# System Testing Bug Report

**Generated:** {self.timestamp}

## Executive Summary

- **Total Bugs Found:** {len(self.bug_reports)}
- **Critical:** {len(critical_bugs)}
- **High:** {len(high_bugs)}
- **Medium:** {len(medium_bugs)}
- **Low:** {len(low_bugs)}

## Critical Issues

"""
        
        for bug in critical_bugs:
            md_content += f"""### {bug['component']} - {bug['issue']}
**Timestamp:** {bug['timestamp']}
**Details:** {bug.get('test_case', bug.get('endpoint', bug.get('file', 'N/A')))}

"""
        
        md_content += """## High Priority Issues

"""
        
        for bug in high_bugs:
            md_content += f"""### {bug['component']} - {bug['issue']}
**Timestamp:** {bug['timestamp']}
**Details:** {bug.get('test_case', bug.get('endpoint', bug.get('file', 'N/A')))}

"""
        
        md_content += """## Medium Priority Issues

"""
        
        for bug in medium_bugs:
            md_content += f"""### {bug['component']} - {bug['issue']}
**Timestamp:** {bug['timestamp']}
**Details:** {bug.get('test_case', bug.get('endpoint', bug.get('file', 'N/A')))}

"""
        
        md_content += """## Low Priority Issues

"""
        
        for bug in low_bugs:
            md_content += f"""### {bug['component']} - {bug['issue']}
**Timestamp:** {bug['timestamp']}
**Details:** {bug.get('test_case', bug.get('endpoint', bug.get('file', 'N/A')))}

"""
        
        md_content += f"""

## Bugs by Component

"""
        
        for component, bugs in report['bugs_by_component'].items():
            md_content += f"""### {component}
**Count:** {len(bugs)}

"""
            for bug in bugs:
                md_content += f"- **{bug['severity']}:** {bug['issue']}\n"
            md_content += "\n"
        
        md_content += f"""

---
*Report generated automatically by System Tester*
"""
        
        with open(f'system_testing_results/bug_report_{self.timestamp}.md', 'w') as f:
            f.write(md_content)
        
        print(f"Bug report saved to: system_testing_results/bug_report_{self.timestamp}.json")
        print(f"Markdown report: system_testing_results/bug_report_{self.timestamp}.md")
        
        return report
    
    def run_complete_testing(self):
        """Run complete system testing"""
        print("Starting comprehensive system testing...")
        
        # Run all tests
        self.test_model_files()
        self.test_data_quality()
        self.test_fastapi_service()
        self.test_laravel_web_interface()
        self.test_prediction_consistency()
        
        # Generate bug report
        report = self.generate_bug_report()
        
        print("\n" + "="*50)
        print("SYSTEM TESTING COMPLETE")
        print("="*50)
        print(f"Total bugs found: {len(self.bug_reports)}")
        print(f"Critical: {len([b for b in self.bug_reports if b['severity'] == 'Critical'])}")
        print(f"High: {len([b for b in self.bug_reports if b['severity'] == 'High'])}")
        print(f"Medium: {len([b for b in self.bug_reports if b['severity'] == 'Medium'])}")
        print(f"Low: {len([b for b in self.bug_reports if b['severity'] == 'Low'])}")
        
        return report

if __name__ == "__main__":
    tester = SystemTester()
    report = tester.run_complete_testing() 