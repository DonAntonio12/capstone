import pandas as pd
import numpy as np
from tensorflow import keras
from sklearn.preprocessing import StandardScaler

# Load model and class labels
model = keras.models.load_model('resources/ann_soil_classification_model.h5')
soil_classes = pd.read_csv('resources/soil_class_labels.csv', header=None).squeeze().astype(str).values

# Load full dataset to fit scaler (for consistency) and for reference info
csv_path = 'resources/Soil_Classification_Dataset_PH.csv'
df_full = pd.read_csv(csv_path)
features = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
scaler = StandardScaler().fit(df_full[features])

# Example input (replace with your own or use sys.argv for CLI)
example = {
    'Nitrogen (%)': 0.18,
    'Phosphorus (ppm)': 18,
    'Potassium (ppm)': 140,
    'pH': 6.2,
    'Organic_Carbon (%)': 1.3
}

# Prepare input
num_scaled = scaler.transform([[example[f] for f in features]])

# Predict
probs = model.predict(num_scaled)[0]
pred_class = np.argmax(probs)
pred_soil = soil_classes[pred_class]

print(f"Predicted Soil Type: {pred_soil}")
print(f"Class probabilities: {dict(zip(soil_classes, np.round(probs, 3)))}")

# Lookup info for predicted soil type
row = df_full[df_full['Type_of_Soil'] == pred_soil].iloc[0]
print("\n--- Soil Information & Crop Recommendation ---")
print(f"Texture: {row['Texture']}")
print(f"Ideal N: {row['Nitrogen (%)']}%, P: {row['Phosphorus (ppm)']} ppm, K: {row['Potassium (ppm)']} ppm, pH: {row['pH']}")
print(f"Recommended crops: {row['Crop_Recommendation']}") 