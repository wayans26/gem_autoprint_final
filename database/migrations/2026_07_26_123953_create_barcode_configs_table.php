<?php

use App\Models\barcode_config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barcode_configs', function (Blueprint $table) {
            $table->id();
            $table->string('config_name');
            $table->string('config_value');
            $table->timestamps();
        });

        $data_configs = [
            [
                'config_name' => 'qr_size',
                'config_value' => '220'
            ],
            [
                'config_name' => 'qr_margin',
                'config_value' => '1'
            ],
            [
                'config_name' => 'error_correction',
                'config_value' => 'H'
            ],
            [
                'config_name' => 'paper_width',
                'config_value' => '104.1'
            ],
            [
                'config_name' => 'paper_height',
                'config_value' => '76.2'
            ],
            [
                'config_name' => 'qr_position_bottom',
                'config_value' => '31' //mm
            ],
            [
                'config_name' => 'safe_area_bottom',
                'config_value' => '16' //mm
            ],
            [
                'config_name' => 'safe_area_top',
                'config_value' => '16' //mm
            ],
            [
                'config_name' => 'safe_area_right',
                'config_value' => '4' //mm
            ],
            [
                'config_name' => 'safe_area_left',
                'config_value' => '4' //mm
            ],
        ];

        foreach ($data_configs as $key => $value) {
            barcode_config::create([
                'config_name' => $value['config_name'],
                'config_value' => $value['config_value']
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barcode_configs');
    }
};
