import pandas as pd
import numpy as np
from tensorflow import keras
from tensorflow.keras import layers
from sklearn.metrics import classification_report, accuracy_score

# 1. Load preprocessed data
X_train = pd.read_csv('resources/X_train_soil.csv')
X_test = pd.read_csv('resources/X_test_soil.csv')
y_train = pd.read_csv('resources/y_train_soil.csv').values.ravel()
y_test = pd.read_csv('resources/y_test_soil.csv').values.ravel()
soil_classes = pd.read_csv('resources/soil_class_labels.csv', header=None).squeeze().astype(str).values

# 2. Build the ANN model
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

# 3. Train the model
history = model.fit(
    X_train, y_train,
    epochs=50,
    batch_size=8,
    validation_split=0.2,
    verbose=2
)

# 4. Evaluate on test set
preds = model.predict(X_test)
pred_classes = np.argmax(preds, axis=1)
acc = accuracy_score(y_test, pred_classes)
print(f'\nTest Accuracy: {acc:.4f}')
print('\nClassification Report:')
print(classification_report(y_test, pred_classes, target_names=soil_classes))

# 5. Save the model and class labels
model.save('resources/ann_soil_classification_model.h5')
print('\nModel saved as resources/ann_soil_classification_model.h5') 