@extends('layouts.pharmadesk')

@section('title', 'Tiket Baru')
@section('page-title', 'Input Tiket Kendala Apotek')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tiket Baru</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Apotek</label>
                    <div class="d-flex gap-2">
                        <select name="pharmacy_id" class="form-select" required>
                            <option value="">Pilih Apotek</option>
                            @foreach($pharmacies as $pharmacy)
                                <option value="{{ $pharmacy->id }}" @selected(old('pharmacy_id') == $pharmacy->id)>{{ $pharmacy->name }}</option>
                            @endforeach
                        </select>
                        @php
                            $roleSlug = auth()->user()?->role?->slug ?? null;
                        @endphp
                        @if(in_array($roleSlug, ['admin','team_expert'], true))
                            <a href="{{ route('pharmacies.create') }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-plus"></i>
                            </a>
                        @endif
                    </div>
                    @if(in_array($roleSlug ?? '', ['admin','team_expert'], true))
                        <small class="text-muted">Klik + untuk menambah apotek baru jika belum terdaftar.</small>
                    @endif
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modul Sistem</label>
                    <select name="module_id" class="form-select" required>
                        <option value="">Pilih Modul</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" @selected(old('module_id') == $module->id)>{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori Kendala</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-select" required>
                        @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High','urgent'=>'Urgent'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority','medium') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sumber Tiket</label>
                    <select name="source" class="form-select" required>
                        @foreach($sourceOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('source', array_key_first($sourceOptions)) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Versi Aplikasi Kasir</label>
                    <input type="text" name="app_version" class="form-control" value="{{ old('app_version') }}" placeholder="contoh: v3.2.1">
                </div>
                <div class="col-12">
                    <label class="form-label">Judul Kendala</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Kendala</label>
                    <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Lampiran Bukti (foto / video)</label>
                    <input type="file" name="attachments[]" class="form-control" multiple accept="image/*,video/*">
                    <small class="text-muted">Opsional, namun sangat disarankan untuk kendala berupa bug/error layar kasir.</small>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button class="btn btn-primary" type="submit">Simpan Tiket</button>
            </div>
        </form>
    </div>
</div>
@endsection
