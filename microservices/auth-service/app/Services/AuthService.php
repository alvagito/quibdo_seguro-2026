<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class AuthService
{
    /**
     * Autenticar usuario y generar token Sanctum.
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'success' => false,
                'message' => 'Las credenciales no coinciden.',
                'data'    => null,
            ];
        }

        // Revocar tokens anteriores del mismo dispositivo
        $user->tokens()->where('name', 'api-token')->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Login exitoso.',
            'data'    => [
                'token' => $token,
                'user'  => $this->formatUser($user),
            ],
        ];
    }

    /**
     * Registrar nuevo usuario.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'rol'      => 'normal',
            'puntos'   => 0,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'success' => true,
            'message' => 'Registro exitoso.',
            'data'    => [
                'token' => $token,
                'user'  => $this->formatUser($user),
            ],
        ];
    }

    /**
     * Cerrar sesión revocando el token actual.
     */
    public function logout(?User $user): array
    {
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado.',
                'data'    => null,
            ];
        }

        $user->currentAccessToken()?->delete();

        return [
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
            'data'    => null,
        ];
    }

    /**
     * Obtener perfil del usuario autenticado.
     */
    public function profile(?User $user): array
    {
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuario no autenticado.',
                'data'    => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Perfil obtenido.',
            'data'    => $this->formatUser($user),
        ];
    }

    /**
     * Extraer el usuario a partir del token Bearer en la request.
     */
    public function userFromRequest(Request $request): ?User
    {
        $token = $request->bearerToken();

        if (!$token) {
            return null;
        }

        $modelClass = \Laravel\Sanctum\Sanctum::personalAccessTokenModel() ?? \Laravel\Sanctum\PersonalAccessToken::class;

        $personalAccessToken = $modelClass::findToken($token);

        if (!$personalAccessToken) {
            return null;
        }

        return $personalAccessToken->tokenable;
    }

    /**
     * Obtener usuario por ID (para validación interna entre servicios).
     */
    public function getUserById(string $id): ?array
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        return $this->formatUser($user);
    }

    /**
     * Obtener usuarios por rol para comunicación interna.
     */
    public function getUsersByRole(string $rol): array
    {
        return User::where('rol', $rol)
            ->get()
            ->map(fn(User $user) => $this->formatUser($user))
            ->toArray();
    }

    /**
     * Actualizar puntos de un usuario desde servicios internos.
     */
    public function updateUserPoints(string $id, int $puntos): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $user->puntos = $puntos;
        $user->save();

        return true;
    }

    /**
     * Formatear datos del usuario para respuesta API.
     */
    private function formatUser(User $user): array
    {
        return [
            'id'     => (string) $user->_id,
            'nombre' => $user->nombre,
            'email'  => $user->email,
            'rol'    => $user->rol,
            'puntos' => (int) $user->puntos,
        ];
    }
}
