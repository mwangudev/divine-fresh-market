@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col">
            <h2 class="brand-green mb-0">{{ ucfirst(auth()->user()->role) }} Dashboard</h2>
            <p class="text-muted">Welcome to the Divine Fresh Market system. Select a service below to get started.</p>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold">Total Revenue</h6>
                            <h3 class="mb-0">TZS {{ number_format($sales->sum('total_amount'), 2) }}</h3>
                        </div>
                        <i class="bi bi-cash-stack fs-1 text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold">Total Profit</h6>
                            <h3 class="mb-0 text-primary">TZS {{ number_format($sales->sum('profit'), 2) }}</h3>
                        </div>
                        <i class="bi bi-graph-up-arrow fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i> Recent Sales (Admin Analysis)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Sale ID</th>
                                    <th>Total Revenue</th>
                                    <th>Net Profit</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales->take(5) as $sale)
                                    <tr>
                                        <td class="ps-4 fw-bold">#{{ $sale->id }}</td>
                                        <td>TZS {{ number_format($sale->total_amount, 2) }}</td>
                                        <td class="text-success fw-bold">+ TZS {{ number_format($sale->profit, 2) }}</td>
                                        <td class="text-end pe-4">
                                            <span class="badge bg-success-soft text-success rounded-pill">Completed</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift">
                <div class="card-body py-5">
                    <i class="bi bi-calculator display-1 text-primary mb-3"></i>
                    <h4 class="card-title fw-bold">Point of Sale</h4>
                    <p class="card-text text-muted px-3">Launch the cash register to process new customer sales.</p>
                    <a href="{{ route('pos.index') }}" class="btn btn-primary w-100 mt-2 py-2 fw-bold shadow-sm">Open Register</a>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift">
                <div class="card-body py-5">
                    <i class="bi bi-tags display-1 text-success mb-3"></i>
                    <h4 class="card-title fw-bold">Categories</h4>
                    <p class="card-text text-muted px-3">Add or edit product categories (e.g., Vegetables, Dairy).</p>
                    <a href="{{ route('inventory.index') }}" class="btn btn-success w-100 mt-2 py-2 fw-bold shadow-sm text-white">Manage Categories</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 hover-lift">
                <div class="card-body py-5">
                    <i class="bi bi-box-seam display-1 text-warning mb-3"></i>
                    <h4 class="card-title fw-bold">Products & Stock</h4>
                    <p class="card-text text-muted px-3">Update prices, manage inventory levels, and add new stock.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-warning w-100 mt-2 py-2 fw-bold shadow-sm text-dark">Manage Stock</a>
                </div>
            </div>
        </div>
     
    </div>
</div>

<style>
    .bg-success-soft { background-color: rgba(25, 135, 84, 0.1); }
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1); }
    .bg-warning-soft { background-color: rgba(255, 193, 7, 0.1); }

    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(25, 135, 84, 0.02);
    }
</style>
@endsection
