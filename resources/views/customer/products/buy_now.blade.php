@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Confirm Purchase</h3>

    <form method="POST" action="{{ route('purchase.confirm') }}">
        @csrf

        <input type="hidden" name="product_id" value="{{ $product->ProductID }}">

        <div class="mb-3">
            <label>Product</label>
            <input class="form-control" value="{{ $product->ProductName }}" disabled>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label>Delivery Type</label>
            <select name="delivery_type" id="delivery_type" class="form-control" required>
                <option value="Pickup">Pickup at Car Wash (Free)</option>
                <option value="Delivery">Home Delivery (RM5)</option>
            </select>
        </div>

        <div class="mb-3" id="addressBox" style="display:none;">
            <label>Delivery Address</label>
            <textarea name="delivery_address" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Confirm & Pay</button>
    </form>
</div>

<script>
document.getElementById('delivery_type').addEventListener('change', function () {
    document.getElementById('addressBox').style.display =
        this.value === 'Delivery' ? 'block' : 'none';
});
</script>
@endsection
