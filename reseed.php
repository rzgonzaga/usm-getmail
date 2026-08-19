<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $maxId = DB::table('email_requests')->max('id');
    DB::statement("DBCC CHECKIDENT ('email_requests', RESEED, $maxId)");
    echo "Reseeded successfully to $maxId.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
