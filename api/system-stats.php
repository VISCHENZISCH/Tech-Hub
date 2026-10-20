<?php
/**
 * Tech Hub — API Télémétrie Système en Temps Réel
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once __DIR__ . '/../includes/system_metrics.php';

$response = [
    'success' => true,
    'timestamp' => time(),
    'time_formatted' => date('H:i:s'),
    'cpu' => array_merge(['usage_percent' => getCpuUsage()], getCpuInfo()),
    'memory' => getMemoryInfo(),
    'storage' => getStorageInfo(),
    'network' => getNetworkRates(),
    'system' => getSystemInfo(),
    'processes' => getTopProcesses(10)
];

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
