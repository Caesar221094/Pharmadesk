@extends('layouts.pharmadesk')

@section('title', 'Apotek')
@section('page-title', 'Daftar Apotek')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Apotek</h5>
    <a href="{{ route('pharmacies.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Tambah Apotek
    </a>
</div>

<form method="GET" class="card mb-3 shadow-sm border-0">
    <div class="card-body row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Cari</label>
            <input type="text" name="q" class="form-control" placeholder="Nama apotek / PIC" value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
        </div>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Nama Apotek</th>
                <th>PIC</th>
                <th>Telepon</th>
                <th>WhatsApp</th>
                <th>Kota</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($pharmacies as $pharmacy)
                <tr>
                    <td>{{ $pharmacy->id }}</td>
                    <td>{{ $pharmacy->name }}</td>
                    <td>{{ $pharmacy->pic_name }}</td>
                    <td>{{ $pharmacy->phone }}</td>
                    <td>{{ $pharmacy->whatsapp }}</td>
                    <td>{{ $pharmacy->city }}</td>
                    <td class="text-end">
                        <a href="{{ route('pharmacies.edit', $pharmacy) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('pharmacies.destroy', $pharmacy) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus apotek ini?')">
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
                    <td colspan="7" class="text-center text-muted py-4">Belum ada data apotek.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($pharmacies->hasPages())
        <div class="card-body border-top">
            {{ $pharmacies->links() }}
        </div>
    @endif
</div>
@endsection
