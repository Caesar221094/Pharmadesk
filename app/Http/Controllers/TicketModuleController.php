<?php

namespace App\Http\Controllers;

use App\Models\TicketModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $modules = TicketModule::orderBy('name')->get();

        return view('ticket-modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('ticket-modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:50', 'unique:ticket_modules,slug'],
        ]);

        TicketModule::create($data);

        return redirect()->route('ticket-modules.index')->with('success', 'Modul berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketModule $ticketModule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketModule $ticketModule): View
    {
        return view('ticket-modules.edit', ['module' => $ticketModule]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketModule $ticketModule): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['required', 'string', 'max:50', 'unique:ticket_modules,slug,' . $ticketModule->id],
        ]);

        $ticketModule->update($data);

        return redirect()->route('ticket-modules.index')->with('success', 'Modul berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketModule $ticketModule): RedirectResponse
    {
        $ticketModule->delete();

        return redirect()->route('ticket-modules.index')->with('success', 'Modul berhasil dihapus');
    }
}
