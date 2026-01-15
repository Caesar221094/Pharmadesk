@extends('layouts.pharmadesk')

@section('title', 'Edit Tiket')
@section('page-title', 'Edit Tiket #' . $ticket->id)

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Tiket</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tickets.update', $ticket) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Apotek</label>
                    <select name="pharmacy_id" class="form-select" required>
                        @foreach($pharmacies as $pharmacy)
                            <option value="{{ $pharmacy->id }}" @selected(old('pharmacy_id', $ticket->pharmacy_id) == $pharmacy->id)>{{ $pharmacy->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Modul Sistem</label>
                    <select name="module_id" class="form-select" required>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" @selected(old('module_id', $ticket->module_id) == $module->id)>{{ $module->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori Kendala</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $ticket->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-select" required>
                        @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High','urgent'=>'Urgent'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', $ticket->priority) == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        @foreach(['open','in_progress','for_review','closed'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $ticket->status) == $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Versi Aplikasi Kasir</label>
                    <input type="text" name="app_version" class="form-control" value="{{ old('app_version', $ticket->app_version) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Judul Kendala</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $ticket->title) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi Kendala</label>
                    <textarea name="description" class="form-control" rows="5" required>{{ old('description', $ticket->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline-secondary">Batal</a>
                <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
