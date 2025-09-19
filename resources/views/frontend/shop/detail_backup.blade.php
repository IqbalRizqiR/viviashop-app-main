@extends('frontend.app')

@section('title', 'Product Detail')

@section('content')
<div class="container">
    <h1>Product Detail Test</h1>
    <div class="row">
        <div class="col-md-12">
            <h2>{{ $parentProduct->name }}</h2>
            <p>Product ID: {{ $parentProduct->id }}</p>
            <p>Type: {{ $parentProduct->type }}</p>
            <p>Price: {{ number_format($parentProduct->price) }}</p>
            
            @if($parentProduct->type == 'configurable' && $variants->count() > 0)
                <h3>Variants:</h3>
                <ul>
                @foreach($variants as $variant)
                    <li>
                        {{ $variant->name }} - {{ number_format($variant->price) }}
                        @if($variant->variantAttributes)
                            <ul>
                            @foreach($variant->variantAttributes as $attr)
                                <li>{{ $attr->attribute_name }}: {{ $attr->value }}</li>
                            @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
