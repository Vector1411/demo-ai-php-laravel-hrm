<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTableFinal20250918 extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->string('resource');
            $table->string('action');
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->ipAddress('ip')->nullable();
            $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
