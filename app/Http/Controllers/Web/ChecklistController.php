<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChecklistRequest;
use App\Http\Requests\UpdateChecklistRequest;
use App\Models\Checklist;
use App\Services\ChecklistService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ChecklistController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private ChecklistService $checklistService
    ) {}

    public function index(): View
    {
        $this->authorize('viewAny', Checklist::class);
        
        $checklists = Checklist::with('items')->latest()->paginate(15);
        return view('checklists.index', compact('checklists'));
    }

    public function create(): View
    {
        $this->authorize('create', Checklist::class);
        return view('checklists.create');
    }

    public function store(StoreChecklistRequest $request): RedirectResponse
    {
        $this->authorize('create', Checklist::class);
        $this->checklistService->createChecklist($request->validated());
        return redirect()->route('checklists.index')->with('success', 'Checklist creado');
    }

    public function show(Checklist $checklist): View
    {
        $this->authorize('view', $checklist);
        $checklist->load('items');
        return view('checklists.show', compact('checklist'));
    }

    public function edit(Checklist $checklist): View
    {
        $this->authorize('update', $checklist);
        $checklist->load('items');
        return view('checklists.edit', compact('checklist'));
    }

    public function update(UpdateChecklistRequest $request, Checklist $checklist): RedirectResponse
    {
        $this->authorize('update', $checklist);
        $this->checklistService->updateChecklist($checklist, $request->validated());
        return redirect()->route('checklists.index')->with('success', 'Checklist actualizado');
    }

    public function destroy(Checklist $checklist): RedirectResponse
    {
        $this->authorize('delete', $checklist);
        $checklist->delete();
        return redirect()->route('checklists.index')->with('success', 'Checklist eliminado');
    }
}
