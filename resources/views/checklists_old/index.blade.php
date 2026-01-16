<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-neutral-800 leading-tight">
                Checklists de Inspección
            </h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <a href="{{ route('checklists.create') }}" class="inline-flex items-center px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Checklist
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Total</p>
                        <p class="text-2xl font-semibold text-neutral-900">{{ $checklists->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Activos</p>
                        <p class="text-2xl font-semibold text-green-600">{{ $checklists->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg border border-neutral-200 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-500">Inactivos</p>
                        <p class="text-2xl font-semibold text-neutral-600">{{ $checklists->where('is_active', false)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checklists Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($checklists as $checklist)
                <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-neutral-900 mb-2">{{ $checklist->name }}</h3>
                                @if($checklist->description)
                                    <p class="text-sm text-neutral-500 mb-4">{{ Str::limit($checklist->description, 100) }}</p>
                                @endif
                            </div>
                            @if($checklist->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600 ml-2">
                                    Inactivo
                                </span>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="mt-4 pt-4 border-t border-neutral-200">
                            <div class="flex items-center text-sm text-neutral-500">
                                <svg class="w-5 h-5 mr-2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span>{{ $checklist->items->count() }} items</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-3 bg-neutral-50 border-t border-neutral-200 flex justify-end space-x-2">
                        <a href="{{ route('checklists.show', $checklist) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-neutral-300 rounded-lg font-medium text-xs text-neutral-700 hover:bg-neutral-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Ver
                        </a>
                        @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                            <a href="{{ route('checklists.edit', $checklist) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-neutral-300 rounded-lg font-medium text-xs text-neutral-700 hover:bg-neutral-50">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>
                            <form action="{{ route('checklists.destroy', $checklist) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este checklist?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-red-300 rounded-lg font-medium text-xs text-red-700 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg border border-neutral-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-neutral-900">No hay checklists</h3>
                        <p class="mt-1 text-sm text-neutral-500">Comience creando un nuevo checklist de inspección.</p>
                        @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                            <div class="mt-6">
                                <a href="{{ route('checklists.create') }}" class="inline-flex items-center px-4 py-2 bg-neutral-800 border border-transparent rounded-lg font-medium text-sm text-white hover:bg-neutral-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Nuevo Checklist
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($checklists->hasPages())
            <div class="mt-6">
                {{ $checklists->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
