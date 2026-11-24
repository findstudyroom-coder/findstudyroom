<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assigned = \Illuminate\Support\Facades\DB::table('admin_library_access')
    ->where('admin_id', 2)
    ->whereIn('library_id', [1024, 1025])
    ->get();
    
echo "Is library 1024 or 1025 assigned to admin 2?\n";
echo "Count: " . $assigned->count() . "\n";
echo json_encode($assigned);

$totalAssigned = \Illuminate\Support\Facades\DB::table('admin_library_access')
    ->where('admin_id', 2)
    ->count();
echo "\nTotal assigned to admin 2: " . $totalAssigned;

$totalLibraries = \App\Models\Library::count();
echo "\nTotal libraries in DB: " . $totalLibraries;

