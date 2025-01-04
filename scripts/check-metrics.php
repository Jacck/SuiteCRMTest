<?php
// Threshold values for architectural metrics
const THRESHOLDS = [
    'maxAfferentCoupling' => 13,    // Maximum incoming dependencies
    'maxEfferentCoupling' => 15,    // Maximum outgoing dependencies
    'maxCyclomaticComplexity' => 15, // Maximum complexity per class
];

$xml = new SimpleXMLElement(file_get_contents($argv[1]));
$violations = [];

foreach ($xml->xpath('//class') as $class) {
    $metrics = $class->metrics[0];
    
    if ((int)$metrics['ca'] > THRESHOLDS['maxAfferentCoupling']) {
        $violations[] = sprintf(
            "High incoming coupling in %s: %d > %d",
            $class['name'],
            $metrics['ca'],
            THRESHOLDS['maxAfferentCoupling']
        );
    }
    
    if ((int)$metrics['ce'] > THRESHOLDS['maxEfferentCoupling']) {
        $violations[] = sprintf(
            "High outgoing coupling in %s: %d > %d",
            $class['name'],
            $metrics['ce'],
            THRESHOLDS['maxEfferentCoupling']
        );
    }
}

if (!empty($violations)) {
    echo "Architecture violations found:\n";
    echo implode("\n", $violations);
    exit(1);
}

echo "Architecture checks passed!\n";
exit(0);
