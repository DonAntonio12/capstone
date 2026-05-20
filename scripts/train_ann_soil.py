import pandas as pd
import numpy as np
from tensorflow import keras
from tensorflow.keras import layers
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler
from sklearn.metrics import classification_report

# 1. Load dataset
csv_path = 'resources/fertilizer_recommendation_dataset.csv'
df = pd.read_csv(csv_path)

# 2. Select features and target
features = ['Nitrogen', 'Phosphorous', 'Potassium', 'PH']
target = 'Soil'

X = df[features].copy()
y = df[target].copy()

# 3. Encode target (Soil)
soil_le = LabelEncoder()
y_encoded = soil_le.fit_transform(y.astype(str))

# 4. Normalize numerical features
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# 5. Split dataset
X_train, X_test, y_train, y_test = train_test_split(X_scaled, y_encoded, test_size=0.2, random_state=42)

# 6. Build the ANN model
num_features = X_train.shape[1]
num_classes = len(np.unique(y_train))

model = keras.Sequential([
    layers.Input(shape=(num_features,)),
    layers.Dense(32, activation='relu'),
    layers.Dense(16, activation='relu'),
    layers.Dense(num_classes, activation='softmax')
])

model.compile(optimizer='adam',
              loss='sparse_categorical_crossentropy',
              metrics=['accuracy'])

# 7. Train the model
history = model.fit(
    X_train, y_train,
    validation_split=0.1,
    epochs=50,
    batch_size=32,
    verbose=2
)

# 8. Evaluate the model
loss, acc = model.evaluate(X_test, y_test, verbose=0)
print(f'\nTest Accuracy: {acc:.4f}')

# 9. Classification report
y_pred = np.argmax(model.predict(X_test), axis=1)
print('\nClassification Report:')
print(classification_report(y_test, y_pred, target_names=soil_le.classes_))

# 10. Save the model and encoders
model.save('resources/ann_soil_model.h5')
# Save actual soil type names as strings, no index, no header
soil_class_names = [str(cls) for cls in soil_le.classes_]
pd.Series(soil_class_names).to_csv('resources/soil_classes.csv', index=False, header=False)
print('\nModel saved as resources/ann_soil_model.h5')
print('Soil label classes saved as resources/soil_classes.csv:')
print(soil_class_names) 