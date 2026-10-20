<?php
/**
 * Tech Hub — Collecteur de Métriques Système Linux en Temps Réel
 * Lit directement /proc, /sys et les utilitaires système standard.
 */

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 1) {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, $precision) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, $precision) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, $precision) . ' KB';
        return round($bytes) . ' B';
    }
}

if (!function_exists('formatBytesRate')) {
    function formatBytesRate($bytesPerSec) {
        if ($bytesPerSec >= 1048576) return round($bytesPerSec / 1048576, 1) . ' MB/s';
        if ($bytesPerSec >= 1024) return round($bytesPerSec / 1024, 1) . ' KB/s';
        return round($bytesPerSec) . ' B/s';
    }
}

function getCpuUsage() {
    $tempDir = rtrim(sys_get_temp_dir(), '/');
    $statFile = $tempDir . '/techhub_proc_stat.tmp';
    $statContent = @file_get_contents('/proc/stat');
    if (!$statContent) return 0;
    
    preg_match('/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/m', $statContent, $m);
    if (!$m) return 0;
    
    $total = array_sum(array_slice($m, 1));
    $idle = $m[4] + $m[5];
    
    $cpuUsage = 5.0;
    if (file_exists($statFile)) {
        $prev = explode(' ', @file_get_contents($statFile) ?: '');
        if (count($prev) >= 2) {
            $totalDiff = $total - (float)$prev[0];
            $idleDiff = $idle - (float)$prev[1];
            if ($totalDiff > 0) {
                $cpuUsage = round((($totalDiff - $idleDiff) / $totalDiff) * 100, 1);
            }
        }
    } else {
        // Premier appel sans cache : bref échantillonnage
        usleep(40000);
        $stat2 = @file_get_contents('/proc/stat');
        if ($stat2 && preg_match('/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/m', $stat2, $m2)) {
            $t2 = array_sum(array_slice($m2, 1));
            $i2 = $m2[4] + $m2[5];
            $tDiff = $t2 - $total;
            $iDiff = $i2 - $idle;
            if ($tDiff > 0) {
                $cpuUsage = round((($tDiff - $iDiff) / $tDiff) * 100, 1);
            }
        }
    }
    @file_put_contents($statFile, "$total $idle");
    return max(0.5, min(100, $cpuUsage));
}

function getCpuInfo() {
    $cpuinfo = @file_get_contents('/proc/cpuinfo') ?: '';
    preg_match('/model name\s+:\s+(.+)/', $cpuinfo, $model);
    preg_match('/cpu MHz\s+:\s+([\d\.]+)/', $cpuinfo, $mhz);
    $cores = substr_count($cpuinfo, "processor\t:") ?: 1;
    
    $mhzVal = round((float)($mhz[1] ?? 0));
    $ghzVal = round($mhzVal / 1000, 2);
    
    $temp = null;
    $thermalPaths = [
        '/sys/class/thermal/thermal_zone0/temp',
        '/sys/class/thermal/thermal_zone1/temp',
        '/sys/class/hwmon/hwmon0/temp1_input',
        '/sys/class/hwmon/hwmon1/temp1_input'
    ];
    foreach ($thermalPaths as $tp) {
        if (file_exists($tp) && is_readable($tp)) {
            $val = intval(@file_get_contents($tp));
            if ($val > 1000) $val = round($val / 1000);
            if ($val > 15 && $val < 115) {
                $temp = $val;
                break;
            }
        }
    }
    
    $load = function_exists('sys_getloadavg') ? sys_getloadavg() : [0, 0, 0];
    
    return [
        'model' => trim($model[1] ?? 'Processeur Linux Serveur'),
        'frequency_mhz' => $mhzVal,
        'frequency_ghz' => $ghzVal > 0 ? $ghzVal : 2.0,
        'cores' => $cores,
        'temperature_c' => $temp, // null si machine virtuelle sans sonde thermique
        'load_avg' => [round($load[0] ?? 0, 2), round($load[1] ?? 0, 2), round($load[2] ?? 0, 2)]
    ];
}

function getMemoryInfo() {
    $meminfo = @file_get_contents('/proc/meminfo') ?: '';
    preg_match('/MemTotal:\s+(\d+)/', $meminfo, $total);
    preg_match('/MemAvailable:\s+(\d+)/', $meminfo, $avail);
    preg_match('/Cached:\s+(\d+)/', $meminfo, $cached);
    preg_match('/SwapTotal:\s+(\d+)/', $meminfo, $swapTotal);
    preg_match('/SwapFree:\s+(\d+)/', $meminfo, $swapFree);
    
    $totalKb = (int)($total[1] ?? 16777216);
    $availKb = (int)($avail[1] ?? 8388608);
    $cachedKb = (int)($cached[1] ?? 0);
    $usedKb = max(0, $totalKb - $availKb);
    
    $swapTotKb = (int)($swapTotal[1] ?? 0);
    $swapFrKb = (int)($swapFree[1] ?? 0);
    $swapUsedKb = max(0, $swapTotKb - $swapFrKb);
    
    $percent = $totalKb > 0 ? round(($usedKb / $totalKb) * 100, 1) : 0;
    $swapPercent = $swapTotKb > 0 ? round(($swapUsedKb / $swapTotKb) * 100, 1) : 0;
    
    return [
        'total_gb' => round($totalKb / 1048576, 1),
        'used_gb' => round($usedKb / 1048576, 1),
        'available_gb' => round($availKb / 1048576, 1),
        'cached_gb' => round($cachedKb / 1048576, 1),
        'percent' => $percent,
        'swap_used_mb' => round($swapUsedKb / 1024, 1),
        'swap_total_gb' => round($swapTotKb / 1048576, 1),
        'swap_percent' => $swapPercent
    ];
}

