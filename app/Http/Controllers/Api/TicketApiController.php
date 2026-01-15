<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TicketApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        return $query->paginate(20);
    }

    public function store(Request $request)
    {
        $roleSlug = $request->user()?->role?->slug;

        $allowedSources = match ($roleSlug) {
            'team_expert' => ['team_expert', 'internal'],
            default => ['internal'],
        };

        $data = $request->validate([
            'pharmacy_id' => ['required', 'exists:pharmacies,id'],
            'module_id' => ['required', 'exists:ticket_modules,id'],
            'category_id' => ['required', 'exists:ticket_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'source' => ['required', Rule::in($allowedSources)],
        ]);

        if ($roleSlug === 'team_expert') {
            $data['expert_id'] = auth()->id();
        }
        $data['opened_at'] = now();

        $ticket = Ticket::create($data);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'old_status' => null,
            'new_status' => $ticket->status,
            'note' => 'Tiket dibuat via API',
        ]);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        return $ticket->load(['pharmacy', 'module', 'category', 'customer', 'expert', 'tech']);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'pharmacy_id' => ['sometimes', 'exists:pharmacies,id'],
            'module_id' => ['sometimes', 'exists:ticket_modules,id'],
            'category_id' => ['sometimes', 'exists:ticket_categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'app_version' => ['sometimes', 'string', 'max:50'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'status' => ['sometimes', 'in:open,in_progress,for_review,closed'],
        ]);

        $oldStatus = $ticket->status;
        $ticket->update($data);

        if (array_key_exists('status', $data) && $oldStatus !== $ticket->status) {
            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
                'note' => 'Status updated via API',
            ]);
        }

        return response()->json($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return response()->noContent();
    }
}
