<?php

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
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id('id_usuario');
        $table->string('nombre', 100);
        $table->string('email', 100)->unique();
        $table->string('contrasena_hash', 255);
        $table->enum('rol', ['ciudadano', 'administrador', 'autoridad', 'comercio_aliado'])
              ->default('ciudadano');
        $table->integer('puntos')->default(0);
        $table->timestamp('fecha_registro')->useCurrent();
        $table->timestamp('ultima_sesion')->nullable()->useCurrentOnUpdate();
    });
}

};
