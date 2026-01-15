@extends('layouts.pharmadesk')

@section('title', 'Kategori Tiket')
@section('page-title', 'Kategori Tiket')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Kategori Tiket</h5>
    <a href="{{ route('ticket-categories.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
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
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td class="text-end">
                        <a href="{{ route('ticket-categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('ticket-categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
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
                    <td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
