from fastapi import FastAPI
from pydantic import BaseModel
import numpy as np
import pandas as pd
from tensorflow import keras
from sklearn.preprocessing import StandardScaler, LabelEncoder
from fastapi.middleware.cors import CORSMiddleware

app = FastAPI()

# Add this CORS middleware setup after creating the app
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Or specify ["http://127.0.0.1:8000"] for more security
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- Load Soil Model and Data ---
soil_model = keras.models.load_model('resources/ann_soil_classification_model.h5')
soil_classes = pd.read_csv('resources/soil_class_labels.csv', header=None).squeeze().astype(str).values
df_soil = pd.read_csv('resources/Soil_Classification_Dataset_PH.csv')
soil_features = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
soil_scaler = StandardScaler().fit(df_soil[soil_features])

# --- Load Fertilizer Model and Data ---
df_fert = pd.read_csv('resources/fertilizer_recommendation_dataset.csv')
fert_features = ['Nitrogen', 'Phosphorous', 'Potassium', 'PH', 'Soil', 'Crop']
fert_le = LabelEncoder().fit(df_fert['Fertilizer'].astype(str))
soil_le_fert = LabelEncoder().fit(df_fert['Soil'].astype(str))
crop_le_fert = LabelEncoder().fit(df_fert['Crop'].astype(str))
fert_scaler = StandardScaler().fit(df_fert[['Nitrogen', 'Phosphorous', 'Potassium', 'PH']])
try:
    fert_model = keras.models.load_model('resources/ann_fertilizer_model.h5')
except Exception:
    fert_model = None

# --- Fertilizer NPK content (example, adjust as needed) ---
FERT_CONTENT = {
    'Urea': {'N': 0.46, 'P': 0.0, 'K': 0.0},
    'DAP': {'N': 0.18, 'P': 0.46, 'K': 0.0},
    'Muriate of Potash': {'N': 0.0, 'P': 0.0, 'K': 0.60},
    'Compost': {'N': 0.01, 'P': 0.01, 'K': 0.01},
    # Add more as needed
}

class PredictionInput(BaseModel):
    nitrogen: float
    phosphorus: float
    potassium: float
    ph: float
    organic_carbon: float
    crop: str = None  # Optional, for fertilizer rec

@app.post('/predict')
def predict(input: PredictionInput):
    # --- Soil Prediction ---
    X_soil = np.array([[input.nitrogen, input.phosphorus, input.potassium, input.ph, input.organic_carbon]])
    X_soil_scaled = soil_scaler.transform(X_soil)
    soil_probs = soil_model.predict(X_soil_scaled)[0]
    pred_soil_idx = np.argmax(soil_probs)
    pred_soil = soil_classes[pred_soil_idx]
    row = df_soil[df_soil['Type_of_Soil'] == pred_soil].iloc[0]
    soil_info = {
        "soil_type": str(pred_soil),
        "texture": str(row['Texture']),
        "ideal_npk_ph": {
            "N": float(row['Nitrogen (%)']),
            "P": float(row['Phosphorus (ppm)']),
            "K": float(row['Potassium (ppm)']),
            "pH": float(row['pH'])
        },
        "recommended_crops": str(row['Crop_Recommendation']),
        "class_probabilities": {str(soil): float(prob) for soil, prob in zip(soil_classes, soil_probs)}
    }

    # --- Fertilizer Recommendation ---
    fert_result = None
    # Use default crop if none provided, or use the first recommended crop from soil analysis
    default_crop = input.crop if input.crop else "Rice"  # Default to Rice if no crop specified
    if fert_model is not None:
        # Use ANN for fertilizer type
        # Map soil/crop to encoded
        soil_enc = int(soil_le_fert.transform([pred_soil])[0]) if pred_soil in soil_le_fert.classes_ else 0
        crop_enc = int(crop_le_fert.transform([default_crop])[0]) if default_crop in crop_le_fert.classes_ else 0
        fert_X = np.array([[input.nitrogen, input.phosphorus, input.potassium, input.ph, soil_enc, crop_enc]])
        fert_X_scaled = fert_X.copy()
        fert_X_scaled[:, :4] = fert_scaler.transform(fert_X[:, :4])
        fert_probs = fert_model.predict(fert_X_scaled)[0]
        fert_idx = int(np.argmax(fert_probs))
        fert_name = str(fert_le.inverse_transform([fert_idx])[0])
        # Compute deficiency and amount
        # Use ideal NPK from soil_info
        def_n = max(0, float(soil_info['ideal_npk_ph']['N']) - input.nitrogen)
        def_p = max(0, float(soil_info['ideal_npk_ph']['P']) - input.phosphorus)
        def_k = max(0, float(soil_info['ideal_npk_ph']['K']) - input.potassium)
        fert_amounts = {}
        for fert, content in FERT_CONTENT.items():
            amt = 0
            if content['N'] > 0 and def_n > 0:
                amt += def_n / content['N']
            if content['P'] > 0 and def_p > 0:
                amt += def_p / content['P']
            if content['K'] > 0 and def_k > 0:
                amt += def_k / content['K']
            if amt > 0:
                fert_amounts[str(fert)] = round(float(amt), 2)
        fert_result = {
            "recommended_fertilizer": fert_name,
            "fertilizer_probabilities": {str(fert_le.inverse_transform([i])[0]): float(prob) for i, prob in enumerate(fert_probs)},
            "deficiency": {"N": float(def_n), "P": float(def_p), "K": float(def_k)},
            "recommended_amounts": fert_amounts
        }
    print('DEBUG soil_info:', soil_info)
    print('DEBUG fert_result:', fert_result)
    print('DEBUG pred_soil:', pred_soil)
    return {"soil": soil_info, "fertilizer": fert_result} 