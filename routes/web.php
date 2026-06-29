<?php

use App\Services\Contracts\AuthServiceInterface;
use App\Services\NotificationGatewayService;
use App\Support\AuthSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (AuthServiceInterface $authService) {
    if ((session('user_token') || session('user_refresh_token')) && AuthSession::renew($authService)) {
        return redirect()->route('home');
    }

    // Limpiar la sesión al ingresar a la pantalla de login sin credenciales renovables
    AuthSession::clear();

    return view('login');
});

Route::post('/api/auth/login', function (Request $request, AuthServiceInterface $authService) {
    // Obtener las credenciales enviadas
    $username = $request->input('username') ?? $request->input('email');
    $password = $request->input('password');

    if (! $username || ! $password) {
        return response()->json([
            'success' => false,
            'message' => 'Credenciales inválidas o faltantes.',
        ], 400);
    }

    // Ejecutar el inicio de sesión a través del servicio (Mock o gRPC)
    $result = $authService->login($username, $password);

    if ($result['success']) {
        // Almacenar el token y la información del usuario en la sesión de Laravel
        AuthSession::store($result);

        return response()->json($result, 200);
    }

    return response()->json($result, 401);
});

Route::post('/api/auth/refresh', function (Request $request, AuthServiceInterface $authService) {
    $refreshToken = $request->input('refreshToken') ?: session('user_refresh_token');

    if (! $refreshToken) {
        return response()->json([
            'success' => false,
            'message' => 'Refresh token no disponible.',
        ], 401);
    }

    $result = $authService->refresh($refreshToken);

    if ($result['success'] ?? false) {
        AuthSession::store($result);

        return response()->json($result, 200);
    }

    AuthSession::clear();

    return response()->json($result, 401);
});

Route::post('/api/auth/logout', function (Request $request, AuthServiceInterface $authService) {
    $token = $request->input('token') ?: session('user_token');
    $refreshToken = $request->input('refreshToken') ?: session('user_refresh_token');

    $result = $authService->logout($token, $refreshToken);

    AuthSession::clear();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'success' => (bool) ($result['success'] ?? false),
        'revoked' => (bool) ($result['revoked'] ?? false),
        'message' => $result['message'] ?? 'Sesión cerrada.',
    ], ($result['success'] ?? false) ? 200 : 202);
});

Route::get('/home', function (NotificationGatewayService $notificationService, AuthServiceInterface $authService) {
    if (! AuthSession::renew($authService) || ! session('user_token') || ! session('user')) {
        return redirect('/');
    }

    $notificationsPayload = $notificationService->recent(
        session('user_token'),
        session('user'),
        3
    );

    return view('student.home', [
        'notifications_payload' => $notificationsPayload,
    ]);
})->name('home');

Route::get('/dashboard', function (NotificationGatewayService $notificationService, AuthServiceInterface $authService) {
    if (! AuthSession::renew($authService) || ! session('user_token') || ! session('user')) {
        return redirect('/');
    }

    $notificationsPayload = $notificationService->recent(
        session('user_token'),
        session('user'),
        3
    );

    return view('student.home', [
        'notifications_payload' => $notificationsPayload,
    ]);
})->name('dashboard');
