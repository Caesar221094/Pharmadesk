@extends('layouts.pharmadesk')

@section('title', 'Tiket Kendala')
@section('page-title', 'Daftar Tiket Kendala Apotek')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Tiket Kendala</h5>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Tiket Baru
    </a>
</div>

<form method="GET" class="card mb-3 shadow-sm border-0">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua</option>
                @foreach(['open','in_progress','for_review','closed'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-select">
                <option value="">Semua</option>
                @foreach(['low','medium','high','urgent'] as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Modul</label>
            <select name="module_id" class="form-select">
                <option value="">Semua</option>
                @foreach($modules as $module)
                    <option value="{{ $module->id }}" @selected(request('module_id') == $module->id)>{{ $module->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">Semua</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Cari</label>
            <input type="text" name="q" class="form-control" placeholder="Nama apotek / judul" value="{{ request('q') }}">
        </div>
        @php
            $roleSlug = auth()->user()?->role?->slug ?? null;
        @endphp
        @if($roleSlug === 'team_expert')
            <div class="col-md-3">
                <label class="form-label">Filter</label>
                <select name="my_expert" class="form-select">
                    <option value="0" @selected(!request('my_expert'))>Semua tiket</option>
                    <option value="1" @selected(request('my_expert'))>Tiket di mana saya Expert</option>
                </select>
            </div>
        @endif
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Apotek</th>
                <th>Modul</th>
                <th>Kategori</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Dibuat</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->pharmacy->name ?? '-' }}</td>
                    <td>{{ $ticket->module->name ?? '-' }}</td>
                    <td>{{ $ticket->category->name ?? '-' }}</td>
                    <td>
                        @php $color = ['low'=>'secondary','medium'=>'info','high'=>'warning','urgent'=>'danger'][$ticket->priority] ?? 'secondary'; @endphp
                        <span class="badge bg-{{ $color }}">{{ ucfirst($ticket->priority) }}</span>
                    </td>
                    <td>
                        <span class="badge badge-status-{{ $ticket->status }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                    </td>
                    <td>{{ optional($ticket->opened_at)->format('d/m/Y H:i') }}</td>
                    <td class="text-end">
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Belum ada tiket.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets->hasPages())
        <div class="card-body border-top">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection
