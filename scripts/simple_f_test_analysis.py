import pandas as pd
import numpy as np
from sklearn.feature_selection import f_classif
from sklearn.metrics import accuracy_score, classification_report
from tensorflow import keras
import json
from datetime import datetime

def analyze_soil_classification():
    """Analyze F-test for soil classification model"""
    print("\n" + "="*60)
    print("SOIL CLASSIFICATION F-TEST ANALYSIS")
    print("="*60)
    
    # Load data
    df = pd.read_csv('resources/Soil_Classification_Dataset_PH.csv')
    features = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
    X = df[features]
    y = df['Type_of_Soil']
    
    # Perform F-test
    f_scores, p_values = f_classif(X, y)
    
    # Create results table
    results = pd.DataFrame({
        'Feature': features,
        'F_Score': f_scores,
        'P_Value': p_values,
        'Significance': p_values < 0.05,
        'Rank': range(1, len(features) + 1)
    })
    
    # Sort by F-score
    results = results.sort_values('F_Score', ascending=False).reset_index(drop=True)
    results['Rank'] = range(1, len(features) + 1)
    
    print("\nFeature Importance (F-Test Results):")
    print("="*50)
    print(results.to_string(index=False))
    
    # Test model accuracy
    print("\n" + "="*60)
    print("MODEL ACCURACY TEST")
    print("="*60)
    
    # Load model and test data
    model = keras.models.load_model('resources/ann_soil_classification_model.h5')
    X_test = pd.read_csv('resources/X_test_soil.csv')
    y_test = pd.read_csv('resources/y_test_soil.csv').values.ravel()
    
    # Make predictions
    y_pred_proba = model.predict(X_test)
    y_pred = np.argmax(y_pred_proba, axis=1)
    
    # Calculate accuracy
    accuracy = accuracy_score(y_test, y_pred)
    
    print(f"\nModel Accuracy: {accuracy:.4f} ({accuracy*100:.2f}%)")
    
    # Classification report
    soil_classes = pd.read_csv('resources/soil_class_labels.csv', header=None).squeeze().astype(str).values
    print("\nClassification Report:")
    print(classification_report(y_test, y_pred, target_names=soil_classes))
    
    return results, accuracy

def analyze_fertilizer_recommendation():
    """Analyze F-test for fertilizer recommendation model"""
    print("\n" + "="*60)
    print("FERTILIZER RECOMMENDATION F-TEST ANALYSIS")
    print("="*60)
    
    try:
        # Load data
        df = pd.read_csv('resources/fertilizer_recommendation_dataset.csv')
        features = ['Nitrogen', 'Phosphorous', 'Potassium', 'PH', 'Soil', 'Crop']
        X = df[features]
        y = df['Fertilizer']
        
        # Perform F-test
        f_scores, p_values = f_classif(X, y)
        
        # Create results table
        results = pd.DataFrame({
            'Feature': features,
            'F_Score': f_scores,
            'P_Value': p_values,
            'Significance': p_values < 0.05,
            'Rank': range(1, len(features) + 1)
        })
        
        # Sort by F-score
        results = results.sort_values('F_Score', ascending=False).reset_index(drop=True)
        results['Rank'] = range(1, len(features) + 1)
        
        print("\nFeature Importance (F-Test Results):")
        print("="*50)
        print(results.to_string(index=False))
        
        # Test model accuracy
        print("\n" + "="*60)
        print("MODEL ACCURACY TEST")
        print("="*60)
        
        # Load model and test data
        model = keras.models.load_model('resources/ann_fertilizer_model.h5')
        X_test = pd.read_csv('resources/X_test.csv')
        y_test = pd.read_csv('resources/y_test.csv').values.ravel()
        
        # Make predictions
        y_pred_proba = model.predict(X_test)
        y_pred = np.argmax(y_pred_proba, axis=1)
        
        # Calculate accuracy
        accuracy = accuracy_score(y_test, y_pred)
        
        print(f"\nModel Accuracy: {accuracy:.4f} ({accuracy*100:.2f}%)")
        
        return results, accuracy
        
    except Exception as e:
        print(f"Error analyzing fertilizer model: {e}")
        return None, None

def generate_summary_report(soil_results, soil_accuracy, fert_results, fert_accuracy):
    """Generate summary report"""
    print("\n" + "="*60)
    print("SUMMARY REPORT")
    print("="*60)
    
    summary = {
        'timestamp': datetime.now().strftime("%Y-%m-%d_%H-%M-%S"),
        'soil_classification': {
            'accuracy': soil_accuracy,
            'feature_importance': soil_results.to_dict('records') if soil_results is not None else None
        },
        'fertilizer_recommendation': {
            'accuracy': fert_accuracy,
            'feature_importance': fert_results.to_dict('records') if fert_results is not None else None
        }
    }
    
    print(f"\nSoil Classification Model:")
    print(f"  Accuracy: {soil_accuracy:.4f} ({soil_accuracy*100:.2f}%)")
    if soil_results is not None:
        print(f"  Most Important Feature: {soil_results.iloc[0]['Feature']} (F-score: {soil_results.iloc[0]['F_Score']:.2f})")
    
    if fert_accuracy is not None:
        print(f"\nFertilizer Recommendation Model:")
        print(f"  Accuracy: {fert_accuracy:.4f} ({fert_accuracy*100:.2f}%)")
        if fert_results is not None:
            print(f"  Most Important Feature: {fert_results.iloc[0]['Feature']} (F-score: {fert_results.iloc[0]['F_Score']:.2f})")
    
    # Save report
    import os
    os.makedirs('f_test_results', exist_ok=True)
    
    with open(f'f_test_results/f_test_analysis_{summary["timestamp"]}.json', 'w') as f:
        json.dump(summary, f, indent=2)
    
    print(f"\nReport saved to: f_test_results/f_test_analysis_{summary['timestamp']}.json")
    
    return summary

if __name__ == "__main__":
    print("F-TEST ACCURACY ANALYSIS")
    print("="*60)
    
    # Analyze soil classification
    soil_results, soil_accuracy = analyze_soil_classification()
    
    # Analyze fertilizer recommendation
    fert_results, fert_accuracy = analyze_fertilizer_recommendation()
    
    # Generate summary
    summary = generate_summary_report(soil_results, soil_accuracy, fert_results, fert_accuracy)
    
    print("\n" + "="*60)
    print("ANALYSIS COMPLETE")
    print("="*60) 