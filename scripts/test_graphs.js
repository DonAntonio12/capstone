// Test script for sensor data vs predicted values comparison
console.log('Testing sensor data vs predicted values comparison...');

// Example: Sensor detected values vs ANN predicted ideal values
const testData = {
    detected: { 
        n: 0.25,    // Sensor detected N = 0.25%
        p: 45,      // Sensor detected P = 45 ppm
        k: 180,     // Sensor detected K = 180 ppm
        ph: 6.5     // Sensor detected pH = 6.5
    },
    predicted: { 
        n: 0.20,    // ANN predicted ideal N = 0.20%
        p: 20,      // ANN predicted ideal P = 20 ppm
        k: 150,     // ANN predicted ideal K = 150 ppm
        ph: 6.4     // ANN predicted ideal pH = 6.4
    }
};

console.log('Example Data:');
console.log('📊 Detected by Sensor:', testData.detected);
console.log('🎯 Predicted by ANN:', testData.predicted);

// Test chart update functions
function testComparisonCharts() {
    console.log('Testing comparison charts...');
    
    // Test NPK comparison
    if (typeof updateNPKChart === 'function') {
        updateNPKChart(
            testData.detected.n, 
            testData.detected.p, 
            testData.detected.k,
            testData.predicted.n, 
            testData.predicted.p, 
            testData.predicted.k
        );
        console.log('✅ NPK comparison chart updated');
    }
    
    // Test pH comparison
    if (typeof updatePHChart === 'function') {
        updatePHChart(
            testData.detected.ph,
            testData.predicted.ph
        );
        console.log('✅ pH comparison chart updated');
    }
}

console.log('Comparison graph test script loaded successfully!');
console.log('To test comparison charts, run: testComparisonCharts()'); 