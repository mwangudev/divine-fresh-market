@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2 class="brand-green">Staff Dashboard</h2>
        <p class="text-muted">Select an action below to get started.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-calculator display-1 text-primary mb-3"></i>
                <h4 class="card-title">Point of Sale</h4>
                <p class="card-text text-muted">Launch the cash register to process new customer sales.</p>
                <a href="{{ route('pos.index') }}" class="btn btn-primary btn-lg w-100 mt-2">Open Register</a>
            </div>
        </div>
    </div>

    @if(strtolower(auth()->user()->role) === 'admin')
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-tags display-1 text-success mb-3"></i>
                <h4 class="card-title">Categories</h4>
                <p class="card-text text-muted">Add or edit product categories (e.g., Vegetables, Dairy).</p>
                <a href="{{ route('inventory.index') }}" class="btn btn-success btn-lg w-100 mt-2">Manage Categories</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body text-center py-5">
                <i class="bi bi-box-seam display-1 text-warning mb-3"></i>
                <h4 class="card-title">Products & Stock</h4>
                <p class="card-text text-muted">Manage inventory, update prices, and add new stock.</p>
                <a href="{{ route('products.index') }}" class="btn btn-warning text-dark fw-bold btn-lg w-100 mt-2">Manage Stock</a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
