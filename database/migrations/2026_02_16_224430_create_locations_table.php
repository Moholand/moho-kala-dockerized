<?php

use App\Models\User\Location;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->enum('type', [Location::TYPE_PROVINCE, Location::TYPE_CITY]);
            $table->foreignId('parent_id')->nullable()->constrained('locations')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
