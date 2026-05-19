@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="brand-green mb-0"><i class="bi bi-box-seam-fill"></i> Inventory Management</h2>
        <div class="btn-group">
            <a href="{{ route('inventory.excel_form') }}" class="btn btn-success fw-bold">
                <i class="bi bi-file-earmark-excel-fill"></i> Bulk Restock
            </a>
            <a href="" class="btn btn-outline-primary fw-bold">
                <i class="bi bi-plus-lg"></i> Add Product
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Out of Stock</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $metrics['out_of_stock'] }}</h2>
                        <i class="bi bi-x-octagon fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Low Stock Warnings</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $metrics['low_stock'] }}</h2>
                        <i class="bi bi-exclamation-triangle fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Expiring (7 Days)</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $metrics['expiring_soon'] }}</h2>
                        <i class="bi bi-calendar-event fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-dark text-white">
                <div class="card-body">
                    <h6 class="text-uppercase small fw-bold opacity-75">Inventory Value</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">TZS {{ number_format($metrics['total_value']) }}</h4>
                        <i class="bi bi-currency-dollar fs-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2">
            <form action="{{ route('inventory.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-upc-scan text-success"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Scan Barcode or Search Product..." autofocus>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="filter" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="low_stock">Low Stock Only</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Apply Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 30%;">Product & Identity</th>
                        <th style="width: 25%;">Stock Health</th>
                        <th>Expiry Status</th>
                        <th>Unit Price</th>
                        <th class="text-end pe-4">Quick Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                        @php
                            // Calculate health percentage for the progress bar
                            $percentage = ($item->stock_quantity / ($item->min_stock_level * 3)) * 100;
                            $percentage = $percentage > 100 ? 100 : $percentage;

                            $barColor = 'bg-success';
                            if($item->stock_quantity <= 0) $barColor = 'bg-dark';
                            elseif($item->stock_quantity <= $item->min_stock_level) $barColor = 'bg-danger';
                            elseif($item->stock_quantity <= ($item->min_stock_level * 1.5)) $barColor = 'bg-warning';
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($item->image)
                                        <img src="{{ asset('storage/'.$item->image) }}" class="rounded me-3" width="45" height="45" alt="Product">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                            <i class="bi bi-box text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $item->name }}</h6>
                                        <small class="text-muted">{{ $item->barcode ?? $item->sku }} | {{ $item->brand_name ?? 'Local' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold {{ $item->stock_quantity <= $item->min_stock_level ? 'text-danger' : '' }}">
                                        {{ $item->stock_quantity }} {{ $item->unit_of_measure }} left
                                    </span>
                                    <span class="small text-muted">Min: {{ $item->min_stock_level }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                                </div>
                            </td>
                            <td>
                                @if($item->expiry_date)
                                    @php $isExpired = $item->expiry_date <= now(); @endphp
                                    <span class="badge {{ $isExpired ? 'bg-danger' : 'bg-light text-dark border' }}">
                                        <i class="bi {{ $isExpired ? 'bi-exclamation-octagon' : 'bi-calendar' }} me-1"></i>
                                        {{ \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">No Expiry</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-muted">Buy: TZS {{ number_format($item->buying_price) }}</div>
                                <div class="fw-bold text-success">Sell: TZS {{ number_format($item->selling_price) }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group shadow-sm">
                                    <button class="btn btn-sm btn-outline-danger" title="Report Spoilage" data-bs-toggle="modal" data-bs-target="#adjustModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success" title="Add Stock" data-bs-toggle="modal" data-bs-target="#restockModal{{ $item->id }}">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @include('inventory.partials.adjust_modal', ['item' => $item])

                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No inventory records found matching your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $inventory->links() }}
</div>

    </div>
@endsection
