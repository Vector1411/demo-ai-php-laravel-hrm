<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTableFinal20250918 extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('full_name');
            $table->enum('role', ['ADMIN', 'HR', 'MANAGER', 'EMPLOYEE'])->default('EMPLOYEE');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });
    }
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
