import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from tensorflow import keras
from sklearn.metrics import classification_report, confusion_matrix, accuracy_score
import json
from datetime import datetime

class AccuracyPlotGenerator:
    def __init__(self):
        self.timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
        plt.style.use('seaborn-v0_8')
        
    def load_model_and_data(self):
        """Load the trained model and test data"""
        print("Loading model and data...")
        
        # Load model
        self.model = keras.models.load_model('resources/ann_soil_classification_model.h5')
        
        # Load test data
        self.X_test = pd.read_csv('resources/X_test_soil.csv')
        self.y_test = pd.read_csv('resources/y_test_soil.csv').values.ravel()
        self.soil_classes = pd.read_csv('resources/soil_class_labels.csv', header=None).squeeze().astype(str).values
        
        print(f"Model loaded successfully!")
        print(f"Test data shape: {self.X_test.shape}")
        print(f"Number of soil classes: {len(self.soil_classes)}")
        print(f"Soil classes: {list(self.soil_classes)}")
        
    def generate_predictions(self):
        """Generate predictions and calculate metrics"""
        print("\nGenerating predictions...")
        
        # Make predictions
        y_pred_proba = self.model.predict(self.X_test)
        self.y_pred = np.argmax(y_pred_proba, axis=1)
        self.y_pred_proba = y_pred_proba
        
        # Calculate accuracy
        self.accuracy = accuracy_score(self.y_test, self.y_pred)
        
        # Classification report
        self.class_report = classification_report(self.y_test, self.y_pred, 
                                                target_names=self.soil_classes, output_dict=True)
        
        # Confusion matrix
        self.cm = confusion_matrix(self.y_test, self.y_pred)
        
        print(f"Test Accuracy: {self.accuracy:.4f}")
        
    def plot_accuracy_validation(self):
        """Create comprehensive accuracy validation plots"""
        print("\nGenerating accuracy validation plots...")
        
        # Create figure with subplots
        fig = plt.figure(figsize=(20, 16))
        
        # 1. Overall Accuracy Score
        plt.subplot(3, 3, 1)
        plt.bar(['ANN Model'], [self.accuracy], color='#2E8B57', alpha=0.8)
        plt.title('Overall Model Accuracy', fontsize=14, fontweight='bold')
        plt.ylabel('Accuracy Score', fontsize=12)
        plt.ylim(0, 1.1)
        plt.text(0, self.accuracy + 0.05, f'{self.accuracy:.3f}', 
                ha='center', va='bottom', fontsize=14, fontweight='bold')
        
        # Add performance indicators
        if self.accuracy >= 0.9:
            performance = "Excellent"
            color = "#228B22"
        elif self.accuracy >= 0.8:
            performance = "Good"
            color = "#FFD700"
        elif self.accuracy >= 0.7:
            performance = "Fair"
            color = "#FF8C00"
        else:
            performance = "Poor"
            color = "#DC143C"
            
        plt.text(0, 0.5, f'Performance: {performance}', 
                ha='center', va='center', fontsize=12, 
                bbox=dict(boxstyle="round,pad=0.3", facecolor=color, alpha=0.3))
        
        # 2. Confusion Matrix
        plt.subplot(3, 3, 2)
        sns.heatmap(self.cm, annot=True, fmt='d', cmap='Blues', 
                   xticklabels=self.soil_classes, yticklabels=self.soil_classes)
        plt.title('Confusion Matrix', fontsize=14, fontweight='bold')
        plt.ylabel('True Soil Type', fontsize=10)
        plt.xlabel('Predicted Soil Type', fontsize=10)
        plt.xticks(rotation=45, ha='right')
        plt.yticks(rotation=0)
        
        # 3. Per-Class Accuracy
        plt.subplot(3, 3, 3)
        per_class_acc = []
        for i, class_name in enumerate(self.soil_classes):
            class_mask = self.y_test == i
            if np.sum(class_mask) > 0:
                class_acc = np.sum((self.y_pred[class_mask] == i)) / np.sum(class_mask)
                per_class_acc.append(class_acc)
            else:
                per_class_acc.append(0)
        
        bars = plt.bar(range(len(self.soil_classes)), per_class_acc, 
                      color=plt.cm.Set3(np.linspace(0, 1, len(self.soil_classes))))
        plt.title('Per-Class Accuracy', fontsize=14, fontweight='bold')
        plt.ylabel('Accuracy', fontsize=12)
        plt.xlabel('Soil Types', fontsize=10)
        plt.xticks(range(len(self.soil_classes)), self.soil_classes, rotation=45, ha='right')
        plt.ylim(0, 1.1)
        
        # Add accuracy values on bars
        for i, (bar, acc) in enumerate(zip(bars, per_class_acc)):
            plt.text(bar.get_x() + bar.get_width()/2, acc + 0.02, 
                    f'{acc:.2f}', ha='center', va='bottom', fontsize=8)
        
        # 4. Precision, Recall, F1-Score
        plt.subplot(3, 3, 4)
        metrics = ['Precision', 'Recall', 'F1-Score']
        macro_avg = [self.class_report['macro avg']['precision'],
                    self.class_report['macro avg']['recall'],
                    self.class_report['macro avg']['f1-score']]
        
        bars = plt.bar(metrics, macro_avg, color=['#FF6B6B', '#4ECDC4', '#45B7D1'])
        plt.title('Macro-Averaged Metrics', fontsize=14, fontweight='bold')
        plt.ylabel('Score', fontsize=12)
        plt.ylim(0, 1.1)
        
        for bar, score in zip(bars, macro_avg):
            plt.text(bar.get_x() + bar.get_width()/2, score + 0.02, 
                    f'{score:.3f}', ha='center', va='bottom', fontsize=10)
        
        # 5. Prediction Confidence Distribution
        plt.subplot(3, 3, 5)
        max_probs = np.max(self.y_pred_proba, axis=1)
        plt.hist(max_probs, bins=20, alpha=0.7, color='#9B59B6', edgecolor='black')
        plt.title('Prediction Confidence Distribution', fontsize=14, fontweight='bold')
        plt.xlabel('Maximum Probability', fontsize=12)
        plt.ylabel('Frequency', fontsize=12)
        plt.axvline(np.mean(max_probs), color='red', linestyle='--', 
                   label=f'Mean: {np.mean(max_probs):.3f}')
        plt.legend()
        
        # 6. Feature Importance (based on model weights)
        plt.subplot(3, 3, 6)
        feature_names = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
        
        # Get first layer weights (simplified feature importance)
        first_layer_weights = self.model.layers[0].get_weights()[0]
        feature_importance = np.mean(np.abs(first_layer_weights), axis=1)
        
        bars = plt.barh(feature_names, feature_importance, color='#E67E22')
        plt.title('Feature Importance', fontsize=14, fontweight='bold')
        plt.xlabel('Average Absolute Weight', fontsize=12)
        
        for i, (bar, importance) in enumerate(zip(bars, feature_importance)):
            plt.text(bar.get_width() + 0.01, bar.get_y() + bar.get_height()/2, 
                    f'{importance:.3f}', ha='left', va='center', fontsize=9)
        
        # 7. Classification Report Heatmap
        plt.subplot(3, 3, 7)
        report_data = []
        for class_name in self.soil_classes:
            if class_name in self.class_report:
                report_data.append([
                    self.class_report[class_name]['precision'],
                    self.class_report[class_name]['recall'],
                    self.class_report[class_name]['f1-score']
                ])
        
        report_matrix = np.array(report_data)
        sns.heatmap(report_matrix, annot=True, fmt='.3f', cmap='RdYlGn',
                   xticklabels=['Precision', 'Recall', 'F1-Score'],
                   yticklabels=self.soil_classes, vmin=0, vmax=1)
        plt.title('Classification Metrics Heatmap', fontsize=14, fontweight='bold')
        plt.ylabel('Soil Types', fontsize=10)
        plt.xlabel('Metrics', fontsize=12)
        plt.xticks(rotation=0)
        plt.yticks(rotation=0)
        
        # 8. Error Analysis
        plt.subplot(3, 3, 8)
        errors = self.y_test != self.y_pred
        error_count = np.sum(errors)
        correct_count = len(self.y_test) - error_count
        
        plt.pie([correct_count, error_count], labels=['Correct', 'Errors'], 
               autopct='%1.1f%%', colors=['#2ECC71', '#E74C3C'], startangle=90)
        plt.title('Prediction Errors', fontsize=14, fontweight='bold')
        
        # 9. Model Performance Summary
        plt.subplot(3, 3, 9)
        plt.axis('off')
        
        summary_text = f"""
        MODEL PERFORMANCE SUMMARY
        
        Overall Accuracy: {self.accuracy:.4f}
        
        Total Samples: {len(self.y_test)}
        Correct Predictions: {correct_count}
        Incorrect Predictions: {error_count}
        
        Macro-Averaged Scores:
        • Precision: {self.class_report['macro avg']['precision']:.4f}
        • Recall: {self.class_report['macro avg']['recall']:.4f}
        • F1-Score: {self.class_report['macro avg']['f1-score']:.4f}
        
        Model Architecture:
        • Input Features: {self.X_test.shape[1]}
        • Output Classes: {len(self.soil_classes)}
        • Layers: {len(self.model.layers)}
        
        Performance Rating: {performance}
        """
        
        plt.text(0.1, 0.9, summary_text, transform=plt.gca().transAxes, 
                fontsize=10, verticalalignment='top', fontfamily='monospace',
                bbox=dict(boxstyle="round,pad=0.5", facecolor='lightgray', alpha=0.8))
        
        plt.tight_layout()
        
        # Save the plot
        plt.savefig(f'accuracy_validation_plots_{self.timestamp}.png', 
                   dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        
        print(f"\nAccuracy validation plots saved as: accuracy_validation_plots_{self.timestamp}.png")
        
    def generate_detailed_report(self):
        """Generate detailed evaluation report"""
        print("\nGenerating detailed report...")
        
        report = {
            'timestamp': self.timestamp,
            'model_performance': {
                'overall_accuracy': float(self.accuracy),
                'total_samples': int(len(self.y_test)),
                'correct_predictions': int(np.sum(self.y_test == self.y_pred)),
                'incorrect_predictions': int(np.sum(self.y_test != self.y_pred))
            },
            'classification_report': self.class_report,
            'confusion_matrix': self.cm.tolist(),
            'per_class_accuracy': {},
            'feature_names': ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
        }
        
        # Add per-class accuracy
        for i, class_name in enumerate(self.soil_classes):
            class_mask = self.y_test == i
            if np.sum(class_mask) > 0:
                class_acc = np.sum((self.y_pred[class_mask] == i)) / np.sum(class_mask)
                report['per_class_accuracy'][class_name] = float(class_acc)
        
        # Save report
        with open(f'accuracy_validation_report_{self.timestamp}.json', 'w') as f:
            json.dump(report, f, indent=2)
        
        print(f"Detailed report saved as: accuracy_validation_report_{self.timestamp}.json")
        
        return report

def main():
    print("="*60)
    print("ANN SOIL TYPE PREDICTION - ACCURACY VALIDATION PLOTS")
    print("="*60)
    
    # Initialize plot generator
    plotter = AccuracyPlotGenerator()
    
    # Load model and data
    plotter.load_model_and_data()
    
    # Generate predictions
    plotter.generate_predictions()
    
    # Create plots
    plotter.plot_accuracy_validation()
    
    # Generate detailed report
    report = plotter.generate_detailed_report()
    
    print("\n" + "="*60)
    print("VALIDATION COMPLETE!")
    print("="*60)
    print(f"Final Accuracy: {report['model_performance']['overall_accuracy']:.4f}")
    print(f"Plots saved: accuracy_validation_plots_{plotter.timestamp}.png")
    print(f"Report saved: accuracy_validation_report_{plotter.timestamp}.json")

if __name__ == "__main__":
    main()
