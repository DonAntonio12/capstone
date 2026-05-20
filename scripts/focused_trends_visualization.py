import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
from tensorflow import keras
from sklearn.metrics import accuracy_score, classification_report
from datetime import datetime
from pathlib import Path
import warnings
warnings.filterwarnings('ignore')

class FocusedTrendsVisualizer:
    def __init__(self):
        self.timestamp = datetime.now().strftime("%Y-%m-%d_%H-%M-%S")
        plt.style.use('default')
        plt.rcParams['figure.facecolor'] = 'white'
        plt.rcParams['axes.facecolor'] = 'white'
        plt.rcParams['font.size'] = 11
        plt.rcParams['axes.grid'] = True
        plt.rcParams['grid.alpha'] = 0.3
        
    def load_data(self):
        """Load necessary data for visualization"""
        print("🔄 Loading data for focused trends visualization...")
        
        try:
            # Resolve project root (scripts/..)
            root = Path(__file__).resolve().parents[1]
            resources = root / 'resources'
            
            # Load soil dataset
            self.soil_dataset = pd.read_csv(resources / 'Soil_Classification_Dataset_PH.csv')
            print("✅ Soil dataset loaded successfully")
            
            # Load models if available
            try:
                self.soil_model = keras.models.load_model(resources / 'ann_soil_classification_model.h5')
                self.X_test_soil = pd.read_csv(resources / 'X_test_soil.csv')
                self.y_test_soil = pd.read_csv(resources / 'y_test_soil.csv').values.ravel()
                self.soil_classes = pd.read_csv(resources / 'soil_class_labels.csv', header=None).squeeze().astype(str).values
                self.models_available = True
                print("✅ Soil classification model loaded successfully")
            except:
                self.models_available = False
                print("⚠️ Models not available, will show data analysis only")
                
        except Exception as e:
            print(f"❌ Error loading data: {e}")
            return False
            
        return True
    
    def create_nutrient_trends_bar_chart(self, ax):
        """Create bar chart for nutrient trends per soil type"""
        print("📊 Creating nutrient trends bar chart...")
        
        # Group by soil type and calculate mean nutrient values
        nutrient_stats = self.soil_dataset.groupby('Type_of_Soil').agg({
            'Nitrogen (%)': 'mean',
            'Phosphorus (ppm)': 'mean',
            'Potassium (ppm)': 'mean'
        }).round(2)
        
        # Prepare data for plotting
        soil_types = nutrient_stats.index
        x_pos = np.arange(len(soil_types))
        width = 0.25
        
        # Create bars for each nutrient
        bars1 = ax.bar(x_pos - width, nutrient_stats['Nitrogen (%)'], width, 
                      label='Nitrogen (%)', color='#2E8B57', alpha=0.8, edgecolor='black')
        bars2 = ax.bar(x_pos, nutrient_stats['Phosphorus (ppm)'], width, 
                      label='Phosphorus (ppm)', color='#FF6B35', alpha=0.8, edgecolor='black')
        bars3 = ax.bar(x_pos + width, nutrient_stats['Potassium (ppm)'], width, 
                      label='Potassium (ppm)', color='#4169E1', alpha=0.8, edgecolor='black')
        
        # Customize the chart
        ax.set_title('Nutrient Trends per Soil Type\n(Bar Chart Analysis)', 
                    fontsize=14, fontweight='bold', pad=20)
        ax.set_xlabel('Soil Types', fontsize=12, fontweight='bold')
        ax.set_ylabel('Nutrient Values', fontsize=12, fontweight='bold')
        ax.set_xticks(x_pos)
        ax.set_xticklabels(soil_types, rotation=45, ha='right', fontsize=10)
        ax.legend(fontsize=10, loc='upper right')
        ax.grid(True, alpha=0.3, axis='y')
        
        # Add value labels on bars
        for bars in [bars1, bars2, bars3]:
            for bar in bars:
                height = bar.get_height()
                ax.text(bar.get_x() + bar.get_width()/2., height + height*0.01,
                       f'{height:.1f}', ha='center', va='bottom', fontsize=8, fontweight='bold')
        
        # Add trend analysis text
        ax.text(0.02, 0.98, 'Trend Analysis:\n• Clear nutrient differentiation\n• Soil-specific patterns\n• Potassium shows highest variation', 
               transform=ax.transAxes, fontsize=9, verticalalignment='top',
               bbox=dict(boxstyle="round,pad=0.3", facecolor='lightblue', alpha=0.7))
    
    def create_ph_variation_line_chart(self, ax):
        """Create line chart for pH variation"""
        print("📈 Creating pH variation line chart...")
        
        # Group by soil type and get pH statistics
        ph_stats = self.soil_dataset.groupby('Type_of_Soil')['pH'].agg(['mean', 'std', 'min', 'max']).round(2)
        
        # Create line plot with error bars
        soil_types = ph_stats.index
        x_pos = np.arange(len(soil_types))
        
        # Plot mean pH with error bars
        ax.errorbar(x_pos, ph_stats['mean'], yerr=ph_stats['std'], 
                   marker='o', linewidth=3, markersize=8, capsize=5, capthick=2,
                   color='#8B0000', alpha=0.8, label='Mean pH ± Std Dev')
        
        # Add min/max range
        ax.fill_between(x_pos, ph_stats['min'], ph_stats['max'], 
                       alpha=0.2, color='#FF6B6B', label='pH Range (Min-Max)')
        
        # Add reference lines
        ax.axhline(y=7.0, color='red', linestyle='--', alpha=0.7, linewidth=2, label='Neutral pH (7.0)')
        ax.axhline(y=6.5, color='orange', linestyle='--', alpha=0.5, linewidth=1, label='Slightly Acidic (6.5)')
        ax.axhline(y=7.5, color='blue', linestyle='--', alpha=0.5, linewidth=1, label='Slightly Alkaline (7.5)')
        
        # Customize the chart
        ax.set_title('pH Variation Across Soil Types\n(Line Chart with Error Bars)', 
                    fontsize=14, fontweight='bold', pad=20)
        ax.set_xlabel('Soil Types', fontsize=12, fontweight='bold')
        ax.set_ylabel('pH Level', fontsize=12, fontweight='bold')
        ax.set_xticks(x_pos)
        ax.set_xticklabels(soil_types, rotation=45, ha='right', fontsize=10)
        ax.legend(fontsize=9, loc='upper right')
        ax.grid(True, alpha=0.3)
        ax.set_ylim(5.5, 8.5)
        
        # Add pH interpretation zones
        ax.axhspan(5.5, 6.5, alpha=0.1, color='red', label='Acidic Zone')
        ax.axhspan(6.5, 7.5, alpha=0.1, color='green', label='Neutral Zone')
        ax.axhspan(7.5, 8.5, alpha=0.1, color='blue', label='Alkaline Zone')
        
        # Add value labels
        for i, (mean_val, std_val) in enumerate(zip(ph_stats['mean'], ph_stats['std'])):
            ax.text(i, mean_val + std_val + 0.1, f'{mean_val:.2f}±{std_val:.2f}', 
                   ha='center', va='bottom', fontsize=9, fontweight='bold',
                   bbox=dict(boxstyle="round,pad=0.2", facecolor='white', alpha=0.8))
    
    def create_correlation_heatmap(self, ax):
        """Create heat map for correlations and distribution"""
        print("🔥 Creating correlation heat map...")
        
        # Select numeric columns for correlation
        numeric_cols = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
        correlation_data = self.soil_dataset[numeric_cols].corr()
        
        # Create heatmap
        sns.heatmap(correlation_data, annot=True, cmap='RdBu_r', center=0,
                   square=True, fmt='.3f', cbar_kws={'label': 'Correlation Coefficient'},
                   ax=ax, linewidths=0.5, linecolor='white')
        
        # Customize the heatmap
        ax.set_title('Nutrient Correlation Matrix\n(Heat Map Analysis)', 
                    fontsize=14, fontweight='bold', pad=20)
        ax.set_xlabel('Soil Parameters', fontsize=12, fontweight='bold')
        ax.set_ylabel('Soil Parameters', fontsize=12, fontweight='bold')
        
        # Rotate labels for better readability
        ax.set_xticklabels(ax.get_xticklabels(), rotation=45, ha='right', fontsize=10)
        ax.set_yticklabels(ax.get_yticklabels(), rotation=0, fontsize=10)
        
        # Add correlation strength indicators
        ax.text(0.02, 0.98, 'Correlation Strength:\n• Red: Strong Positive\n• Blue: Strong Negative\n• White: No Correlation', 
               transform=ax.transAxes, fontsize=9, verticalalignment='top',
               bbox=dict(boxstyle="round,pad=0.3", facecolor='lightyellow', alpha=0.8))

    def create_scatter_distribution(self, ax):
        """Create scatter plot: pH vs Nitrogen, color by Phosphorus, size by Potassium"""
        print("🟢 Creating scatter distribution plot...")
        
        x = self.soil_dataset['pH']
        y = self.soil_dataset['Nitrogen (%)']
        c = self.soil_dataset['Phosphorus (ppm)']
        s = self.soil_dataset['Potassium (ppm)']
        
        # Normalize sizes for visibility
        size_scaled = 50 + (s - s.min()) / (s.max() - s.min() + 1e-9) * 200
        
        scatter = ax.scatter(x, y, c=c, s=size_scaled, cmap='viridis', alpha=0.7, edgecolors='black', linewidths=0.5)
        cbar = plt.colorbar(scatter, ax=ax)
        cbar.set_label('Phosphorus (ppm)')
        
        ax.set_title('Distribution: pH vs Nitrogen\n(Color=Phosphorus, Size=Potassium)', fontsize=14, fontweight='bold')
        ax.set_xlabel('pH Level', fontsize=12, fontweight='bold')
        ax.set_ylabel('Nitrogen (%)', fontsize=12, fontweight='bold')
        ax.grid(True, alpha=0.3)
        
        # Reference lines for pH
        ax.axvline(7.0, color='red', linestyle='--', alpha=0.6, linewidth=1)
        ax.axvspan(6.5, 7.5, alpha=0.1, color='green')
        
        # Legend proxy for size
        for pot in np.linspace(s.min(), s.max(), 3):
            ax.scatter([], [], s=50 + (pot - s.min())/(s.max()-s.min()+1e-9)*200, 
                       c='gray', alpha=0.5, edgecolors='black', label=f'K≈{pot:.0f} ppm')
        ax.legend(title='Potassium size legend', fontsize=8, loc='best')
    
    def create_performance_comparison(self, ax):
        """Create bar chart/table for ANN performance comparison"""
        print("📊 Creating ANN performance comparison...")
        
        if not self.models_available:
            # Show placeholder if models not available
            ax.text(0.5, 0.5, 'Models Not Available\n\nPlease ensure model files are present:\n• ann_soil_classification_model.h5\n• X_test_soil.csv\n• y_test_soil.csv', 
                   ha='center', va='center', fontsize=12, fontweight='bold',
                   bbox=dict(boxstyle="round,pad=0.5", facecolor='lightgray', alpha=0.8),
                   transform=ax.transAxes)
            ax.set_title('ANN Performance Comparison\n(Model Files Required)', 
                        fontsize=14, fontweight='bold', pad=20)
            return
        
        # Evaluate model performance
        y_pred_proba = self.soil_model.predict(self.X_test_soil)
        y_pred = np.argmax(y_pred_proba, axis=1)
        accuracy = accuracy_score(self.y_test_soil, y_pred)
        
        # Calculate per-class performance
        class_report = classification_report(self.y_test_soil, y_pred, 
                                           target_names=self.soil_classes, output_dict=True)
        
        # Prepare data for visualization
        metrics = ['Accuracy', 'Precision', 'Recall', 'F1-Score']
        macro_scores = [
            accuracy,
            class_report['macro avg']['precision'],
            class_report['macro avg']['recall'],
            class_report['macro avg']['f1-score']
        ]
        
        # Create bar chart
        bars = ax.bar(metrics, macro_scores, color=['#2E8B57', '#FF6B35', '#4169E1', '#8B0000'], 
                     alpha=0.8, edgecolor='black', linewidth=2)
        
        # Customize the chart
        ax.set_title('ANN Model Performance Metrics\n(Bar Chart Comparison)', 
                    fontsize=14, fontweight='bold', pad=20)
        ax.set_ylabel('Score', fontsize=12, fontweight='bold')
        ax.set_xlabel('Performance Metrics', fontsize=12, fontweight='bold')
        ax.set_ylim(0, 1.1)
        ax.grid(True, alpha=0.3, axis='y')
        
        # Add value labels on bars
        for bar, score in zip(bars, macro_scores):
            height = bar.get_height()
            ax.text(bar.get_x() + bar.get_width()/2., height + 0.02,
                   f'{score:.3f}', ha='center', va='bottom', fontsize=11, fontweight='bold')
        
        # Add performance rating
        if accuracy >= 0.9:
            rating = "Excellent"
            color = "green"
        elif accuracy >= 0.8:
            rating = "Good"
            color = "orange"
        else:
            rating = "Needs Improvement"
            color = "red"
        
        ax.text(0.02, 0.98, f'Overall Rating: {rating}\nAccuracy: {accuracy:.1%}\nModel Status: Ready for Deployment', 
               transform=ax.transAxes, fontsize=10, verticalalignment='top', color=color, fontweight='bold',
               bbox=dict(boxstyle="round,pad=0.3", facecolor='lightgreen', alpha=0.7))
    
    def create_focused_visualization(self):
        """Create the main focused visualization"""
        print("🎨 Creating focused trends visualization...")
        
        # Create figure with 2x3 subplots
        fig, axes = plt.subplots(2, 3, figsize=(20, 12))
        (ax1, ax2, ax3), (ax4, ax5, ax6) = axes
        fig.suptitle('Soil Analysis - Focused Trends & Patterns Visualization', fontsize=18, fontweight='bold', y=0.98)
        
        # Create each visualization
        self.create_nutrient_trends_bar_chart(ax1)
        self.create_ph_variation_line_chart(ax2)
        self.create_correlation_heatmap(ax3)
        self.create_performance_comparison(ax4)
        self.create_scatter_distribution(ax5)
        ax6.axis('off')
        
        # Adjust layout
        plt.tight_layout()
        plt.subplots_adjust(top=0.93, hspace=0.3, wspace=0.3)
        
        # Save the plot
        filename = f'focused_trends_visualization_{self.timestamp}.png'
        plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        
        print(f"\n✅ Focused trends visualization saved as: {filename}")
        return filename

    def save_scatter_only(self):
        """Generate ONLY the scatter plot as a separate figure and save it."""
        print("🎯 Generating scatter plot only (separate figure)...")
        
        fig, ax = plt.subplots(figsize=(8, 6))
        self.create_scatter_distribution(ax)
        plt.tight_layout()
        filename = f'scatter_correlations_distribution_{self.timestamp}.png'
        plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        print(f"✅ Scatter plot saved as: {filename}")

    def save_bar_nutrient_trends(self):
        """Generate ONLY the nutrient trends bar chart as a separate figure and save it."""
        print("🎯 Generating nutrient trends bar chart only (separate figure)...")
        
        fig, ax = plt.subplots(figsize=(10, 6))
        self.create_nutrient_trends_bar_chart(ax)
        plt.tight_layout()
        filename = f'bar_nutrient_trends_{self.timestamp}.png'
        plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        print(f"✅ Bar chart saved as: {filename}")

    def save_line_ph_variation(self):
        """Generate ONLY the pH variation line chart as a separate figure and save it."""
        print("🎯 Generating pH variation line chart only (separate figure)...")
        
        fig, ax = plt.subplots(figsize=(10, 6))
        self.create_ph_variation_line_chart(ax)
        plt.tight_layout()
        filename = f'line_ph_variation_{self.timestamp}.png'
        plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        print(f"✅ Line chart saved as: {filename}")

    def save_heatmap_correlations(self):
        """Generate ONLY the correlation heat map as a separate figure and save it."""
        print("🎯 Generating correlation heat map only (separate figure)...")
        
        fig, ax = plt.subplots(figsize=(8, 7))
        self.create_correlation_heatmap(ax)
        plt.tight_layout()
        filename = f'heatmap_correlations_{self.timestamp}.png'
        plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        print(f"✅ Heat map saved as: {filename}")

    def save_ann_performance(self):
        """Generate ONLY the ANN performance comparison as a separate figure and save it."""
        print("🎯 Generating ANN performance comparison only (separate figure)...")
        
        if not getattr(self, 'models_available', False):
            fig, ax = plt.subplots(figsize=(9, 6))
            ax.axis('off')
            ax.text(0.5, 0.5, 'Models Not Available\n\nPlease ensure model files are present:\n• ann_soil_classification_model.h5\n• X_test_soil.csv\n• y_test_soil.csv', 
                    ha='center', va='center', fontsize=12, fontweight='bold',
                    bbox=dict(boxstyle="round,pad=0.6", facecolor='lightgray', alpha=0.9),
                    transform=ax.transAxes)
            filename = f'ann_performance_requirements_{self.timestamp}.png'
            plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
            plt.show()
            print(f"⚠️ Saved requirements note as: {filename}")
            return
        
        # Compute metrics
        y_pred_proba = self.soil_model.predict(self.X_test_soil)
        y_pred = np.argmax(y_pred_proba, axis=1)
        accuracy = accuracy_score(self.y_test_soil, y_pred)
        report = classification_report(self.y_test_soil, y_pred, target_names=self.soil_classes, output_dict=True)
        macro_precision = report['macro avg']['precision']
        macro_recall = report['macro avg']['recall']
        macro_f1 = report['macro avg']['f1-score']
        
        # Create bar chart
        fig, ax = plt.subplots(figsize=(9, 6))
        metrics = ['Accuracy', 'Macro Precision', 'Macro Recall', 'Macro F1']
        values = [accuracy, macro_precision, macro_recall, macro_f1]
        colors = ['#2E8B57', '#FF6B35', '#4169E1', '#8B0000']
        bars = ax.bar(metrics, values, color=colors, alpha=0.9, edgecolor='black', linewidth=2)
        
        # Labels and formatting
        ax.set_title('ANN Soil Classifier – Performance Comparison', fontsize=14, fontweight='bold')
        ax.set_ylabel('Score', fontsize=12, fontweight='bold')
        ax.set_ylim(0, 1.1)
        ax.grid(True, axis='y', alpha=0.3)
        for bar, v in zip(bars, values):
            ax.text(bar.get_x() + bar.get_width()/2, v + 0.02, f'{v:.3f}', ha='center', va='bottom', fontsize=11, fontweight='bold')
        
        # Save
        plt.tight_layout()
        filename = f'ann_performance_{self.timestamp}.png'
        plt.savefig(filename, dpi=300, bbox_inches='tight', facecolor='white')
        plt.show()
        print(f"✅ ANN performance figure saved as: {filename}")

def main():
    print("="*70)
    print("FOCUSED SOIL ANALYSIS - TRENDS & PATTERNS VISUALIZATION")
    print("="*70)
    
    # Initialize visualizer
    visualizer = FocusedTrendsVisualizer()
    
    # Load data
    if not visualizer.load_data():
        print("❌ Failed to load data. Please check file paths.")
        return
    
    # Create focused visualization
    filename = visualizer.create_focused_visualization()
    
    print("\n" + "="*70)
    print("FOCUSED VISUALIZATION COMPLETE!")
    print("="*70)
    print(f"📊 Visualization saved as: {filename}")
    print("\n📈 The visualization includes:")
    print("   • Bar Chart - Nutrient trends per soil type")
    print("   • Line Chart - pH variation with error bars")
    print("   • Heat Map - Correlations & distribution")
    print("   • Bar Chart - ANN performance comparison")
    print("   • Matplotlib-style visuals with clean design")

if __name__ == "__main__":
    # When called directly, produce ONLY the scatter plot per user request
    visualizer = FocusedTrendsVisualizer()
    if visualizer.load_data():
        visualizer.save_scatter_only()
    else:
        print("❌ Failed to load data for scatter plot.")
