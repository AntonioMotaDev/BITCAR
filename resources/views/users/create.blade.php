<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-neutral-800 leading-tight">
                Nuevo Usuario
            </h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-neutral-300 rounded-lg font-medium text-sm text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="p-6 space-y-6">
                    <!-- Name & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
                                Nombre Completo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('name') border-red-500 @enderror"
                                   required autofocus>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Phone & Role -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-neutral-700 mb-2">
                                Teléfono
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-neutral-700 mb-2">
                                Rol <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="role"
                                    class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('role') border-red-500 @enderror"
                                    required>
                                <option value="">Seleccionar...</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                <option value="operador" {{ old('role', 'operador') == 'operador' ? 'selected' : '' }}>Operador</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password & Confirmation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-neutral-700 mb-2">
                                Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500 @error('password') border-red-500 @enderror"
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-neutral-500">Mínimo 8 caracteres</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-neutral-700 mb-2">
                                Confirmar Contraseña <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-neutral-500 focus:border-neutral-500"
                                   required>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 flex justify-end space-x-3">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-white border border-neutral-300 rounded-lg font-medium text-sm text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2">
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
