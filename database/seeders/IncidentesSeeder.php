<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Incidente;
use App\Models\User;
use Carbon\Carbon;

class IncidentesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener un usuario normal para asignar los incidentes
        $usuario = User::where('rol', 'normal')->first();
        
        if (!$usuario) {
            echo "No hay usuarios normales. Ejecuta primero MongoDBSeeder.\n";
            return;
        }

        // Crear incidentes de diferentes tipos y fechas
        
        // Incidentes de HOY (últimas 24 horas)
        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 1, // Robo
            'id_estado' => 1,
            'latitud' => '5.6947',
            'longitud' => '-76.6611',
            'descripcion' => 'Robo a mano armada en la esquina del parque. Dos personas en motocicleta.',
            'fecha_hora_incidente' => Carbon::now()->subHours(2),
            'fecha_hora_reporte' => Carbon::now()->subHours(2),
            'direccion_aproximada' => 'Calle 26 con Carrera 5, Centro',
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 2, // Accidente
            'id_estado' => 1,
            'latitud' => '5.6950',
            'longitud' => '-76.6615',
            'descripcion' => 'Choque entre dos vehículos en la avenida principal. No hay heridos graves.',
            'fecha_hora_incidente' => Carbon::now()->subHours(5),
            'fecha_hora_reporte' => Carbon::now()->subHours(5),
            'direccion_aproximada' => 'Avenida Simón Bolívar con Calle 30',
            'created_at' => Carbon::now()->subHours(5),
            'updated_at' => Carbon::now()->subHours(5),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 3, // Violencia
            'id_estado' => 1,
            'latitud' => '5.6945',
            'longitud' => '-76.6620',
            'descripcion' => 'Pelea callejera entre varias personas. Situación controlada por la policía.',
            'fecha_hora_incidente' => Carbon::now()->subHours(8),
            'fecha_hora_reporte' => Carbon::now()->subHours(8),
            'direccion_aproximada' => 'Barrio Kennedy, Calle 15',
            'created_at' => Carbon::now()->subHours(8),
            'updated_at' => Carbon::now()->subHours(8),
        ]);

        // Incidentes de ESTA SEMANA (2-7 días atrás)
        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 1, // Robo
            'id_estado' => 1,
            'latitud' => '5.6955',
            'longitud' => '-76.6625',
            'descripcion' => 'Hurto de celular en transporte público. Persona desconocida.',
            'fecha_hora_incidente' => Carbon::now()->subDays(2),
            'fecha_hora_reporte' => Carbon::now()->subDays(2),
            'direccion_aproximada' => 'Ruta de bus, Carrera 10',
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 4, // Otro
            'id_estado' => 1,
            'latitud' => '5.6960',
            'longitud' => '-76.6630',
            'descripcion' => 'Árbol caído bloqueando la vía. Requiere atención de las autoridades.',
            'fecha_hora_incidente' => Carbon::now()->subDays(3),
            'fecha_hora_reporte' => Carbon::now()->subDays(3),
            'direccion_aproximada' => 'Vía al aeropuerto, Km 2',
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(3),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 2, // Accidente
            'id_estado' => 1,
            'latitud' => '5.6965',
            'longitud' => '-76.6635',
            'descripcion' => 'Accidente de tránsito con motocicleta. Conductor con heridas leves.',
            'fecha_hora_incidente' => Carbon::now()->subDays(5),
            'fecha_hora_reporte' => Carbon::now()->subDays(5),
            'direccion_aproximada' => 'Calle 40 con Carrera 8',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        // Incidentes MÁS ANTIGUOS (más de 7 días)
        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 1, // Robo
            'id_estado' => 1,
            'latitud' => '5.6970',
            'longitud' => '-76.6640',
            'descripcion' => 'Intento de robo frustrado por vecinos. Sospechosos huyeron.',
            'fecha_hora_incidente' => Carbon::now()->subDays(10),
            'fecha_hora_reporte' => Carbon::now()->subDays(10),
            'direccion_aproximada' => 'Barrio La Yesca, Calle 22',
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 3, // Violencia
            'id_estado' => 1,
            'latitud' => '5.6975',
            'longitud' => '-76.6645',
            'descripcion' => 'Riña en establecimiento comercial. Policía intervino.',
            'fecha_hora_incidente' => Carbon::now()->subDays(15),
            'fecha_hora_reporte' => Carbon::now()->subDays(15),
            'direccion_aproximada' => 'Centro Comercial, Piso 2',
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(15),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 4, // Otro
            'id_estado' => 1,
            'latitud' => '5.6980',
            'longitud' => '-76.6650',
            'descripcion' => 'Fuga de agua en vía pública. Afecta el tránsito vehicular.',
            'fecha_hora_incidente' => Carbon::now()->subDays(20),
            'fecha_hora_reporte' => Carbon::now()->subDays(20),
            'direccion_aproximada' => 'Carrera 12 con Calle 35',
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20),
        ]);

        Incidente::create([
            'id_usuario' => $usuario->_id,
            'id_tipo_incidente' => 2, // Accidente
            'id_estado' => 1,
            'latitud' => '5.6985',
            'longitud' => '-76.6655',
            'descripcion' => 'Colisión múltiple en hora pico. Tres vehículos involucrados.',
            'fecha_hora_incidente' => Carbon::now()->subDays(25),
            'fecha_hora_reporte' => Carbon::now()->subDays(25),
            'direccion_aproximada' => 'Puente del río Atrato',
            'created_at' => Carbon::now()->subDays(25),
            'updated_at' => Carbon::now()->subDays(25),
        ]);

        echo "✅ Se crearon 10 incidentes de prueba:\n";
        echo "   - 3 de HOY (últimas 24 horas)\n";
        echo "   - 3 de ESTA SEMANA (2-7 días)\n";
        echo "   - 4 MÁS ANTIGUOS (más de 7 días)\n";
        echo "   - Tipos: 4 Robos, 3 Accidentes, 2 Violencia, 1 Otro\n";
    }
}
