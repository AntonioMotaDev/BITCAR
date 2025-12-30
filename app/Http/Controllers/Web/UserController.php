<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        User::create($request->validated());
        return redirect()->route('users.index')->with('success', 'Usuario creado');
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
