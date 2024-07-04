<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // foreignIdFor: The foreignIdFor method adds a {column}_id
    // equivalent column for a given model class. The column type will be UNSIGNED
    // BIGINT, CHAR(36), or CHAR(26) depending on the model key type: example --> For(User::class)

    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title',2048)->nullable()->comment("title posted");
            $table->string('slug',2048)->comment();
            $table->string('thumbnail',2048)->nullable()->comments('preview image');
            $table->longText('body')->comment('text of topic');
            $table->boolean('active')->default(false)->comment('status posted');
            $table->datetime('published_at')->nullable()->comment('created_at');
            $table->foreignIdFor(App\Models\User::class,'user_id')->comment('foreign key of user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
