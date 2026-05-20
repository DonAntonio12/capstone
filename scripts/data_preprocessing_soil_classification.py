import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler

# 1. Load dataset
csv_path = 'resources/Soil_Classification_Dataset_PH.csv'
df = pd.read_csv(csv_path)

# 2. Select features and target
features = ['Nitrogen (%)', 'Phosphorus (ppm)', 'Potassium (ppm)', 'pH', 'Organic_Carbon (%)']
target = 'Type_of_Soil'

X = df[features].copy()
y = df[target].copy()

# 3. Encode target (soil type)
soil_le = LabelEncoder()
y_encoded = soil_le.fit_transform(y.astype(str))

# 4. Normalize features
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# 5. Train-test split
X_train, X_test, y_train, y_test = train_test_split(
    X_scaled, y_encoded, test_size=0.2, random_state=42, stratify=y_encoded
)

# 6. Save processed data
pd.DataFrame(X_train, columns=features).to_csv('resources/X_train_soil.csv', index=False)
pd.DataFrame(X_test, columns=features).to_csv('resources/X_test_soil.csv', index=False)
pd.DataFrame(y_train, columns=['SoilType']).to_csv('resources/y_train_soil.csv', index=False)
pd.DataFrame(y_test, columns=['SoilType']).to_csv('resources/y_test_soil.csv', index=False)
pd.Series(soil_le.classes_).to_csv('resources/soil_class_labels.csv', index=False, header=False)

print('Data preprocessing complete!')
print('Train shape:', X_train.shape, y_train.shape)
print('Test shape:', X_test.shape, y_test.shape)
print('Soil classes:', list(soil_le.classes_)) 