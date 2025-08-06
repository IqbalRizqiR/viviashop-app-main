<?php

// HTTP Migration Script untuk ViVia Shop
// URL: https://your-domain.com/migrate.php?token=SECRET_TOKEN_123

// Security token - ganti dengan token rahasia Anda
$SECRET_TOKEN = 'SECRET_TOKEN_123';

// Cek token
if (!isset($_GET['token']) || $_GET['token'] !== $SECRET_TOKEN) {
    die('Access denied. Invalid token.');
}

// Bootstrap Laravel dengan cara yang lebih aman
try {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    } else {
        die('Composer autoload not found. Please run composer install.');
    }
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
} catch (Exception $e) {
    die('Laravel bootstrap failed: ' . $e->getMessage());
}

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    echo "<h1>ViVia Shop Migration Script</h1>";
    echo "<p>Starting migration process...</p>";
    
    // Cek apakah tabel sub_attribute_options sudah ada
    if (!Schema::hasTable('sub_attribute_options')) {
        echo "<p>Creating sub_attribute_options table...</p>";
        
        Schema::create('sub_attribute_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('attribute_option_id');
            $table->foreign('attribute_option_id')->references('id')->on('attribute_options')->onDelete('cascade');
            $table->timestamps();
        });
        
        echo "<p style='color: green;'>✓ Table sub_attribute_options created successfully!</p>";
    } else {
        echo "<p style='color: orange;'>! Table sub_attribute_options already exists, skipping...</p>";
    }
    
    // Insert sample data untuk demonstrasi 3 tingkat atribut
    echo "<p>Inserting sample data...</p>";
    
    // Level 1: Attribute (Art Paper)
    $attribute = DB::table('attributes')->where('code', 'APP')->first();
    if (!$attribute) {
        $attributeId = DB::table('attributes')->insertGetId([
            'code' => 'APP',
            'name' => 'Art Paper',
            'type' => 'select',
            'validation' => null,
            'is_required' => false,
            'is_unique' => false,
            'is_filterable' => true,
            'is_configurable' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "<p style='color: green;'>✓ Created attribute: Art Paper (APP)</p>";
    } else {
        $attributeId = $attribute->id;
        echo "<p style='color: orange;'>! Attribute Art Paper already exists</p>";
    }
    
    // Level 2: Attribute Options (Gramatur)
    $gramaturOptions = ['100gr', '120gr', '150gr', '200gr', '230gr', '260gr'];
    
    foreach ($gramaturOptions as $gramatur) {
        $existingOption = DB::table('attribute_options')
            ->where('attribute_id', $attributeId)
            ->where('name', $gramatur)
            ->first();
            
        if (!$existingOption) {
            $optionId = DB::table('attribute_options')->insertGetId([
                'name' => $gramatur,
                'attribute_id' => $attributeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "<p style='color: green;'>✓ Created option: {$gramatur}</p>";
            
            // Level 3: Sub Attribute Options (Tipe Cetak)
            $subOptions = ['Vinyl', 'Digital Print', 'Offset Print', 'UV Print'];
            
            foreach ($subOptions as $subOption) {
                DB::table('sub_attribute_options')->insert([
                    'name' => $subOption,
                    'attribute_option_id' => $optionId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "<p style='color: blue;'>  ✓ Created sub-option: {$subOption} for {$gramatur}</p>";
            }
        } else {
            echo "<p style='color: orange;'>! Option {$gramatur} already exists</p>";
        }
    }
    
    echo "<h2 style='color: green;'>Migration completed successfully!</h2>";
    echo "<p><strong>Struktur yang telah dibuat:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Level 1:</strong> Art Paper (APP) - Atribut utama</li>";
    echo "<li><strong>Level 2:</strong> 100gr, 120gr, 150gr, 200gr, 230gr, 260gr - Varian gramatur</li>";
    echo "<li><strong>Level 3:</strong> Vinyl, Digital Print, Offset Print, UV Print - Tipe cetak untuk setiap gramatur</li>";
    echo "</ul>";
    
    echo "<p><a href='/admin/attributes' style='background: #007cba; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Go to Attributes Management</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>Migration failed!</h2>";
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database connection and try again.</p>";
}

function now() {
    return date('Y-m-d H:i:s');
}
?>
