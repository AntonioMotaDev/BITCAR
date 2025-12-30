<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\VehicleResource;
use App\Services\VehicleAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(
        private VehicleAssignmentService $assignmentService
    ) {}

    /**
     * Login de operador
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        if (! $user->isActive()) {
            return response()->json([
                'message' => 'Usuario inactivo',
            ], 403);
        }

        // Crear token
        $token = $user->createToken('mobile-app')->plainTextToken;

        // Obtener vehículo asignado
        $vehicle = $this->assignmentService->getActiveVehicle($user);

        return response()->json([
            'message' => 'Login exitoso',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token,
                'vehicle' => $vehicle ? new VehicleResource($vehicle) : null,
            ],
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout exitoso',
        ]);
    }

    /**
     * Obtener usuario autenticado con vehículo asignado
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $vehicle = $this->assignmentService->getActiveVehicle($user);

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'vehicle' => $vehicle ? new VehicleResource($vehicle) : null,
            ],
        ]);
    }
}
