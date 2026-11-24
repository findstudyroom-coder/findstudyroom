<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logs = \App\Models\LibraryCallLog::all();
echo "Total logs: " . $logs->count() . "\n";
if ($logs->count() > 0) {
    echo "Sample log: " . json_encode($logs->first()->toArray()) . "\n";
}

$assignedLibraryIds = \App\Models\Library::pluck('id')->toArray();
echo "Total assigned libraries: " . count($assignedLibraryIds) . "\n";

$latestLogIds = \App\Models\LibraryCallLog::whereIn('library_id', $assignedLibraryIds)
    ->selectRaw('MAX(id) as id')
    ->groupBy('library_id')
    ->pluck('id');

echo "Latest log IDs count: " . $latestLogIds->count() . "\n";
echo "Latest log IDs: " . json_encode($latestLogIds) . "\n";

$latestLogs = \App\Models\LibraryCallLog::whereIn('id', $latestLogIds)->get();
echo "Latest logs count: " . $latestLogs->count() . "\n";

