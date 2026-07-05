@extends('layouts.admin')

@section('title', 'Departments - Inventory - ' . config('app.name'))
@section('page_title', 'Inventory / Departments')

@section('content')
    @include('inventory.partials.subnav')

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <div class="fw-semibold">Departments</div>
            <a href="{{ route('inventory.departments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> New Department
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($departments as $d)
                    <tr>
                        <td class="fw-semibold">{{ $d->name }}</td>
                        <td>
                            @if($d->products_count > 0)
                                <a href="{{ route('inventory.products.index', ['department_id' => $d->id]) }}" class="text-decoration-none">
                                    {{ $d->products_count }}
                                </a>
                            @else
                                <span class="text-secondary">0</span>
                            @endif
                        </td>
                        <td>
                            @if($d->active)
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('inventory.departments.edit', $d) }}">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('inventory.departments.destroy', $d) }}"
                                  onsubmit="return confirm('Delete department? Products will stay but department will be cleared.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">No departments yet. Pehle department banaein, phir products assign karein.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $departments->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
