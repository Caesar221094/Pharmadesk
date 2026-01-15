@extends('layouts.pharmadesk')

@section('title', 'Detail Tiket')
@section('page-title', 'Detail Tiket #' . $ticket->id)

@section('content')
<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ $ticket->title }}</h5>
                    <small class="text-muted">{{ $ticket->pharmacy->name ?? '-' }} &mdash; Modul {{ $ticket->module->name ?? '-' }}</small>
                </div>
                <div class="text-end">
                    <span class="badge badge-status-{{ $ticket->status }} mb-1">{{ $ticket->status_label }}</span><br>
                    @php $color = ['low'=>'secondary','medium'=>'info','high'=>'warning','urgent'=>'danger'][$ticket->priority] ?? 'secondary'; @endphp
                    <span class="badge bg-{{ $color }}">{{ ucfirst($ticket->priority) }}</span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="text-muted">Deskripsi Kendala</h6>
                <p class="mb-3">{!! nl2br(e($ticket->description)) !!}</p>

                <dl class="row small mb-0">
                    <dt class="col-sm-3">Versi Aplikasi</dt>
                    <dd class="col-sm-9">{{ $ticket->app_version ?? '-' }}</dd>
                    <dt class="col-sm-3">Kategori</dt>
                    <dd class="col-sm-9">{{ $ticket->category->name ?? '-' }}</dd>
                    <dt class="col-sm-3">Sumber Tiket</dt>
                    <dd class="col-sm-9">{{ ucfirst(str_replace('_',' ',$ticket->source)) }}</dd>
                    <dt class="col-sm-3">Dibuka</dt>
                    <dd class="col-sm-9">{{ optional($ticket->opened_at)->format('d/m/Y H:i') }}</dd>
                    <dt class="col-sm-3">Ditutup</dt>
                    <dd class="col-sm-9">{{ optional($ticket->closed_at)->format('d/m/Y H:i') ?? '-' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Timeline Aktivitas</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled timeline mb-0">
                    @forelse($ticket->activities as $activity)
                        <li class="mb-3 d-flex">
                            <div class="me-3 text-secondary">
                                <i class="fa-solid fa-circle-dot"></i>
                            </div>
                            <div>
                                <div class="small text-muted">{{ $activity->created_at->format('d/m/Y H:i') }} &mdash; {{ $activity->user->name ?? '-' }}</div>
                                @if($activity->old_status || $activity->new_status)
                                    <div class="small mb-1">Status: {{ $activity->old_status ? ucfirst(str_replace('_',' ',$activity->old_status)) : '-' }} → {{ $activity->new_status ? ucfirst(str_replace('_',' ',$activity->new_status)) : '-' }}</div>
                                @endif
                                @if($activity->note)
                                    <div>{!! nl2br(e($activity->note)) !!}</div>
                                @endif
                            </div>
                        </li>
                    @empty
                        <p class="text-muted mb-0">Belum ada aktivitas.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Penanggung Jawab</h6>
            </div>
            <div class="card-body small">
                <dl class="row mb-2">
                    <dt class="col-sm-5">Team Expert Pengelola</dt>
                    <dd class="col-sm-7">{{ $ticket->expert->name ?? '-' }}</dd>
                    <dt class="col-sm-5">Tim IT (Tech)</dt>
                    <dd class="col-sm-7">{{ $ticket->tech->name ?? 'Tim Tech (IT Support)' }}</dd>
                </dl>
                <p class="text-muted mb-0">Team Expert menjadi pengelola utama tiket, sedangkan Tim Tech menangani eksekusi teknis dan mengubah status tiket saat pekerjaan IT sudah selesai.</p>
            </div>
        </div>

        @if($ticket->attachments->count())
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0">Lampiran</h6>
                </div>
                <div class="card-body small">
                    <ul class="list-unstyled mb-0">
                        @foreach($ticket->attachments as $attachment)
                            @php
                                $mime = $attachment->mime_type ?? '';
                            @endphp
                            <li class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fa-solid fa-paperclip me-1"></i>
                                        <a href="{{ route('attachments.show', $attachment) }}" target="_blank">{{ $attachment->filename }}</a>
                                        <span class="text-muted">&mdash; {{ $attachment->user->name ?? '-' }}</span>
                                    </div>
                                </div>
                                @if(str_starts_with($mime, 'image/'))
                                    <div class="mt-2">
                                        <img src="{{ route('attachments.show', $attachment) }}" alt="{{ $attachment->filename }}" class="img-fluid rounded border" style="max-height: 180px;">
                                    </div>
                                @elseif(str_starts_with($mime, 'video/'))
                                    <div class="mt-2">
                                        <video controls style="max-width: 100%; max-height: 240px;">
                                            <source src="{{ route('attachments.show', $attachment) }}" type="{{ $mime }}">
                                            Browser Anda tidak mendukung pemutaran video.
                                        </video>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Aksi Status & Prioritas</h6>
                <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-outline-primary">Buka Halaman Edit</a>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-0">Gunakan halaman edit untuk mengubah status dan prioritas tiket. Form di bawah ini hanya untuk menambah komentar dan bukti baru.</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">Tambah Update / Komentar</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tickets.activities.store', $ticket) }}" enctype="multipart/form-data" class="small">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Catatan</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Tambahkan penjelasan, progres teknis, atau klarifikasi untuk tiket ini..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lampiran tambahan (foto / video)</label>
                        <input type="file" name="attachments[]" class="form-control" multiple accept="image/*,video/*">
                        <small class="text-muted">Gunakan untuk menambah bukti baru, misalnya rekaman layar tambahan.</small>
                    </div>
                    <button class="btn btn-sm btn-primary" type="submit">Kirim Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
