<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketModule;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();
        $byStatus = Ticket::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $byCategory = Ticket::selectRaw('category_id, COUNT(*) as total')->groupBy('category_id')->pluck('total', 'category_id');
        $byModule = Ticket::selectRaw('module_id, COUNT(*) as total')->groupBy('module_id')->pluck('total', 'module_id');

        $categories = TicketCategory::whereIn('id', $byCategory->keys())->pluck('name', 'id');
        $modules = TicketModule::whereIn('id', $byModule->keys())->pluck('name', 'id');

        $urgentTickets = Ticket::where('priority', 'urgent')
            ->where('status', '!=', 'closed')
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $openTickets = Ticket::where('status', '!=', 'closed')
            ->latest('updated_at')
            ->limit(10)
            ->get();

        $user = auth()->user();
        $roleSlug = $user?->role?->slug;

        $ticketsForTech = collect();
        $ticketsForExpert = collect();

        if ($roleSlug === 'tech') {
            // Antrian global untuk tim IT: semua tiket yang masih butuh aksi IT
            $ticketsForTech = Ticket::whereIn('status', ['open', 'in_progress'])
                ->latest('updated_at')
                ->limit(10)
                ->get();
        } elseif ($roleSlug === 'team_expert') {
            // Semua tiket yang dipegang Team Expert dan belum closed
            $ticketsForExpert = Ticket::where('expert_id', $user->id)
                ->where('status', '!=', 'closed')
                ->latest('updated_at')
                ->limit(10)
                ->get();
        }

        return view('dashboard', compact(
            'totalTickets',
            'byStatus',
            'byCategory',
            'byModule',
            'categories',
            'modules',
            'urgentTickets',
            'openTickets',
            'ticketsForTech',
            'ticketsForExpert',
            'roleSlug'
        ));
    }
}
