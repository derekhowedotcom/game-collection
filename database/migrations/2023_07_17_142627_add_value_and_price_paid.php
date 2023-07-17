<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if(Schema::hasTable('collection_items')){
            Schema::table('collection_items', function (Blueprint $table) {
                $table->float('value')->nullable()->after('barcode');
                $table->float('price_paid')->nullable()->after('value');
            });
        }
    }

    public function down(): void
    {
        if(!Schema::hasTable('collection_items')){
            if(Schema::hasColumn('collection_items', 'value') && Schema::hasColumn('collection_items', 'price_paid')) {
                Schema::table('collection_items', function (Blueprint $table) {
                    $table->dropColumn('value');
                    $table->dropColumn('price_paid');
                });
            }
        }
    }
};
