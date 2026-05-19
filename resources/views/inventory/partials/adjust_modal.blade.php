<div class="modal fade" id="adjustModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Stock Adjustment: {{ $item->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inventory.adjust', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Adjustment Type</label>
                        <select name="adjustment_type" class="form-select" required>
                            <option value="subtract">Deduct (Spoilage / Damage / Loss)</option>
                            <option value="add">Add (Manual Correction / Gift)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantity ({{ $item->unit_of_measure }})</label>
                        <input type="number" name="quantity" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Reason for Adjustment</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="e.g., Tomatoes rotted due to heat" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Adjustment</button>
                </div>
            </form>
        </div>
    </div>
</div>
