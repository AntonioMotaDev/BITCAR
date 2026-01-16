<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::latest()->paginate(15);
        $documents = Document::all();
        $roleOptions = [
            'admin' => ['label' => 'Administrador'],
            'supervisor' => ['label' => 'Supervisor'],
            'operador' => ['label' => 'Operador']
        ];
        return view('users.index', compact('users','documents','roleOptions'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    /**
     * Almacenar un nuevo usuario
     */
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,supervisor,operador',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Crear usuario
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];
        $user->status = 'active';
        $user->save();

        // Manejar imagen si se subió
        if ($request->hasFile('image')) {
            // Obtener el archivo
            $image = $request->file('image');
            
            // Generar un nombre único para la imagen
            $imageName = $user->id . '_' . $image->getClientOriginalName();
            
            // Guardar la imagen en storage/app/public/users
            $path = $image->storeAs('users', $imageName, 'public');
            
            // Guardar solo el path en la base de datos
            $user->image = $path; // Ejemplo: "users/1705251234_abc123.jpg"
            $user->save();
        }
        

        // Redirigir con mensaje de éxito
        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function show(User $user): View
    {
        $this->authorize('view', $user);
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $user->update($request->validated());
        return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado');
    }
}
