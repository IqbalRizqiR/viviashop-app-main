@extends('layouts.app')

@section('title', 'Smart Print Variant Manager')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-magic text-primary me-2"></i>Smart Print Variant Manager
            </h1>
            <small class="text-muted">
                Auto-fix and manage variants for products in 
                <a href="{{ route('admin.print-service.stock') }}" target="_blank" class="text-primary">
                    <i class="fas fa-external-link-alt"></i> Stock Management Print Service
                </a>
            </small>
        </div>
        <div>
            <a href="{{ route('admin.print-service.stock') }}" class="btn btn-info btn-sm" target="_blank">
                <i class="fas fa-boxes me-1"></i>View Stock Management
            </a>
        </div>
    </div>

    <!-- Auto-Fix Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-tools me-2"></i>Auto-Fix Existing Variants
            </h6>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Automatically detect and fix paper_size and print_type for variants that have empty values.
                This will scan variant names for keywords like "A4", "Black & White", "Color", etc.
                <br><small><strong>Note:</strong> Only affects products that are checked as "Print Service" in product creation.</small>
            </p>
            
            @if($problematicVariants->count() > 0)
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Found {{ $problematicVariants->count() }} variants with missing print fields:</h5>
                    <ul class="mb-0">
                        @foreach($problematicVariants as $variant)
                        <li>
                            <strong>{{ $variant->product->name }}</strong> → {{ $variant->name }}
                            <small class="text-muted">
                                (Paper Size: {{ $variant->paper_size ?? 'Missing' }}, 
                                Print Type: {{ $variant->print_type ?? 'Missing' }})
                            </small>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <button class="btn btn-warning" onclick="autoFixVariants()">
                    <i class="fas fa-magic me-1"></i>Auto-Fix All Variants
                </button>
            @else
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>All variants have proper paper_size and print_type values!
                </div>
            @endif
        </div>
    </div>

    <!-- Create Variants Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-plus-circle me-2"></i>Create Smart Print Variants
            </h6>
        </div>
        <div class="card-body">
            <p class="text-muted">
                Create standard Black & White and Color variants for print service products that don't have variants yet.
                <br><small><strong>Note:</strong> Only shows products that are checked as "Print Service" in product creation.</small>
            </p>
            
            @if($productsWithoutVariants->count() > 0)
                <div class="alert alert-info border-primary">
                    <h5><i class="fas fa-info-circle me-2"></i>Found {{ $productsWithoutVariants->count() }} print service products without variants:</h5>
                    <p class="mb-3">These products will not appear in <strong>Stock Management Print Service</strong> until they have variants with proper paper_size and print_type fields.</p>
                    <div class="row">
                        @foreach($productsWithoutVariants as $product)
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-box me-2"></i>{{ $product->name }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>No variants found</strong> - This product needs variants to appear in Stock Management.
                                    </div>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-money-bill me-1"></i>Base Price</span>
                                        <input type="number" class="form-control" id="price_{{ $product->id }}" value="2000" min="100">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <button class="btn btn-success btn-block" onclick="createVariants({{ $product->id }})">
                                        <i class="fas fa-plus me-1"></i>Create Black & White + Color Variants
                                    </button>
                                    <small class="text-muted mt-2 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Will create: "{{ $product->name }} - Black & White" (Rp <span id="bw_price_{{ $product->id }}">2,000</span>) 
                                        and "{{ $product->name }} - Color" (Rp <span id="color_price_{{ $product->id }}">5,000</span>)
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3 p-3 bg-light rounded">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>What will be created:</h6>
                        <ul class="mb-0">
                            <li><strong>Black & White variant:</strong> A4 paper size, BW print type, base price</li>
                            <li><strong>Color variant:</strong> A4 paper size, Color print type, 2.5x base price</li>
                            <li><strong>Auto-generated SKUs and barcodes</strong> to avoid duplicates</li>
                            <li><strong>Default stock:</strong> 100 sheets (BW), 50 sheets (Color)</li>
                        </ul>
                    </div>
                </div>
            @else
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>All print service products have variants!
                </div>
            @endif
        </div>
    </div>

    <!-- Results Section -->
    <div id="results-section" style="display: none;">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-check-circle me-2"></i>Results
                </h6>
            </div>
            <div class="card-body" id="results-content">
                <!-- Results will be populated here -->
            </div>
        </div>
    </div>
</div>

<script>
// Update price preview when base price changes
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for all price inputs
    document.querySelectorAll('input[id^="price_"]').forEach(function(input) {
        const productId = input.id.replace('price_', '');
        
        input.addEventListener('input', function() {
            updatePricePreview(productId, this.value);
        });
    });
});

function updatePricePreview(productId, basePrice) {
    const bwPrice = parseInt(basePrice);
    const colorPrice = Math.floor(bwPrice * 2.5);
    
    document.getElementById('bw_price_' + productId).textContent = bwPrice.toLocaleString();
    document.getElementById('color_price_' + productId).textContent = colorPrice.toLocaleString();
}

function autoFixVariants() {
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
    
    fetch('/admin/smart-print-variant/auto-fix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        showResults(data);
        if(data.success) {
            setTimeout(() => {
                location.reload();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showResults({
            success: false,
            message: 'An error occurred: ' + error.message
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-magic me-1"></i>Auto-Fix All Variants';
    });
}

function createVariants(productId) {
    const btn = event.target;
    const basePrice = document.getElementById(`price_${productId}`).value;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';
    
    fetch(`/admin/smart-print-variant/create-variants/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            base_price: parseInt(basePrice)
        })
    })
    .then(response => response.json())
    .then(data => {
        showResults(data);
        if(data.success) {
            setTimeout(() => {
                location.reload();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showResults({
            success: false,
            message: 'An error occurred: ' + error.message
        });
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plus me-1"></i>Create Variants';
    });
}

function showResults(data) {
    const resultsSection = document.getElementById('results-section');
    const resultsContent = document.getElementById('results-content');
    
    let html = `<div class="alert alert-${data.success ? 'success' : 'danger'}">`;
    html += `<h5>${data.message}</h5>`;
    
    if(data.details && data.details.length > 0) {
        html += '<ul class="mb-0">';
        data.details.forEach(detail => {
            html += `<li>${detail}</li>`;
        });
        html += '</ul>';
    }
    
    if(data.variants && data.variants.length > 0) {
        html += '<h6 class="mt-3">Created Variants:</h6>';
        html += '<ul class="mb-0">';
        data.variants.forEach(variant => {
            html += `<li><strong>${variant.name}</strong> - ${variant.paper_size} ${variant.print_type} - Rp ${variant.price}</li>`;
        });
        html += '</ul>';
    }
    
    html += '</div>';
    
    resultsContent.innerHTML = html;
    resultsSection.style.display = 'block';
    resultsSection.scrollIntoView({ behavior: 'smooth' });
}
</script>
@endsection