<div class="row product-row g-2 align-items-start mb-2 border rounded p-2">
    <div class="col-md-3">
        <select name="products[{{ $i }}][product_id]" class="form-select">
            <option value="">اختر منتج</option>
            @foreach ($products as $p)
                <option value="{{ $p->id }}" @selected((int) ($pp->product_id ?? 0) === (int) $p->id)>{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <select name="products[{{ $i }}][type]" class="form-select">
            <option value="main" @selected(($pp->type ?? 'main') === 'main')>أساسي</option>
            <option value="optional" @selected(($pp->type ?? '') === 'optional')>مرشح</option>
        </select>
    </div>
    <div class="col-md-2">
        <input type="number" min="0" class="form-control" name="products[{{ $i }}][base_quantity]"
            value="{{ $pp->base_quantity ?? 0 }}" placeholder="الحد الأدنى">
    </div>
    <div class="col-md-2">
        <input type="number" step="0.01" min="0" class="form-control" name="products[{{ $i }}][base_price]"
            value="{{ $pp->base_price ?? 0 }}" placeholder="السعر الأساسي">
    </div>
    <div class="col-md-2">
        <textarea class="form-control" rows="2" name="products[{{ $i }}][tiers]" placeholder="50:500">{{ $pp ? $pp->tiers->map(fn ($t) => $t->quantity . ':' . $t->price)->implode("\n") : '' }}</textarea>
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-danger btn-sm w-100"
            onclick="this.closest('.product-row').remove()">×</button>
    </div>
</div>
