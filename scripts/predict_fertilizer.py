import pandas as pd
import numpy as np
from tensorflow import keras
from sklearn.preprocessing import LabelEncoder, StandardScaler
import sys

# --- CONFIGURABLE THRESHOLDS PER SOIL/CROP (example values, adjust as needed) ---
# These should be based on agronomy tables for your crops/soils
NPK_THRESHOLDS = {
    ('Loamy Soil', 'rice'): {'N': 80, 'P': 40, 'K': 60},
    ('Loamy Soil', 'wheat'): {'N': 90, 'P': 45, 'K': 65},
    # Add more (soil, crop) pairs as needed
}

# Fertilizer NPK content (example, adjust as needed)
FERT_CONTENT = {
    'Urea': {'N': 0.46, 'P': 0.0, 'K': 0.0},
    'DAP': {'N': 0.18, 'P': 0.46, 'K': 0.0},
    'Muriate of Potash': {'N': 0.0, 'P': 0.0, 'K': 0.60},
    'Compost': {'N': 0.01, 'P': 0.01, 'K': 0.01},
    # Add more as needed
}

# Load encoders and scaler using training data
X_train = pd.read_csv('resources/X_train.csv')
y_train = pd.read_csv('resources/y_train.csv').values.ravel()
df_full = pd.read_csv('resources/fertilizer_recommendation_dataset.csv')

# Fit encoders on full data for consistency
soil_le = LabelEncoder().fit(df_full['Soil'].astype(str))
crop_le = LabelEncoder().fit(df_full['Crop'].astype(str))
fert_le = LabelEncoder().fit(df_full['Fertilizer'].astype(str))
scaler = StandardScaler().fit(df_full[['Nitrogen', 'Phosphorous', 'Potassium', 'PH']])

# Load model
model = keras.models.load_model('resources/ann_fertilizer_model.h5')

# Example input (replace with your own or use sys.argv for CLI)
example = {
    'Nitrogen': 70,
    'Phosphorous': 30,
    'Potassium': 50,
    'PH': 6.5,
    'Soil': 'Loamy Soil',
    'Crop': 'rice'
}

# --- ANN Prediction ---
soil_encoded = soil_le.transform([example['Soil']])[0]
crop_encoded = crop_le.transform([example['Crop']])[0]
num_scaled = scaler.transform([[example['Nitrogen'], example['Phosphorous'], example['Potassium'], example['PH']]])[0]
X_input = np.array([[num_scaled[0], num_scaled[1], num_scaled[2], num_scaled[3], soil_encoded, crop_encoded]])
probs = model.predict(X_input)
pred_class = np.argmax(probs, axis=1)[0]
pred_fert = fert_le.inverse_transform([pred_class])[0]

print(f"Predicted Fertilizer (ANN): {pred_fert}")
print(f"Class probabilities: {probs[0]}")

# --- Rule-based Multi-Fertilizer Dosage Recommendation ---
soil = example['Soil']
crop = example['Crop']
current_N = example['Nitrogen']
current_P = example['Phosphorous']
current_K = example['Potassium']

# Get target NPK for this soil/crop
thresholds = NPK_THRESHOLDS.get((soil, crop))
if thresholds is None:
    print(f"[WARNING] No NPK thresholds set for ({soil}, {crop}). Please update NPK_THRESHOLDS in the script.")
    thresholds = {'N': 80, 'P': 40, 'K': 60}  # fallback default

deficiency = {
    'N': max(thresholds['N'] - current_N, 0),
    'P': max(thresholds['P'] - current_P, 0),
    'K': max(thresholds['K'] - current_K, 0)
}

print(f"NPK Deficiency: {deficiency}")

# Recommend a combination of fertilizers to address all deficiencies
recommendations = []
for nutrient, needed in deficiency.items():
    if needed > 0:
        # Find all fertilizers that can supply this nutrient
        for fert, content in FERT_CONTENT.items():
            if content[nutrient] > 0:
                amount = needed / content[nutrient]
                recommendations.append((fert, amount, nutrient, needed, content[nutrient]))
                break  # Recommend the first matching fertilizer for each nutrient

if recommendations:
    print("\nRecommended Fertilizer Applications:")
    for fert, amount, nutrient, needed, content in recommendations:
        print(f"- {fert}: {amount:.2f} kg/ha to address {nutrient} deficiency (needs {needed} kg/ha, {fert} supplies {content*100:.0f}% {nutrient})")
else:
    print("No significant NPK deficiency detected. No fertilizer application needed.") 