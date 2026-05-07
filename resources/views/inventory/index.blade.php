@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="brand-green">Manage Categories</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white text-success fw-bold">
                <i class="bi bi-plus-circle"></i> Add New Category
            </div>
            <div class="card-body">
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Vegetables" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save Category</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Added On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td class="fw-bold">{{ $category->name }}</td>
                                    <td class="text-muted">{{ $category->description ?? '-' }}</td>
                                    <td>{{ $category->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No categories found. Add one on the left!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
