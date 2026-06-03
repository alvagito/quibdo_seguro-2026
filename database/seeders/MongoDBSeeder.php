<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recompensa;
use App\Models\Oferta;
use Illuminate\Support\Facades\Hash;

class MongoDBSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        $admin = User::create([
            'nombre' => 'Administrador',
            'email' => 'admin@quibdo.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'puntos' => 0
        ]);

        // Crear usuario normal
        $usuario = User::create([
            'nombre' => 'Usuario Normal',
            'email' => 'usuario@quibdo.com',
            'password' => Hash::make('usuario123'),
            'rol' => 'normal',
            'puntos' => 150
        ]);

        User::create([
            'nombre' => 'Autoridad Local',
            'email' => 'autoridad@quibdo.com',
            'password' => Hash::make('autoridad123'),
            'rol' => 'autoridad',
            'puntos' => 0
        ]);

        // Crear comercio
        $comercio = User::create([
            'nombre' => 'Restaurante El Sabor',
            'email' => 'comercio@quibdo.com',
            'password' => Hash::make('comercio123'),
            'rol' => 'comercio',
            'puntos' => 0
        ]);

        // Crear otro comercio
        $comercio2 = User::create([
            'nombre' => 'Tienda La Esquina',
            'email' => 'tienda@quibdo.com',
            'password' => Hash::make('tienda123'),
            'rol' => 'comercio',
            'puntos' => 0
        ]);

        // Crear recompensas del sistema
        Recompensa::create([
            'nombre' => 'Reconocimiento Ciudadano Destacado',
            'puntos' => 500
        ]);

        Recompensa::create([
            'nombre' => 'Certificado de Participación Comunitaria',
            'puntos' => 300
        ]);

        Recompensa::create([
            'nombre' => 'Insignia de Colaborador Activo',
            'puntos' => 200
        ]);

        // Crear ofertas de comercios
        Oferta::create([
            'id_comercio' => $comercio->_id,
            'titulo' => '20% de descuento en almuerzo',
            'descripcion' => 'Disfruta de un 20% de descuento en cualquier almuerzo del día',
            'puntos' => 50
        ]);

        Oferta::create([
            'id_comercio' => $comercio->_id,
            'titulo' => 'Postre gratis',
            'descripcion' => 'Obtén un postre gratis con tu comida',
            'puntos' => 30
        ]);

        Oferta::create([
            'id_comercio' => $comercio2->_id,
            'titulo' => '10% de descuento en compras',
            'descripcion' => 'Descuento del 10% en todas tus compras',
            'puntos' => 40
        ]);

        Oferta::create([
            'id_comercio' => $comercio2->_id,
            'titulo' => 'Producto gratis',
            'descripcion' => 'Lleva un producto gratis en compras mayores a $20.000',
            'puntos' => 80
        ]);
    }
}
