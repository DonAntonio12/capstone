import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler
import os
os.makedirs('resources', exist_ok=True)

# 1. Load dataset
csv_path = 'resources/fertilizer_recommendation_dataset.csv'
df = pd.read_csv(csv_path)

# 2. Select features and target
features = ['Nitrogen', 'Phosphorous', 'Potassium', 'PH', 'Soil', 'Crop']
target = 'Fertilizer'

X = df[features].copy()
y = df[target].copy()

# 3. Encode categorical features
for col in ['Soil', 'Crop']:
    le = LabelEncoder()
    X[col] = le.fit_transform(X[col].astype(str))

# Encode target
le_fert = LabelEncoder()
y = le_fert.fit_transform(y.astype(str))

# 4. Normalize numerical features
scaler = StandardScaler()
X[['Nitrogen', 'Phosphorous', 'Potassium', 'PH']] = scaler.fit_transform(X[['Nitrogen', 'Phosphorous', 'Potassium', 'PH']])

# 5. Split dataset
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 6. Save processed data for ANN training
X_train.to_csv('resources/X_train.csv', index=False)
X_test.to_csv('resources/X_test.csv', index=False)
pd.DataFrame(y_train, columns=['Fertilizer']).to_csv('resources/y_train.csv', index=False)
pd.DataFrame(y_test, columns=['Fertilizer']).to_csv('resources/y_test.csv', index=False)

print('Data preprocessing complete!')
print('Train shape:', X_train.shape, y_train.shape)
print('Test shape:', X_test.shape, y_test.shape) 