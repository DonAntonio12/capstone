# MODEL IMPROVEMENT TIPS:
# 1. Try more/less layers or neurons (e.g., add another Dense layer, or increase neurons).
# 2. Increase epochs (e.g., 100) or use EarlyStopping for better convergence.
# 3. Use Dropout layers to reduce overfitting.
# 4. Tune batch_size (try 16, 64, etc.).
# 5. Try different activation functions (e.g., 'tanh').
# 6. Balance your dataset if some classes are rare.
# 7. Feature engineering: add or remove features, or try polynomial features.
# 8. Use learning rate schedules or optimizers (e.g., RMSprop, Adam with lower lr).
# 9. Use K-fold cross-validation for more robust evaluation.
# 10. Clean data: remove outliers, impute missing values, etc.

import pandas as pd
import numpy as np
from tensorflow import keras
from tensorflow.keras import layers
from sklearn.metrics import classification_report, accuracy_score

# 1. Load preprocessed data
X_train = pd.read_csv('resources/X_train.csv')
X_test = pd.read_csv('resources/X_test.csv')
y_train = pd.read_csv('resources/y_train.csv').values.ravel()
y_test = pd.read_csv('resources/y_test.csv').values.ravel()

# 2. Build the ANN model (EASY TO TUNE BELOW)
num_features = X_train.shape[1]
num_classes = len(np.unique(y_train))

model = keras.Sequential([
    layers.Input(shape=(num_features,)),
    layers.Dense(64, activation='relu'),  # Increased neurons
    layers.Dropout(0.2),                 # Dropout for regularization
    layers.Dense(32, activation='relu'),  # Added more layers
    layers.Dense(16, activation='relu'),
    layers.Dense(num_classes, activation='softmax')
])

model.compile(optimizer=keras.optimizers.Adam(learning_rate=0.001),
              loss='sparse_categorical_crossentropy',
              metrics=['accuracy'])

# 3. Train the model (EASY TO TUNE BELOW)
history = model.fit(
    X_train, y_train,
    validation_split=0.1,
    epochs=100,            # Increased epochs
    batch_size=32,         # Try 16, 64, etc.
    verbose=2
)

# 4. Evaluate the model
loss, acc = model.evaluate(X_test, y_test, verbose=0)
print(f'\nTest Accuracy: {acc:.4f}')

# 5. Classification report
y_pred = np.argmax(model.predict(X_test), axis=1)
print('\nClassification Report:')
print(classification_report(y_test, y_pred))

# 6. Save the model
model.save('resources/ann_fertilizer_model.h5')
print('\nModel saved as resources/ann_fertilizer_model.h5') 