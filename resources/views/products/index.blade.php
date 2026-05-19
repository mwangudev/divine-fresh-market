<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="brand-green mb-0"><i class="bi bi-box-seam-fill"></i> Manage Products</h2>
            <p class="text-muted small">Manage Divine Fresh Market stock, prices, and expiration dates.</p>
        </div>
        <div>
            @if(strtolower(auth()->user()->role) === 'admin')
                <a href="{{ route('inventory.index') }}" class="btn btn-success me-2"><i class="bi bi-tags"></i> Categories</a>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="row">
        @if(strtolower(auth()->user()->role) === 'admin')
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-divine-dark text-white fw-bold py-3">
                    <i class="bi bi-plus-circle-fill text-success"></i> Register New Product
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 p-2 bg-light border rounded">
                            <label class="form-label fw-bold text-success small"><i class="bi bi-upc-scan"></i> Scan Barcode</label>
                            <input type="text" name="barcode" class="form-control form-control-lg border-success" placeholder="Scan here..." autofocus>
                            <small class="text-muted">Leave empty for fresh farm produce.</small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small">Category</label>
                                <select name="category_id" class="form-select select2" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small">Brand Name</label>
                                <input type="text" name="brand_name" class="form-control" placeholder="e.g. Asas, Mo">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Fresh Tomatoes" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small">Buy Price (Tsh)</label>
                                <input type="number" name="buying_price" class="form-control text-danger fw-bold" step="0.01" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small">Sell Price (Tsh)</label>
                                <input type="number" name="selling_price" class="form-control text-success fw-bold" step="0.01" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small">Base Stock Qty</label>
                                <input type="number" name="stock_quantity" class="form-control" step="0.01" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small">Min Alert Level</label>
                                <input type="number" name="min_stock_level" class="form-control border-warning" value="5" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small">Selling Unit</label>
                                <select name="unit_of_measure" class="form-select">
                                    <option value="pcs">Pieces (Pcs)</option>
                                    <option value="kg">Kilograms (Kg)</option>
                                    <option value="liters">Liters (L)</option>
                                    <option value="box">Box/Carton</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small">Expiry Date</label>
                                <input type="date" name="expiry_date" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold py-2"><i class="bi bi-save"></i> Save Product</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="col-lg-{{ strtolower(auth()->user()->role) === 'admin' ? '8' : '12' }}">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold text-dark">Inventory List</h5>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Quick search by name or barcode...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Details</th>
                                    @if(auth()->user()->role === 'admin') <th>Buying</th> @endif
                                    <th>Selling</th>
                                    <th>Stock Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->brand_name ?? 'Local' }} | {{ $product->barcode ?? $product->sku ?? 'No Barcode' }}</small><br>
                                            <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                                        </td>

                                        @if(strtolower(auth()->user()->role) === 'admin')
                                            <td class="text-danger small fw-bold">Tsh {{ number_format($product->buying_price, 2) }}</td>
                                        @endif

                                        <td class="text-success fw-bold">Tsh {{ number_format($product->selling_price, 2) }}</td>

                                        <td>
                                            @php
                                                $isLow = $product->stock_quantity <= ($product->min_stock_level ?? 5);
                                                $isExpired = $product->expiry_date && $product->expiry_date <= now();
                                            @endphp

                                            <div class="d-flex flex-column">
                                                <span class="badge {{ $isLow ? 'bg-danger' : 'bg-success' }} mb-1 align-self-start">
                                                    {{ $product->stock_quantity }} {{ $product->unit_of_measure }}
                                                </span>
                                                @if($isExpired)
                                                    <small class="text-danger fw-bold"><i class="bi bi-exclamation-octagon"></i> Expired!</small>
                                                @elseif($product->expiry_date)
                                                    <small class="text-muted small">Exp: {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}</small>
                                                @endif
                                            </div>
                                        </td>

                                        <td class="text-end pe-3">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}" title="Edit Details">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#restockModal{{ $product->id }}" title="Quick Restock">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title fw-bold" id="editModalLabel{{ $product->id }}"><i class="bi bi-pencil-square"></i> Edit Product: {{ $product->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('products.update', $product->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold">Product Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">Brand Name</label>
                                                                <input type="text" name="brand_name" class="form-control" value="{{ $product->brand_name }}">
                                                            </div>

                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">Category</label>
                                                                <select name="category_id" class="form-select" required>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-6">
                                                                <label class="form-label small fw-bold">Barcode</label>
                                                                <input type="text" name="barcode" class="form-control" value="{{ $product->barcode }}">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-6 mb-3">
                                                                <label class="form-label small fw-bold">Buying Price (Tsh)</label>
                                                                <input type="number" name="buying_price" class="form-control text-danger fw-bold" value="{{ $product->buying_price }}" step="0.01" min="0" required>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <label class="form-label small fw-bold">Selling Price (Tsh)</label>
                                                                <input type="number" name="selling_price" class="form-control text-success fw-bold" value="{{ $product->selling_price }}" step="0.01" min="0" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="restockModal{{ $product->id }}" tabindex="-1" aria-labelledby="restockModalLabel{{ $product->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title fw-bold" id="restockModalLabel{{ $product->id }}"><i class="bi bi-box-arrow-in-down"></i> Stock In: {{ $product->name }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('inventory.adjust', $product->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="adjustment_type" value="add">
                                                    <input type="hidden" name="reason" value="Supplier Delivery / Restock">

                                                    <div class="modal-body">
                                                        <div class="alert alert-secondary py-2 border-0">
                                                            Current Shelf Stock: <strong class="text-dark">{{ $product->stock_quantity }} {{ $product->unit_of_measure }}</strong>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-success">Incoming New Quantity</label>
                                                            <div class="input-group input-group-lg">
                                                                <input type="number" name="quantity" class="form-control border-success text-center fw-bold" step="0.01" min="0.01" placeholder="0.00" required>
                                                                <span class="input-group-text bg-success text-white fw-bold">{{ $product->unit_of_measure }}</span>
                                                            </div>
                                                            <small class="text-muted d-block mt-1">This will be added immediately to your active store inventory.</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success btn-sm fw-bold">Update Stock Levels</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No products found in inventory.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $("#tableSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#productTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
@endsection
