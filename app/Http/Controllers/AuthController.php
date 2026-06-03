<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * Refactorizado para actuar como proxy hacia el microservicio Auth-Service.
 */
class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validación fallida', 'errors' => $validator->errors()], 422);
            }

            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Delegar al microservicio vía Gateway
        try {
            $response = Http::timeout(10)->post(config('services.gateway.url') . '/api/auth/login', $request->all());
        } catch (Throwable $exception) {
            return $this->gatewayUnavailable($request);
        }

        $body = $response->json();

        // Si la petición espera JSON (API / AJAX), devolver tal cual
        if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
            return response()->json($body, $response->status());
        }

        if (!empty($body['success']) && $body['success']) {
            $token = $body['data']['token'] ?? null;
            $user = User::where('email', $request->email)->first();

         

            if ($user) {
                Auth::login($user);
                

            }

            $redirect = $this->redirectToRoleDashboard($user);

            if ($token) {
                return $redirect->withCookie(cookie('quibdo_token', $token, 60 * 24 * 30));
            }

            return $redirect->with('success', $body['message'] ?? 'Login exitoso.');
        }

        $redirect = redirect()->back()->withInput();
        if (!empty($body['errors'])) {
            $redirect = $redirect->withErrors($body['errors']);
        } else {
            $redirect = $redirect->with('error', $body['message'] ?? 'Error de autenticación');
        }

        return $redirect;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error de validación', 'errors' => $validator->errors()], 422);
            }

            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Delegar al microservicio vía Gateway
        try {
            $response = Http::timeout(10)->post(config('services.gateway.url') . '/api/auth/register', $request->all());
        } catch (Throwable $exception) {
            return $this->gatewayUnavailable($request);
        }

        $body = $response->json();

        if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
            return response()->json($body, $response->status());
        }

        if (!empty($body['success']) && $body['success']) {
            $token = $body['data']['token'] ?? null;
            $user = User::where('email', $request->email)->first();

            if ($user) {
                Auth::login($user);
            }

            $redirect = $this->redirectToRoleDashboard($user);

            if ($token) {
                return $redirect->withCookie(cookie('quibdo_token', $token, 60 * 24 * 30));
            }

            return $redirect->with('success', $body['message'] ?? 'Registro exitoso.');
        }

        $redirect = redirect()->back()->withInput();
        if (!empty($body['errors'])) {
            $redirect = $redirect->withErrors($body['errors']);
        } else {
            $redirect = $redirect->with('error', $body['message'] ?? 'Error al registrar');
        }

        return $redirect;
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken() ?: $request->cookie('quibdo_token');

        if (!$token) {
            if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Token no encontrado.', 'data' => null], 401);
            }

            return redirect('/')->with('error', 'No se pudo cerrar sesión: token no encontrado.');
        }

        try {
            $response = Http::timeout(10)->withToken($token)->post(config('services.gateway.url') . '/api/auth/logout');
        } catch (Throwable $exception) {
            $response = null;
        }

        Auth::logout();

        $forgetCookie = Cookie::forget('quibdo_token');

        if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
            return response()->json(
                $response ? $response->json() : ['success' => true, 'message' => 'Sesión local cerrada.', 'data' => null],
                $response ? $response->status() : 200
            )->withCookie($forgetCookie);
        }

        return redirect('/')->withCookie($forgetCookie)->with('success', 'Sesión cerrada correctamente.');
    }

    private function redirectToRoleDashboard(?User $user)
    {
        $role = $user?->rol;

        return match ($role) {
            'admin' => redirect('/admin'),
            'comercio' => redirect('/comercio'),
            'autoridad' => redirect('/autoridad'),
            default => redirect('/dashboard'),
        };
    }

    private function gatewayUnavailable(Request $request)
    {
        $message = 'El servicio de autenticación no está disponible. Revisa que el gateway y auth-service estén encendidos.';

        if ($request->wantsJson() || $request->isJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $message, 'data' => null], 503);
        }

        return redirect()->back()->withInput()->with('error', $message);
    }
}
