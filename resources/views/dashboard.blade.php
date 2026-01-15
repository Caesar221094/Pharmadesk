@extends('layouts.pharmadesk')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Pharmadesk')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Tiket</p>
                        <h4 class="mb-0">{{ $totalTickets }}</h4>
                    </div>
                    <div class="text-primary">
                        <i class="fa-solid fa-ticket fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Tiket Open</p>
                <h4 class="mb-0">{{ $byStatus['open'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Tiket In Progress</p>
                <h4 class="mb-0">{{ $byStatus['in_progress'] ?? 0 }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="text-muted mb-1">Tiket Urgent (Open)</p>
                <h4 class="mb-0">{{ $urgentTickets->count() }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Tiket Urgent</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @forelse($urgentTickets as $ticket)
                        <a href="{{ route('tickets.show', $ticket) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $ticket->title }}</div>
                                <small class="text-muted">{{ $ticket->pharmacy->name ?? '-' }} &mdash; {{ ucfirst(str_replace('_',' ',$ticket->status)) }}</small>
                            </div>
                            <span class="badge rounded-pill bg-danger">Urgent</span>
                        </a>
                    @empty
                        <p class="text-muted mb-0">Tidak ada tiket urgent.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                @if($roleSlug === 'tech')
                    <h6 class="mb-0">Tiket Butuh Aksi IT</h6>
                @elseif($roleSlug === 'team_expert')
                    <h6 class="mb-0">Tiket Saya (Belum Selesai)</h6>
                @else
                    <h6 class="mb-0">Tiket Belum Selesai</h6>
                @endif
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        if ($roleSlug === 'tech') {
                            $list = $ticketsForTech;
                        } elseif ($roleSlug === 'team_expert') {
                            $list = $ticketsForExpert;
                        } else {
                            $list = $openTickets;
                        }
                    @endphp
                    @forelse($list as $ticket)
                        <a href="{{ route('tickets.show', $ticket) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">{{ $ticket->title }}</div>
                                <small class="text-muted">{{ $ticket->pharmacy->name ?? '-' }} &mdash; {{ ucfirst(str_replace('_',' ',$ticket->status)) }}</small>
                            </div>
                            <span class="badge rounded-pill badge-status-{{ $ticket->status }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                        </a>
                    @empty
                        <p class="text-muted mb-0">Tidak ada tiket terbuka.</p>
                    @endforelse
                </div>
                @if($roleSlug === 'tech')
                    <p class="text-muted small mt-3 mb-0">Daftar ini menunjukkan tiket yang masih butuh aksi dari tim IT (status Open atau In Progress), diurutkan dari yang paling baru di-update.</p>
                @elseif($roleSlug === 'team_expert')
                    <p class="text-muted small mt-3 mb-0">Daftar ini menunjukkan semua tiket yang Anda pegang sebagai Team Expert dan belum closed (Open, In Progress, atau Pending Review), diurutkan dari yang paling baru di-update.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Tiket per Status</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Tiket per Modul</h6>
            </div>
            <div class="card-body">
                <canvas id="moduleChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const statusData = @json($byStatus);
    const moduleData = @json($byModule);
    const moduleLabels = @json($modules);

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(statusData).map(k => k.replaceAll('_', ' ')),
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: Object.values(statusData),
                    backgroundColor: '#3b82f6',
                }]
            }
        });
    }

    const moduleCtx = document.getElementById('moduleChart');
    if (moduleCtx) {
        const labels = Object.keys(moduleData).map(id => moduleLabels[id] ?? 'Modul');
        const values = Object.values(moduleData);
        new Chart(moduleCtx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: ['#0ea5e9','#6366f1','#22c55e','#f97316','#facc15','#ec4899']
                }]
            }
        });
    }
</script>
@endpush
