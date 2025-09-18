<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevokedTokensTableReallyFinal20250918 extends Migration
{
    public function up()
    {
        Schema::create('revoked_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('jti')->unique();
            $table->timestamp('revoked_at')->useCurrent();
        });
    }
    public function down()
    {
        Schema::dropIfExists('revoked_tokens');
    }
}
