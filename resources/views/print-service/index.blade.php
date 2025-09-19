<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ViVia Print Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 800px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            color: white;
            font-weight: bold;
        }
        .step.active {
            background: #28a745;
        }
        .step.completed {
            background: #007bff;
        }
        .upload-area {
            border: 3px dashed #007bff;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .upload-area:hover {
            border-color: #0056b3;
            background: #f8f9fa;
        }
        .upload-area.dragover {
            border-color: #28a745;
            background: #e8f5e8;
        }
        .file-item {
            display: flex;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .btn-print {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            border-radius: 25px;
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40,167,69,0.4);
        }
        .price-display {
            background: #e9ecef;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }
        .product-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #007bff;
        }
        .product-card.selected {
            border-color: #007bff !important;
            background: #f8f9ff;
        }
        .product-card.selected .card-body {
            background: linear-gradient(45deg, #e3f2fd, #bbdefb);
        }
        .session-info {
            background: rgba(255,255,255,0.1);
            padding: 10px;
            border-radius: 10px;
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="session-info">
            <h5><i class="fas fa-clock"></i> Session Active</h5>
            <small>Session expires: {{ $session->expires_at->format('d/m/Y H:i') }}</small>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">
                    <i class="fas fa-print text-primary"></i>
                    ViVia Print Service
                </h2>

                <div class="step-indicator">
                    <div class="step active" id="step-1">1</div>
                    <div class="step" id="step-2">2</div>
                    <div class="step" id="step-3">3</div>
                    <div class="step" id="step-4">4</div>
                    <div class="step" id="step-5">5</div>
                </div>

                <!-- Step 1: File Upload -->
                <div id="upload-section" class="step-content">
                    <h4 class="mb-3"><i class="fas fa-upload"></i> Upload Files</h4>
                    <div class="upload-area" id="upload-area">
                        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                        <h5>Drop files here or click to browse</h5>
                        <p class="text-muted">Supports: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, TXT, CSV, LOG, RTF, ODT, ODS</p>
                        <p class="text-muted">Max size: 50MB per file, Max files: 10</p>
                        <input type="file" id="file-input" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.txt,.csv,.log,.rtf,.odt,.ods" style="display: none;">
                    </div>
                    
                    <div id="file-list" class="mt-3"></div>
                    
                    <button class="btn btn-primary mt-3" id="upload-btn" disabled>
                        <i class="fas fa-arrow-right"></i> Next Step
                    </button>
                </div>

                <!-- Step 2: Product Selection -->
                <div id="product-section" class="step-content" style="display: none;">
                    <h4 class="mb-3"><i class="fas fa-box"></i> Select Paper Type</h4>
                    <p class="text-muted mb-4">Choose the type of paper that best suits your printing needs.</p>
                    
                    <div class="row" id="product-list">
                        <!-- Products will be loaded here -->
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <button class="btn btn-secondary" onclick="previousStep()">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button class="btn btn-primary" id="product-next-btn" disabled>
                            <i class="fas fa-arrow-right"></i> Next Step
                        </button>
                    </div>
                </div>

                <!-- Step 3: Paper Size & Print Type Selection -->
                <div id="selection-section" class="step-content" style="display: none;">
                    <h4 class="mb-3"><i class="fas fa-paper-plane"></i> Select Paper Size & Print Type</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Paper Size</label>
                            <select class="form-select" id="paper-size">
                                <option value="">Select paper size</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Print Type</label>
                            <select class="form-select" id="print-type">
                                <option value="">Select print type</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Quantity (sets)</label>
                            <input type="number" class="form-control" id="quantity" value="1" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Pages</label>
                            <input type="number" class="form-control" id="total-pages" readonly>
                        </div>
                    </div>

                    <div class="price-display" id="price-display" style="display: none;">
                        <h5>Price Calculation</h5>
                        <div class="row">
                            <div class="col-6">Product:</div>
                            <div class="col-6 text-end" id="selected-product-name">-</div>
                        </div>
                        <div class="row">
                            <div class="col-6">Unit Price:</div>
                            <div class="col-6 text-end" id="unit-price">-</div>
                        </div>
                        <div class="row">
                            <div class="col-6">Total Pages:</div>
                            <div class="col-6 text-end" id="display-total-pages">-</div>
                        </div>
                        <div class="row">
                            <div class="col-6">Quantity:</div>
                            <div class="col-6 text-end" id="display-quantity">-</div>
                        </div>
                        <hr>
                        <div class="row fw-bold">
                            <div class="col-6">Total Price:</div>
                            <div class="col-6 text-end text-primary" id="total-price">-</div>
                        </div>
                    </div>

                    <button class="btn btn-secondary me-2" onclick="previousStep()">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn btn-primary" id="selection-next" disabled>
                        <i class="fas fa-arrow-right"></i> Next Step
                    </button>
                </div>

                <!-- Step 4: Customer Info & Payment -->
                <div id="payment-section" class="step-content" style="display: none;">
                    <h4 class="mb-3"><i class="fas fa-user"></i> Customer Information</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" id="customer-name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="customer-phone" required>
                        </div>
                    </div>

                    <h5 class="mt-4"><i class="fas fa-credit-card"></i> Payment Method</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card payment-option" data-method="toko">
                                <div class="card-body text-center">
                                    <i class="fas fa-store fa-2x mb-2"></i>
                                    <h6>Pay at Store</h6>
                                    <small>Pay when you pick up</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card payment-option" data-method="manual">
                                <div class="card-body text-center">
                                    <i class="fas fa-university fa-2x mb-2"></i>
                                    <h6>Bank Transfer</h6>
                                    <small>Upload payment proof</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card payment-option" data-method="automatic">
                                <div class="card-body text-center">
                                    <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                                    <h6>Online Payment</h6>
                                    <small>Pay with e-wallet/card</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="payment-proof-section" style="display: none;" class="mt-3">
                        <label class="form-label">Upload Payment Proof</label>
                        <input type="file" class="form-control" id="payment-proof" accept="image/*">
                    </div>

                    <button class="btn btn-secondary me-2" id="back-to-selection">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    <button class="btn btn-print" id="checkout-btn" disabled>
                        <i class="fas fa-check"></i> Complete Order
                    </button>
                </div>

                <!-- Step 5: Status -->
                <div id="status-section" class="step-content" style="display: none;">
                    <div class="text-center">
                        <div id="status-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let sessionToken = '{{ $session->session_token }}';
        let uploadedFiles = [];
        let selectedVariant = null;
        let paymentMethod = null;
        let currentCalculation = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            setupEventListeners();
            loadProducts();
        });

        function setupEventListeners() {
            console.log('Setting up event listeners');
            
            // File upload
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('file-input');
            
            console.log('Upload area:', uploadArea);
            console.log('File input:', fileInput);

            if (uploadArea && fileInput) {
                uploadArea.addEventListener('click', function() {
                    console.log('Upload area clicked');
                    fileInput.click();
                });
                uploadArea.addEventListener('dragover', handleDragOver);
                uploadArea.addEventListener('dragleave', handleDragLeave);
                uploadArea.addEventListener('drop', handleDrop);
                fileInput.addEventListener('change', handleFileSelect);
                console.log('File upload events attached');
            } else {
                console.error('Upload area or file input not found');
            }

            // Product selection
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantityInput.addEventListener('input', calculatePrice);
            }

            // Step navigation
            const uploadBtn = document.getElementById('upload-btn');
            const productNextBtn = document.getElementById('product-next-btn');
            const selectionNext = document.getElementById('selection-next');
            const checkoutBtn = document.getElementById('checkout-btn');

            if (uploadBtn) uploadBtn.addEventListener('click', goToProductSelection);
            if (productNextBtn) productNextBtn.addEventListener('click', goToSelection);
            if (selectionNext) selectionNext.addEventListener('click', goToPayment);
            if (checkoutBtn) checkoutBtn.addEventListener('click', doCheckout);

            // Selection changes
            const paperSize = document.getElementById('paper-size');
            if (paperSize) paperSize.addEventListener('change', updatePrintTypes);
            document.getElementById('print-type').addEventListener('change', calculatePrice);
            document.getElementById('quantity').addEventListener('input', calculatePrice);

            // Payment method selection
            document.querySelectorAll('.payment-option').forEach(option => {
                option.addEventListener('click', function() {
                    selectPaymentMethod(this.dataset.method);
                });
            });
        }

        let productData = null;
        let allProducts = [];

        async function loadProducts() {
            console.log('Loading products...');
            try {
                const response = await fetch('/print-service/products');
                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                console.log('Products data:', data);
                
                if (data.success && data.products.length > 0) {
                    allProducts = data.products;
                    displayProductList();
                    console.log('Products loaded successfully');
                } else {
                    console.error('No products found or API error:', data);
                    // Fallback - hide product step or show error
                    document.getElementById('product-section').style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading products:', error);
                // Fallback - hide product step or show error
                document.getElementById('product-section').style.display = 'none';
            }
        }

        function displayProductList() {
            const productList = document.getElementById('product-list');
            productList.innerHTML = '';
            
            allProducts.forEach(product => {
                const productCard = `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 product-card" data-product-id="${product.id}" onclick="selectProduct(${product.id})">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-file-text fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text text-muted">
                                    Available sizes: ${[...new Set(product.variants.map(v => v.paper_size))].join(', ')}<br>
                                    Print types: ${[...new Set(product.variants.map(v => v.print_type))].join(', ')}
                                </p>
                                <div class="price-range">
                                    <small class="text-success">
                                        From Rp ${Math.min(...product.variants.map(v => v.price)).toLocaleString()}
                                        - Rp ${Math.max(...product.variants.map(v => v.price)).toLocaleString()}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                productList.innerHTML += productCard;
            });
        }

        function selectProduct(productId) {
            // Remove previous selection
            document.querySelectorAll('.product-card').forEach(card => {
                card.classList.remove('border-primary', 'selected');
            });
            
            // Add selection to clicked card
            const selectedCard = document.querySelector(`[data-product-id="${productId}"]`);
            selectedCard.classList.add('border-primary', 'selected');
            
            // Store selected product
            productData = allProducts.find(p => p.id === productId);
            
            // Enable next button
            document.getElementById('product-next-btn').disabled = false;
            
            // Populate paper options for selected product
            populateProductOptions(productData);
        }

        function populateProductOptions(product) {
            const paperSizeSelect = document.getElementById('paper-size');
            const paperSizes = [...new Set(product.variants.map(v => v.paper_size))];
            
            paperSizeSelect.innerHTML = '<option value="">Select paper size</option>';
            paperSizes.forEach(size => {
                paperSizeSelect.innerHTML += `<option value="${size}">${size}</option>`;
            });
            
            paperSizeSelect.addEventListener('change', updatePrintTypes);
        }

        function updatePrintTypes() {
            const paperSize = document.getElementById('paper-size').value;
            const printTypeSelect = document.getElementById('print-type');
            
            printTypeSelect.innerHTML = '<option value="">Select print type</option>';
            
            if (paperSize && productData) {
                const availableTypes = productData.variants
                    .filter(v => v.paper_size === paperSize)
                    .map(v => ({
                        value: v.print_type,
                        label: v.print_type === 'bw' ? 'Black & White' : 'Color',
                        price: v.price,
                        stock: v.stock,
                        min_threshold: v.min_stock_threshold
                    }));
                
                availableTypes.forEach(type => {
                    let stockStatus = '';
                    if (type.stock <= 0) {
                        stockStatus = ' (Out of Stock)';
                    } else if (type.stock <= type.min_threshold) {
                        stockStatus = ` (Low Stock: ${type.stock})`;
                    } else {
                        stockStatus = ` (Stock: ${type.stock})`;
                    }
                    
                    const option = document.createElement('option');
                    option.value = type.value;
                    option.textContent = `${type.label} - Rp ${parseFloat(type.price).toLocaleString()}${stockStatus}`;
                    option.disabled = type.stock <= 0;
                    printTypeSelect.appendChild(option);
                });
                
                printTypeSelect.addEventListener('change', calculatePrice);
            }
        }

        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('upload-area').classList.add('dragover');
        }

        function handleDragLeave(e) {
            e.preventDefault();
            document.getElementById('upload-area').classList.remove('dragover');
        }

        function handleDrop(e) {
            e.preventDefault();
            document.getElementById('upload-area').classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        }

        function handleFileSelect(e) {
            handleFiles(e.target.files);
        }

        async function handleFiles(files) {
            if (files.length > 10) {
                alert('Maximum 10 files allowed');
                return;
            }

            // 🛡️ FRONTEND SAFEGUARD: Check for duplicate files
            const newFiles = [];
            const duplicateFiles = [];

            for (let file of files) {
                if (file.size > 50 * 1024 * 1024) {
                    alert(`File ${file.name} is too large (max 50MB)`);
                    return;
                }

                // Check if file already exists in uploaded files
                const isDuplicate = uploadedFiles.some(existingFile => 
                    existingFile.file_name === file.name && 
                    existingFile.file_size === file.size
                );

                if (isDuplicate) {
                    duplicateFiles.push(file.name);
                } else {
                    newFiles.push(file);
                }
            }

            // Alert user about duplicates
            if (duplicateFiles.length > 0) {
                const message = `The following file(s) are already uploaded:\n${duplicateFiles.join('\n')}\n\nThey will be skipped.`;
                alert(message);
            }

            // If no new files to upload
            if (newFiles.length === 0) {
                return;
            }

            const formData = new FormData();
            formData.append('session_token', sessionToken);
            
            for (let file of newFiles) {
                formData.append('files[]', file);
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch('/print-service/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    uploadedFiles = data.files;
                    displayUploadedFiles();
                    document.getElementById('total-pages').value = data.total_pages;
                    document.getElementById('upload-btn').disabled = false;

                    // Show message about skipped files if any
                    if (data.skipped_files && data.skipped_files.length > 0) {
                        const skippedNames = data.skipped_files.map(f => f.name).join(', ');
                        alert(`Note: ${skippedNames} were skipped (already uploaded)`);
                    }
                } else {
                    alert('Upload failed: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Upload failed: ' + error.message);
            }
        }

        function displayUploadedFiles() {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';
            
            uploadedFiles.forEach(file => {
                fileList.innerHTML += `
                    <div class="file-item d-flex align-items-center mb-2 p-2 border rounded">
                        <i class="fas fa-file me-2"></i>
                        <span class="flex-grow-1">${file.name}</span>
                        <span class="badge bg-secondary me-2">${file.pages_count} pages</span>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="previewFile(${file.id})" title="Preview">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteFile(${file.id})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
        }

        async function calculatePrice() {
            const paperSize = document.getElementById('paper-size').value;
            const printType = document.getElementById('print-type').value;
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            const totalPages = parseInt(document.getElementById('total-pages').value) || 0;

            if (!paperSize || !printType || !totalPages || !productData) return;

            try {
                // Use the selected product from step 2, not the first product
                const variant = productData.variants.find(v => 
                    v.paper_size === paperSize && v.print_type === printType
                );
                
                if (variant) {
                    selectedVariant = variant;
                    
                    const requiredStock = totalPages * quantity;
                    if (variant.stock < requiredStock) {
                        alert(`Insufficient stock! Available: ${variant.stock} sheets, Required: ${requiredStock} sheets`);
                        document.getElementById('selection-next').disabled = true;
                        return;
                    }
                    
                    const calcResponse = await fetch('/print-service/calculate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                variant_id: variant.id,
                                total_pages: totalPages,
                                quantity: quantity
                            })
                        });

                        const calcData = await calcResponse.json();
                        
                        if (calcData.success) {
                            currentCalculation = calcData.calculation;
                            displayPrice(calcData.calculation, variant);
                            document.getElementById('selection-next').disabled = false;
                        }
                    } else {
                        console.error('Variant not found for selected options');
                    }
                
            } catch (error) {
                console.error('Price calculation error:', error);
            }
        }

        function displayPrice(calculation, variant = null) {
            // Display selected product name
            if (productData) {
                document.getElementById('selected-product-name').textContent = productData.name;
            }
            
            document.getElementById('unit-price').textContent = `Rp ${calculation.unit_price.toLocaleString()}`;
            document.getElementById('display-total-pages').textContent = calculation.total_pages;
            document.getElementById('display-quantity').textContent = calculation.quantity;
            document.getElementById('total-price').textContent = `Rp ${calculation.total_price.toLocaleString()}`;
            
            if (variant) {
                const requiredStock = calculation.total_pages * calculation.quantity;
                const stockInfo = document.getElementById('stock-info') || document.createElement('div');
                stockInfo.id = 'stock-info';
                stockInfo.className = 'row mt-2';
                
                let stockClass = 'text-success';
                let stockMessage = `Stock Available: ${variant.stock} sheets`;
                
                if (variant.stock <= variant.min_stock_threshold) {
                    stockClass = 'text-warning';
                    stockMessage = `⚠️ Low Stock: ${variant.stock} sheets`;
                }
                
                if (variant.stock < requiredStock) {
                    stockClass = 'text-danger';
                    stockMessage = `❌ Insufficient Stock: ${variant.stock} sheets (Need: ${requiredStock})`;
                }
                
                stockInfo.innerHTML = `
                    <div class="col-6">Stock Status:</div>
                    <div class="col-6 text-end ${stockClass}">${stockMessage}</div>
                `;
                
                const priceDisplay = document.getElementById('price-display');
                if (!document.getElementById('stock-info')) {
                    priceDisplay.appendChild(stockInfo);
                }
            }
            
            document.getElementById('price-display').style.display = 'block';
        }

        function selectPaymentMethod(method) {
            paymentMethod = method;
            
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('border-primary');
            });
            
            document.querySelector(`[data-method="${method}"]`).classList.add('border-primary');
            
            if (method === 'manual') {
                document.getElementById('payment-proof-section').style.display = 'block';
            } else {
                document.getElementById('payment-proof-section').style.display = 'none';
            }
            
            updateCheckoutButton();
        }

        function updateCheckoutButton() {
            const name = document.getElementById('customer-name').value;
            const phone = document.getElementById('customer-phone').value;
            
            document.getElementById('checkout-btn').disabled = !(name && phone && paymentMethod);
        }

        // Add event listeners for customer info
        document.getElementById('customer-name').addEventListener('input', updateCheckoutButton);
        document.getElementById('customer-phone').addEventListener('input', updateCheckoutButton);

        async function doCheckout() {
            const formData = new FormData();
            formData.append('session_token', sessionToken);
            formData.append('customer_name', document.getElementById('customer-name').value);
            formData.append('customer_phone', document.getElementById('customer-phone').value);
            formData.append('variant_id', selectedVariant.id);
            formData.append('payment_method', paymentMethod);
            formData.append('total_pages', document.getElementById('total-pages').value);
            formData.append('quantity', document.getElementById('quantity').value);
            
            // Send files as array - each file ID separately
            uploadedFiles.forEach((file, index) => {
                formData.append(`files[${index}]`, file.id);
            });

            if (paymentMethod === 'manual') {
                const proofFile = document.getElementById('payment-proof').files[0];
                if (proofFile) {
                    formData.append('payment_proof', proofFile);
                }
            }

            try {
                const response = await fetch('/print-service/checkout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    if (data.payment.status === 'redirect') {
                        window.location.href = data.payment.redirect_url;
                    } else {
                        showOrderStatus(data.order, data.payment);
                    }
                } else {
                    alert('Checkout failed: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Checkout error:', error);
                alert('Checkout failed');
            }
        }

        function showOrderStatus(order, payment) {
            goToStatus();
            
            let statusContent = `
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>Order Created Successfully!</h4>
                <p>Order Code: <strong>${order.order_code}</strong></p>
                <p>Total: <strong>Rp ${order.total_price.toLocaleString()}</strong></p>
            `;

            if (payment.status === 'pending') {
                statusContent += `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        ${payment.message}
                    </div>
                `;
            }

            statusContent += `
                <button class="btn btn-primary" onclick="window.location.reload()">
                    <i class="fas fa-redo"></i> New Order
                </button>
            `;

            document.getElementById('status-content').innerHTML = statusContent;
        }

        // Step navigation functions
        function goToUpload() {
            setActiveStep(1);
            showSection('upload-section');
        }

        function goToProductSelection() {
            setActiveStep(2);
            showSection('product-section');
        }

        function goToSelection() {
            setActiveStep(3);
            showSection('selection-section');
        }

        function goToPayment() {
            setActiveStep(4);
            showSection('payment-section');
            updatePaymentSummary();
        }

        function goToStatus() {
            setActiveStep(5);
            showSection('status-section');
        }

        function previousStep() {
            const currentActiveStep = document.querySelector('.step.active');
            const currentStepNumber = parseInt(currentActiveStep.textContent);
            
            if (currentStepNumber === 2) {
                goToUpload();
            } else if (currentStepNumber === 3) {
                goToProductSelection();
            } else if (currentStepNumber === 4) {
                goToSelection();
            } else if (currentStepNumber === 5) {
                goToPayment();
            }
        }

        function setActiveStep(stepNumber) {
            // Remove active from all steps
            document.querySelectorAll('.step').forEach(step => {
                step.classList.remove('active');
            });
            
            // Add completed to previous steps
            for (let i = 1; i < stepNumber; i++) {
                document.querySelector(`#step-${i}`).classList.add('completed');
            }
            
            // Set current step as active
            document.querySelector(`#step-${stepNumber}`).classList.add('active');
        }

        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.step-content').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show target section
            document.getElementById(sectionId).style.display = 'block';
        }

        function goToStatus() {
            setActiveStep(5);
            showSection('status-section');
        }

        async function deleteFile(fileId) {
            if (!confirm('Are you sure you want to delete this file?')) {
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const response = await fetch('/print-service/file/' + fileId, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        session_token: sessionToken,
                        file_id: fileId
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    uploadedFiles = data.files;
                    displayUploadedFiles();
                    document.getElementById('total-pages').value = data.total_pages;
                    
                    if (data.files.length === 0) {
                        document.getElementById('upload-btn').disabled = true;
                    }
                } else {
                    alert('Delete failed: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Delete failed: ' + error.message);
            }
        }

        async function previewFile(fileId) {
            try {
                const url = `/print-service/preview/${fileId}?session_token=${sessionToken}&file_id=${fileId}`;
                window.open(url, '_blank');
            } catch (error) {
                console.error('Preview error:', error);
                alert('Preview failed: ' + error.message);
            }
        }
    </script>

    <style>
        .payment-option {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .payment-option.border-primary {
            border-color: #007bff !important;
            border-width: 2px !important;
        }
    </style>
</body>
</html>
