<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Pharmacy;
use App\Models\TicketCategory;
use App\Models\TicketModule;
use App\Models\TicketActivity;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = Ticket::with(['pharmacy', 'module', 'category']);

        $user = $request->user();
        $roleSlug = $user?->role?->slug;

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($moduleId = $request->get('module_id')) {
            $query->where('module_id', $moduleId);
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($pharmacyId = $request->get('pharmacy_id')) {
            $query->where('pharmacy_id', $pharmacyId);
        }

        if ($roleSlug === 'team_expert' && $request->boolean('my_expert')) {
            $query->where('expert_id', $user->id);
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('pharmacy', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $tickets = $query->latest('updated_at')->paginate(15)->withQueryString();

        $modules = TicketModule::all();
        $categories = TicketCategory::all();
        $pharmacies = Pharmacy::all();

        return view('tickets.index', compact('tickets', 'modules', 'categories', 'pharmacies'));
    }

    public function create(): View
    {
        $pharmacies = Pharmacy::all();
        $categories = TicketCategory::all();
        $modules = TicketModule::all();

        $roleSlug = auth()->user()?->role?->slug;

        $sourceOptions = match ($roleSlug) {
            'team_expert' => [
                'team_expert' => 'Team Expert',
                'internal' => 'Internal',
            ],
            default => [
                'internal' => 'Internal',
            ],
        };

        return view('tickets.create', compact('pharmacies', 'categories', 'modules', 'sourceOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $roleSlug = $request->user()?->role?->slug;

        $allowedSources = match ($roleSlug) {
            'team_expert' => ['team_expert', 'internal'],
            default => ['internal'],
        };

        $rules = [
            'pharmacy_id' => ['required', 'exists:pharmacies,id'],
            'module_id' => ['required', 'exists:ticket_modules,id'],
            'category_id' => ['required', 'exists:ticket_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'source' => ['required', Rule::in($allowedSources)],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ];

        $rules['attachments.*'] = ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/gif,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska', 'max:20480'];

        $data = $request->validate($rules);

        if ($roleSlug === 'team_expert') {
            $data['expert_id'] = auth()->id();
        }

        if (! isset($data['priority'])) {
            $data['priority'] = 'medium';
        }
        $data['opened_at'] = now();

        $ticket = Ticket::create($data);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'old_status' => null,
            'new_status' => $ticket->status,
            'note' => 'Tiket dibuat',
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('ticket-attachments', 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully');
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load(['pharmacy', 'module', 'category', 'customer', 'expert', 'tech', 'activities.user', 'attachments']);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket): View
    {
        $pharmacies = Pharmacy::all();
        $categories = TicketCategory::all();
        $modules = TicketModule::all();

        return view('tickets.edit', compact('ticket', 'pharmacies', 'categories', 'modules'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $rules = [
            'pharmacy_id' => ['required', 'exists:pharmacies,id'],
            'module_id' => ['required', 'exists:ticket_modules,id'],
            'category_id' => ['required', 'exists:ticket_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:open,in_progress,for_review,closed'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
        ];

        $data = $request->validate($rules);

        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;

        $ticket->update($data);

        if ($oldStatus !== $ticket->status) {
            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
                'note' => 'Status updated',
            ]);
        }

        if ($oldPriority !== $ticket->priority) {
            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'old_status' => $ticket->status,
                'new_status' => $ticket->status,
                'note' => 'Priority updated from ' . $oldPriority . ' to ' . $ticket->priority,
            ]);
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully');
    }

    public function addActivity(Request $request, Ticket $ticket): RedirectResponse
    {
        $data = $request->validate([
            'note' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/gif,video/mp4,video/quicktime,video/x-msvideo,video/x-matroska', 'max:20480'],
        ]);

        if (empty($data['note']) && ! $request->hasFile('attachments')) {
            return back()->with('success', 'Tidak ada perubahan yang dikirim.');
        }

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'old_status' => $ticket->status,
            'new_status' => $ticket->status,
            'note' => $data['note'] ?? null,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if (! $file) {
                    continue;
                }

                $path = $file->store('ticket-attachments', 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $request->user()->id,
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Update aktivitas tiket berhasil dikirim');
    }

    public function showAttachment(TicketAttachment $attachment)
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($attachment->path)) {
            abort(404);
        }

        return $disk->response($attachment->path);
    }
}
