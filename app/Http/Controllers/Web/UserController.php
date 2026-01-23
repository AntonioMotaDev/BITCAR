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

        $users = User::latest()
        ->with(['userDocuments' => function ($query) {
            $query->select([
                'id',
                'user_id', 
                'vehicle_id',
                'file_name',
                'path',
                'expiration_date',
                'status'
            ]);
        }])
        ->paginate(15);

        $roleOptions = [
            'admin' => ['label' => 'Administrador'],
            'supervisor' => ['label' => 'Supervisor'],
            'operador' => ['label' => 'Operador']
        ];
        return view('users.index', compact('users','roleOptions'));
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
            $imageName = $user->id . '.' . $image->getClientOriginalExtension();
            
            $path = $image->storeAs('users', $imageName, 'public');
            
            // Guardar solo el path en la base de datos
            $user->image = $path;
            $user->save();
        }
        

        // Redirigir con mensaje de éxito
        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function show($id)
    {
        // Buscar el documento
        $document = Document::find($id);
        
        if (!$document) {
            return response()->json([
                'error' => 'Documento no encontrado'
            ], 404);
        }
        
        // Verificar si el archivo existe
        if (!Storage::exists($document->file_path)) {
            return response()->json([
                'error' => 'El archivo no existe en el servidor'
            ], 404);
        }
        
        // Obtener el tipo MIME
        $mimeType = Storage::mimeType($document->file_path);
        
        // Preparar headers
        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ];
        
        // Retornar el archivo
        return response(Storage::get($document->file_path), 200, $headers);
    }

    public function download(User $user, Document $document)
    {
        // Validar que el documento pertenece al usuario
        if ($document->user_id != $user->id) {
            return response()->json([
                'error' => 'No autorizado'
            ], 403);
        }
        
        // Verificar que el archivo existe
        if (!Storage::exists($document->path)) {
            return response()->json([
                'error' => 'Archivo no encontrado'
            ], 404);
        }
        
        // Descargar el archivo
        return Storage::download($document->file_path, $document->file_name);
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        try{

            $user = User::findOrFail($id);
        
            // Validar datos
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:8|confirmed',
                'role' => 'required|string',
                'status' => 'required|in:active,inactive',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
            
            // Eliminar la imagen si delete_image=1
            if ($request->has('delete_image') && $request->input('delete_image') == '1' && $user->image) {
            
                if (Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
                
                // Limpiar el campo en la base de datos
                $user->image = null;
            }
            
            // Si se subió una nueva imagen
            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
                
                // Obtener extensión del archivo
                $extension = $request->file('image')->getClientOriginalExtension();
                
                $fileName = "users/{$id}.{$extension}";
                
                // Guardar la imagen
                $request->file('image')->storeAs('users', "{$id}.{$extension}", 'public');
                $user->image = $fileName;
            }
            
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];
            $user->status = $validatedData['status'];
            
            if ($request->filled('password')) {
                $user->password = Hash::make($validatedData['password']);
            }
            
            $user->save();
        
            return redirect()->route('users.index')
                ->with('success', 'Usuario actualizado correctamente.');

        }catch(\Exception $e){
            return redirect()->route('users.index')
                ->with('error', 'El usuario no se pudo actualizar: ' . $e->getMessage());
        }
        
    }

    public function uploadDocument(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'file_name' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,txt|max:5120',
            'expiration_date' => 'nullable|date',
        ]);

        $file = $request->file('document_file');
        $userId = $validated['user_id'];
        
        // Crear nombre único
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        
        // Limpiar nombre original (remover caracteres especiales)
        $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '', $originalName);
        $cleanName = substr($cleanName, 0, 50);
        
        $timestamp = time();
        $fileName = 'users_'. $userId . '_' . $timestamp . '_' . $cleanName . '.' . $extension;
        $filePath = 'documents/' . $fileName;
        
        try {
            // Guardar archivo
            $file->storeAs('documents', $fileName, 'public');

            if (isset($validated['expiration_date']) && $validated['expiration_date']) {
                $expirationDate = \Carbon\Carbon::parse($validated['expiration_date']);
                
                if ($expirationDate->isPast()) {
                    $status = 'vencido';
                }
            }
            
            $document = new Document();
            $document->user_id = $userId;
            $document->file_name = $validated['file_name'];
            $document->path = $filePath;
            $document->expiration_date = $validated['expiration_date'] ?? null;
            $document->status = $status ?? 'activo';
            $document->save();
            
            return redirect()->route('users.index')
                ->with('success', 'Documento "' . $validated['file_name'] . '" subido con éxito');
                
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Error al subir el documento: ' . $e->getMessage());
        }
    }
    

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado');
    }
}
