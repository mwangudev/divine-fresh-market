@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="brand-green mb-0"><i class="bi bi-file-earmark-excel-fill text-success"></i> Excel Bulk Restock</h2>
            <p class="text-muted small">Upload a structured spreadsheet to update stock levels instantly.</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-divine-dark text-white py-3 fw-bold">
                    <i class="bi bi-upload"></i> Upload Spreadsheet
                </div>
                <div class="card-body py-4">
                    <form action="{{ route('inventory.excel_store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Excel/CSV File</label>
                            <input type="file" name="excel_file" class="form-control form-control-lg border-success" required>
                            <small class="text-muted">Supported formats: .xlsx, .xls, .csv (Max size: 2MB)</small>
                        </div>
                        <button type="submit" class="btn btn-success fw-bold px-4"><i class="bi bi-cloud-arrow-up"></i> Process Restock</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm border-start border-warning border-4">
    <div class="card-header bg-white py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
        <span><i class="bi bi-info-circle-fill text-warning"></i> Spreadsheet Template Requirements</span>
        <a href="{{ route('inventory.download_template') }}" class="btn btn-sm btn-warning fw-bold">
            <i class="bi bi-download"></i> Download Sample Template
        </a>
    </div>
    <div class="card-body">
        <p class="text-muted small">Ili mfumo usome data zako kwa usahihi, bonyeza kitufe cha juu kupakua template yenye bidhaa zako zote, au uhakikishe faili lako lina muundo huu:</p>

        <div class="table-responsive">
    <table class="table table-bordered table-sm small" style="font-size: 11px;">
        <thead class="table-light text-center text-nowrap">
            <tr>
                <th>search_identifier</th>
                <th>product_name</th>
                <th>sku</th>
                <th>barcode</th>
                <th>brand_name</th>
                <th>unit_of_measure</th>
                <th>stock_quantity_to_add</th>
                <th>buying_price</th>
                <th>selling_price</th>
                <th>expiry_date</th>
            </tr>
        </thead>
        <tbody class="text-center text-muted">
            <tr>
                <td>6001068636402</td>
                <td>Fresh Milk 1L</td>
                <td>DAI-MILK-01</td>
                <td>6001068636402</td>
                <td>Asas</td>
                <td>liters</td>
                <td>12.00</td>
                <td>1800.00</td>
                <td>2300.00</td>
                <td>2026-06-30</td>
            </tr>
        </tbody>
    </table>
</div>

        <div class="alert alert-warning py-2 small mb-0 mt-3">
            <i class="bi bi-exclamation-triangle-fill"></i> <strong>Note:</strong> Ukishapakua faili hili, fungua kwenye Excel, badilisha namba zilizopo kwenye safu ya <code>stock_quantity</code> kuweka kiasi kipya kilichoingia stooni leo, kisha weka faili hilo upande wa kushoto.
        </div>
    </div>
</div>
    </div>
</div>
@endsection
