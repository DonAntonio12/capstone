import pandas as pd
import numpy as np
from tensorflow import keras
from sklearn.preprocessing import StandardScaler

# Soil info reference table (as before)
SOIL_INFO = {
    "Loam": {
        "Texture": "Balanced – crumbly",
        "N_range": "0.15–0.30%",
        "P_range": "15–30 ppm",
        "K_range": "100–200 ppm",
        "pH_range": "6.0–7.0",
        "Remarks": "Best all-around soil for agriculture",
        "Crop Recommendation": "Rice, tomato, okra, eggplant, corn, peanut",
        "Why Suitable": "Balanced nutrients, good drainage and retention"
    },
    "Clay (Luwad)": {
        "Texture": "Fine, sticky when wet",
        "N_range": "0.10–0.25%",
        "P_range": "10–20 ppm",
        "K_range": ">200 ppm",
        "pH_range": "5.5–6.5",
        "Remarks": "Compacts easily, retains water",
        "Crop Recommendation": "Palay, gabi, kamote, kangkong",
        "Why Suitable": "Holds water well, ideal for water-loving crops"
    },
    "Sandy Soil": {
        "Texture": "Coarse, gritty",
        "N_range": "<0.10%",
        "P_range": "<10 ppm",
        "K_range": "<100 ppm",
        "pH_range": "5.0–6.0",
        "Remarks": "Poor nutrient retention, fast drainage",
        "Crop Recommendation": "Pakwan, mani, patani, melon, carrots, onions",
        "Why Suitable": "Root crops thrive due to loose texture"
    },
    "Silty Soil": {
        "Texture": "Smooth, slippery when wet",
        "N_range": "0.20–0.30%",
        "P_range": "15–25 ppm",
        "K_range": "100–200 ppm",
        "pH_range": "6.0–7.0",
        "Remarks": "High fertility, can compact easily",
        "Crop Recommendation": "Lettuce, pechay, cabbage, okra",
        "Why Suitable": "Good moisture retention and nutrient content"
    },
    "Peaty Soil": {
        "Texture": "Dark, spongy, moist",
        "N_range": ">0.30%",
        "P_range": "5–10 ppm",
        "K_range": "50–100 ppm",
        "pH_range": "4.0–5.0",
        "Remarks": "Acidic, high organic matter",
        "Crop Recommendation": "Upo, kalabasa, gabi, kangkong",
        "Why Suitable": "Moisture-rich, good for swamp-tolerant crops"
    },
    "Volcanic (Andosol)": {
        "Texture": "Soft, crumbly, porous",
        "N_range": "0.25–0.35%",
        "P_range": "15–30 ppm",
        "K_range": ">200 ppm",
        "pH_range": "5.5–6.5",
        "Remarks": "Very fertile, rich in minerals",
        "Crop Recommendation": "Pineapple, coffee, carrots, sayote, repolyo",
        "Why Suitable": "Naturally nutrient-rich from volcanic ash"
    },
    "Alluvial Soil": {
        "Texture": "Fine, loose, fertile",
        "N_range": "0.20–0.30%",
        "P_range": "15–25 ppm",
        "K_range": "150–250 ppm",
        "pH_range": "6.0–7.5",
        "Remarks": "From river sediments; very productive",
        "Crop Recommendation": "Palay, munggo, ampalaya, okra",
        "Why Suitable": "High fertility due to river deposits"
    },
    "Calcareous Soil": {
        "Texture": "Light, powdery or gravelly",
        "N_range": "<0.10%",
        "P_range": ">30 ppm",
        "K_range": ">200 ppm",
        "pH_range": "7.5–8.5",
        "Remarks": "Alkaline, can limit nutrient absorption",
        "Crop Recommendation": "Grapes, kamatis, munggo, cassava",
        "Why Suitable": "Suitable for alkaline-tolerant crops"
    },
    "Mountain/Upland Soil": {
        "Texture": "Rocky, shallow",
        "N_range": "<0.10%",
        "P_range": "<10 ppm",
        "K_range": "100–200 ppm",
        "pH_range": "5.0–6.0",
        "Remarks": "Susceptible to erosion",
        "Crop Recommendation": "Coffee, cacao, sayote, camote, ginger",
        "Why Suitable": "Suitable for highland and perennial crops"
    },
    "Hydrosol (Swamp)": {
        "Texture": "Waterlogged, silty or clayey",
        "N_range": "0.10–0.20%",
        "P_range": "<15 ppm",
        "K_range": "<100 ppm",
        "pH_range": "4.5–5.5",
        "Remarks": "Always submerged or moist",
        "Crop Recommendation": "Palay, kangkong, gabi, water spinach",
        "Why Suitable": "Tolerates submerged or wet conditions"
    },
    "Acidic Soil": {
        "Texture": "Varies (often silty or clayey)",
        "N_range": "Low–Medium",
        "P_range": "Low",
        "K_range": "Low–Medium",
        "pH_range": "<6.0",
        "Remarks": "Low pH, may need liming for most crops",
        "Crop Recommendation": "Root crops, gabi, kangkong, palay (with tolerant varieties)",
        "Why Suitable": "Some crops tolerate acidity, but liming is often needed"
    },
    "Alkaline Soil": {
        "Texture": "Varies (often sandy or calcareous)",
        "N_range": "Low",
        "P_range": "High",
        "K_range": "Medium–High",
        "pH_range": ">7.5",
        "Remarks": "High pH, can limit nutrient absorption",
        "Crop Recommendation": "Grapes, kamatis, munggo, cassava, alkaline-tolerant crops",
        "Why Suitable": "Suitable for alkaline-tolerant crops"
    },
    "Neutral Soil": {
        "Texture": "Balanced",
        "N_range": "Medium",
        "P_range": "Medium",
        "K_range": "Medium",
        "pH_range": "6.5–7.5",
        "Remarks": "Ideal for most crops",
        "Crop Recommendation": "Rice, corn, vegetables, most field crops",
        "Why Suitable": "Optimal pH and nutrients for general agriculture"
    },
}

