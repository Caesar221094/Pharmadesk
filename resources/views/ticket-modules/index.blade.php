@extends('layouts.pharmadesk')

@section('title', 'Modul Sistem')
@section('page-title', 'Modul Sistem Kasir')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Modul Sistem</h5>
    <a href="{{ route('ticket-modules.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Tambah Modul
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Slug</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($modules as $module)
                <tr>
                    <td>{{ $module->id }}</td>
                    <td>{{ $module->name }}</td>
                    <td>{{ $module->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('ticket-modules.edit', $module) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('ticket-modules.destroy', $module) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus modul ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">Belum ada modul.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
