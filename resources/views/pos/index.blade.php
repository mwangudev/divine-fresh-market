@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="brand-green m-0 flex-shrink-0"><i class="bi bi-calculator"></i> Point of Sale</h2>

    <div class="mx-4 flex-grow-1" style="max-width: 50%;">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0 text-success">
                <i class="bi bi-upc-scan"></i>
            </span>
            <input type="text"
                   id="posProductSearch"
                   class="form-control form-control-lg border-start-0"
                   placeholder="Scan Barcode au Andika Jina la Bidhaa..."
                   autofocus
                   autocomplete="off">
        </div>
    </div>

    <div class="flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Exit POS</a>
    </div>
</div>


<div class="row align-items-start">
    <div class="col-lg-7 col-xl-8 mb-4">
        <div class="card shadow-sm border-0 bg-transparent">
            <div class="card-body p-0">
                <div class="row g-2">
                 @forelse ($products as $product)
    <div class="col-6 col-md-4 col-xl-3 product-card-wrapper">

        <button class="btn btn-outline-success w-100 h-100 p-3 text-start bg-white shadow-sm"
                data-name="{{ strtolower($product->name) }}"
                data-barcode="{{ strtolower($product->barcode ?? '') }}"
                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->selling_price }}, {{ $product->stock_quantity }})"
                style="border-width: 2px;">

            <div class="fw-bold text-dark text-truncate" style="font-size: 1.1rem;">{{ $product->name }}</div>
            <div class="text-success fw-bold my-1">Tsh {{ number_format($product->selling_price, 0) }}</div>
            <div class="text-muted small"><i class="bi bi-box"></i> Stock: {{ $product->stock_quantity }}</div>
        </button>

    </div>
@empty
    <div class="col-12 text-center text-muted mt-5">
        <h4>No products in stock.</h4>
        <p>Please add products via the inventory manager.</p>
    </div>
@endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-xl-4 position-sticky" style="top: 20px;">
        <div class="card shadow border-0 border-top border-success border-4">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold"><i class="bi bi-cart3"></i> Current Sale</h4>
            </div>
            <div class="card-body p-0">
                <div style="max-height: 40vh; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <tr><td colspan="3" class="text-center text-muted py-4">Cart is empty. Tap items to add.</td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="m-0 text-muted">Total:</h3>
                        <h2 class="m-0 text-danger fw-bold">Tsh <span id="grand-total">0.00</span></h2>
                    </div>

                    <form id="checkout-form" action="{{ route('pos.checkout') }}" method="POST">
                        @csrf
                        <div id="hidden-cart-inputs"></div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Payment Method</label>
                            <select name="payment_method" class="form-select form-select-lg" required>
                                <option value="Cash">💵 Cash</option>
                                <option value="Mobile Money">📱 Mobile Money</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-success btn-lg w-100 fw-bold shadow-sm" onclick="submitSale()">
                            <i class="bi bi-printer"></i> Print & Complete Sale
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    console.log("POS Script Loaded Perfectly!");
    let cart = [];

    function addToCart(id, name, price, maxStock) {
        let existingItem = cart.find(item => item.product_id === id);

        if (existingItem) {
            if (existingItem.quantity < maxStock) {
                existingItem.quantity += 1;
            } else {
                alert(`Cannot add more. Only ${maxStock} in stock.`);
            }
        } else {
            cart.push({ product_id: id, name: name, price: price, quantity: 1, maxStock: maxStock });
        }
        console.log("Current Cart:", cart);
        updateCartUI();
    }

    function updateQuantity(id, change) {
        let item = cart.find(i => i.product_id === id);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                cart = cart.filter(i => i.product_id !== id);
            } else if (item.quantity > item.maxStock) {
                item.quantity = item.maxStock;
            }
        }
        updateCartUI();
    }

    function updateCartUI() {
        let tbody = document.getElementById('cart-items');
        let totalSpan = document.getElementById('grand-total');

        if (cart.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">Cart is empty. Tap items to add.</td></tr>';
            totalSpan.innerText = '0.00';
            return;
        }

        tbody.innerHTML = '';
        let grandTotal = 0;

        cart.forEach(item => {
            let subtotal = item.price * item.quantity;
            grandTotal += subtotal;

            tbody.innerHTML += `
                <tr>
                    <td class="fw-bold text-truncate" style="max-width: 120px;">${item.name}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-secondary px-2" onclick="updateQuantity(${item.product_id}, -1)">-</button>
                            <button type="button" class="btn btn-light px-3 fw-bold" disabled>${item.quantity}</button>
                            <button type="button" class="btn btn-outline-secondary px-2" onclick="updateQuantity(${item.product_id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-bold">${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                </tr>
            `;
        });

        totalSpan.innerText = grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function submitSale() {
        if (cart.length === 0) {
            alert("Please add items to the cart before checking out!");
            return;
        }

        let container = document.getElementById('hidden-cart-inputs');
        container.innerHTML = '';

        cart.forEach((item, index) => {
            container.innerHTML += `<input type="hidden" name="cart[${index}][product_id]" value="${item.product_id}">`;
            container.innerHTML += `<input type="hidden" name="cart[${index}][quantity]" value="${item.quantity}">`;
        });

        document.getElementById('checkout-form').submit();
    }

    //search functionality
    // Hakikisha nambari hii inakaa ndani ya script tag yako ya sasa
document.getElementById('posProductSearch').addEventListener('keyup', function() {
    let query = this.value.toLowerCase().trim();
    let productCards = document.querySelectorAll('.product-card-wrapper');
    let foundAny = false;

    productCards.forEach(function(card) {
        let button = card.querySelector('button');
        let productName = button.getAttribute('data-name');
        let productBarcode = button.getAttribute('data-barcode');

        // Kama jina au barcode ya bidhaa ina neno lililoandikwa, ionyeshe. Kama haina, ifiche!
        if (productName.includes(query) || productBarcode.includes(query)) {
            card.style.display = 'block';
            foundAny = true;
        } else {
            card.style.display = 'none';
        }
    });

    // Mtego wa Barcode Scanner:
    // Kama mtumiaji amescan barcode kamili na bidhaa ikapatikana yenyewe peke yake,
    // iingize automatiki kwenye kikapu (cart) na usafishe sanduku la search.
    if (query.length >= 4) { // Barcode nyingi zina urefu kuanzia herufi 4 na kuendelea
        let visibleCards = Array.from(productCards).filter(c => c.style.display === 'block');
        if (visibleCards.length === 1) {
            let targetButton = visibleCards[0].querySelector('button');
            // Bonyeza kile kifungo automatiki kuingiza kwenye cart
            targetButton.click();
            // Safisha sehemu ya search tayari kwa ajili ya bidhaa inayofuata
            this.value = '';
            // Rudisha bidhaa zote zionekane tena baada ya kusafisha
            productCards.forEach(c => c.style.display = 'block');
        }
    }
});

// Pro-Tip ya Dodoma Shop: Kuzuia focus isitoke kwenye search box
// Hata kama cashier akibonyeza pembeni kimakosa, focus inarudi kwenye input ili scanner isigome
document.addEventListener('click', function(e) {
    // Usirudishe focus kama amebonyeza input yenyewe, au select box au button za cart
    if (e.target.id !== 'posProductSearch' && !e.target.closest('.btn-group') && e.target.tagName !== 'SELECT') {
        setTimeout(() => {
            document.getElementById('posProductSearch').focus();
        }, 100);
    }
});
</script>
@endsection