# Mapping from ANN output to classic soil type
SOIL_MAPPING = {
    "Loamy Soil": "Loam",
    "Sandy Soil": "Sandy Soil",
    "Clayey Soil": "Clay (Luwad)",
    "Peaty Soil": "Peaty Soil",
    "Acidic Soil": "Loam",  # or another, as best fit
    "Neutral Soil": "Loam",
    "Alkaline Soil": "Calcareous Soil"
}

# Load model and label classes
model = keras.models.load_model('resources/ann_soil_model.h5')
soil_classes = pd.read_csv('resources/soil_classes.csv', header=None).squeeze().astype(str).values
print('Soil classes loaded:', soil_classes)

# Load full dataset to fit scaler (for consistency)
df_full = pd.read_csv('resources/fertilizer_recommendation_dataset.csv')
scaler = StandardScaler().fit(df_full[['Nitrogen', 'Phosphorous', 'Potassium', 'PH']])

# Example input (replace with your own or use sys.argv for CLI)
example = {
    'Nitrogen': 70,
    'Phosphorous': 30,
    'Potassium': 50,
    'PH': 6.5
}

# Prepare input
num_scaled = scaler.transform([[example['Nitrogen'], example['Phosphorous'], example['Potassium'], example['PH']]])[0]
X_input = np.array([num_scaled])

# Predict
probs = model.predict(X_input)
pred_class = np.argmax(probs, axis=1)[0]
pred_soil = str(soil_classes[pred_class]).strip()

print(f"Predicted Soil Type (ANN): {pred_soil}")
classic_soil = SOIL_MAPPING.get(pred_soil, pred_soil)
print(f"Mapped Classic Soil Type: {classic_soil}")
print(f"Class probabilities: {probs[0]}")

# Show reference info if available
info = SOIL_INFO.get(classic_soil)
if info:
    print("\n--- Soil Information & Crop Recommendation ---")
    print(f"Texture: {info['Texture']}")
    print(f"Ideal N: {info['N_range']}, P: {info['P_range']}, K: {info['K_range']}, pH: {info['pH_range']}")
    print(f"Remarks: {info['Remarks']}")
    print(f"Recommended Crops: {info['Crop Recommendation']}")
    print(f"Why Suitable: {info['Why Suitable']}")
else:
    print("\n[INFO] No reference data for this soil type.") 