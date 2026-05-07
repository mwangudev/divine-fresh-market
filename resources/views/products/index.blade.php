@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="brand-green"><i class="bi bi-box-seam"></i> Product Stock</h2>
    <div>
        @if(strtolower(auth()->user()->role) === 'admin')
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-success me-2"><i class="bi bi-tags"></i> Categories</a>
        @endif
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row">
    @if(strtolower(auth()->user()->role) === 'admin')
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white text-dark fw-bold pt-3 pb-0 border-0">
                <i class="bi bi-plus-circle text-warning"></i> Add New Product
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Tomatoes" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small">Buy Price (Tsh)</label>
                            <input type="number" name="buying_price" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Sell Price (Tsh)</label>
                            <input type="number" name="selling_price" class="form-control" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="form-label fw-bold small">Stock Qty</label>
                            <input type="number" name="stock_quantity" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small">Unit</label>
                            <select name="unit_of_measure" class="form-select" required>
                                <option value="kg">kg</option>
                                <option value="pcs">pcs</option>
                                <option value="bunches">bunches</option>
                                <option value="liters">Liters</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold">Save to Inventory</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <div class="col-lg-{{ strtolower(auth()->user()->role) === 'admin' ? '8' : '12' }}">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Category</th>

                                @if(strtolower(auth()->user()->role) === 'admin')
                                    <th>Buy Price</th>
                                @endif

                                <th>Sell Price</th>
                                <th>Stock Level</th>

                                @if(strtolower(auth()->user()->role) === 'admin')
                                    <th class="text-end">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $product->name }}</span>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>

                                    @if(strtolower(auth()->user()->role) === 'admin')
                                        <td class="text-muted">Tsh {{ number_format($product->buying_price, 2) }}</td>
                                    @endif

                                    <td class="fw-bold text-success">Tsh {{ number_format($product->selling_price, 2) }}</td>

                                    <td>
                                        @if($product->stock_quantity <= 5)
                                            <span class="badge bg-danger fs-6">{{ $product->stock_quantity }} {{ $product->unit_of_measure }} (Low!)</span>
                                        @else
                                            <span class="badge bg-success bg-opacity-75 fs-6">{{ $product->stock_quantity }} {{ $product->unit_of_measure }}</span>
                                        @endif
                                    </td>


                                    <td class="text-end">
                                        <button class="btn btn-sm " data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                    </td>

                                </tr>


                                <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title fw-bold text-success"><i class="bi bi-box-arrow-in-down"></i> Restock: {{ $product->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('products.update', $product->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">

                                                    <div class="alert alert-info py-2">
                                                        Current Stock: <strong>{{ $product->stock_quantity }} {{ $product->unit_of_measure }}</strong>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Add New Stock (Quantity)</label>
                                                        <input type="number" name="add_stock" class="form-control border-success" step="0.01" min="0" placeholder="How much arrived today?">
                                                        <small class="text-muted">Leave blank if you only want to change prices.</small>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-6 mb-3">
                                                            <label class="form-label fw-bold">Buying Price (Tsh)</label>
                                                            <input type="number" name="buying_price" class="form-control" value="{{ $product->buying_price }}" step="0.01" min="0" required>
                                                        </div>
                                                        <div class="col-6 mb-3">
                                                            <label class="form-label fw-bold">Selling Price (Tsh)</label>
                                                            <input type="number" name="selling_price" class="form-control" value="{{ $product->selling_price }}" step="0.01" min="0" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary fw-bold">Update Product</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No products in inventory yet.</td>
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
