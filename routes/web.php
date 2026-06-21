<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Limpiar la sesión al ingresar a la pantalla de login
    session()->forget(['user', 'user_token']);
    return view('login');
});

Route::post('/api/auth/login', function (\Illuminate\Http\Request $request, \App\Services\Contracts\AuthServiceInterface $authService) {
    // Obtener las credenciales enviadas
    $username = $request->input('username') ?? $request->input('email');
    $password = $request->input('password');

    if (!$username || !$password) {
        return response()->json([
            'success' => false,
            'message' => 'Credenciales inválidas o faltantes.'
        ], 400);
    }

    // Ejecutar el inicio de sesión a través del servicio (Mock o gRPC)
    $result = $authService->login($username, $password);

    if ($result['success']) {
        // Almacenar el token y la información del usuario en la sesión de Laravel
        session([
            'user_token' => $result['token'],
            'user' => $result['user'] ?? null
        ]);
        return response()->json($result, 200);
    }

    return response()->json($result, 401);
});

Route::get('/home', function () {
    return view('student.home');
})->name('home');

Route::get('/dashboard', function () {
    return view('student.home');
})->name('dashboard');

