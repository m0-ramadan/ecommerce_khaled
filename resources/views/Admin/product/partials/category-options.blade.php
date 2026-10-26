@foreach ($categories as $category)
    <option value="{{ $category->id }}"
        @selected((string) request('category_id') === (string) $category->id)>
        {{ str_repeat('— ', $level ?? 0) }}{{ $category->name }}
    </option>
    @if ($category->childrenRecursive->isNotEmpty())
        @include('Admin.product.partials.category-options', [
            'categories' => $category->childrenRecursive,
            'level' => ($level ?? 0) + 1,
        ])
    @endif
@endforeach