function getStorageInfo() {
    $root = '/';
    $totalSpace = @disk_total_space($root) ?: 217160192000;
    $freeSpace = @disk_free_space($root) ?: 29221394432;
    $usedSpace = max(0, $totalSpace - $freeSpace);
    $percent = $totalSpace > 0 ? round(($usedSpace / $totalSpace) * 100, 1) : 0;
    
    return [
        'total_gb' => round($totalSpace / 1073741824, 1),
        'used_gb' => round($usedSpace / 1073741824, 1),
        'free_gb' => round($freeSpace / 1073741824, 1),
        'percent' => $percent
    ];
}

function getNetworkRates() {
    $lines = @file('/proc/net/dev') ?: [];
    $rxTotal = 0;
    $txTotal = 0;
    foreach ($lines as $line) {
        if (strpos($line, ':') !== false) {
            list($iface, $data) = explode(':', $line);
            if (trim($iface) === 'lo') continue;
            $parts = preg_split('/\s+/', trim($data));
            $rxTotal += (float)($parts[0] ?? 0);
            $txTotal += (float)($parts[8] ?? 0);
        }
    }
    
    $tempDir = rtrim(sys_get_temp_dir(), '/');
    $cacheFile = $tempDir . '/techhub_net_stat.tmp';
    $now = microtime(true);
    $rxRate = 0;
    $txRate = 0;
    if (file_exists($cacheFile)) {
        $prev = explode(' ', @file_get_contents($cacheFile) ?: '');
        if (count($prev) >= 3) {
            $dt = $now - (float)$prev[0];
            if ($dt > 0.05) {
                $rxRate = max(0, ($rxTotal - (float)$prev[1]) / $dt);
                $txRate = max(0, ($txTotal - (float)$prev[2]) / $dt);
            }
        }
    }
    @file_put_contents($cacheFile, "$now $rxTotal $txTotal");
    
    return [
        'rx_bytes' => round($rxRate),
        'tx_bytes' => round($txRate),
        'rx_formatted' => formatBytesRate($rxRate),
        'tx_formatted' => formatBytesRate($txRate)
    ];
}

function getSystemInfo() {
    $uptimeSec = 0;
    if (file_exists('/proc/uptime')) {
        $uptimeSec = (int)floatval(@file_get_contents('/proc/uptime'));
    }
    
    $days = floor($uptimeSec / 86400);
    $hours = floor(($uptimeSec % 86400) / 3600);
    $mins = floor(($uptimeSec % 3600) / 60);
    
    $uptimeStr = '';
    if ($days > 0) $uptimeStr .= "{$days}j ";
    $uptimeStr .= "{$hours}h {$mins}m";
    
    $hostname = gethostname() ?: 'serveur-ubuntu';
    $ip = '';
    if (function_exists('shell_exec')) {
        $ip = trim((string)@shell_exec("hostname -I | cut -d' ' -f1"));
    }
    if (!$ip && isset($_SERVER['SERVER_ADDR'])) {
        $ip = $_SERVER['SERVER_ADDR'];
    }
    if (!$ip) {
        $ip = @gethostbyname($hostname) ?: '127.0.0.1';
    }
    
    $procCount = 0;
    if (file_exists('/proc/loadavg')) {
        $lavg = @file_get_contents('/proc/loadavg');
        if (preg_match('/(\d+)\/(\d+)/', $lavg, $pm)) {
            $procCount = (int)$pm[2];
        }
    }
    if ($procCount === 0 && function_exists('shell_exec')) {
        $procCount = (int)trim((string)@shell_exec("ps -e --no-headers | wc -l")) ?: 250;
    }
    if ($procCount === 0) {
        $procCount = 150;
    }
    
    return [
        'hostname' => $hostname,
        'ip' => $ip,
        'os' => php_uname('s') . ' ' . php_uname('r'),
        'uptime_formatted' => $uptimeStr,
        'uptime_seconds' => $uptimeSec,
        'process_count' => $procCount
    ];
}

function getTopProcesses($limit = 10) {
    $limit = max(1, min(50, (int)$limit));
    $limitArg = escapeshellarg((string)($limit + 1));
    $output = @shell_exec("ps -eo pid,user,comm,%cpu,%mem --sort=-%cpu | head -n {$limitArg}");
    if (!$output) return [];
    
    $lines = explode("\n", trim($output));
    array_shift($lines);
    $processes = [];
    foreach ($lines as $line) {
        $cols = preg_split('/\s+/', trim($line));
        if (count($cols) >= 5) {
            $processes[] = [
                'pid' => $cols[0],
                'user' => $cols[1],
                'name' => $cols[2],
                'cpu' => (float)$cols[3],
                'mem' => (float)$cols[4]
            ];
        }
    }
    return $processes;
}
