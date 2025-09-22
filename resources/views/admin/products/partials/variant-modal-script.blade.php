<script>
// Consolidated internal variant modal implementation (safe, single source)
(function(){
    try {
        var isSmartPrint = {{ $product->is_smart_print_enabled && $product->is_print_service ? 'true' : 'false' }};
        var productId = {{ $product->id }};
    } catch(e) {
        // Fallback if product isn't available
        var isSmartPrint = false;
        var productId = null;
    }

    window._internal_closeVariantModal = function() {
        var modal = document.getElementById('variantModal');
        if (modal) modal.remove();
        try { document.body.classList.remove('modal-open'); } catch(e) {}
    };

    window._internal_addAttribute = function(isSmart) {
        var container = document.getElementById('attributeContainer');
        if (!container) return;
        var row = document.createElement('div');
        row.className = 'attribute-row';
        row.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;';
        var namePlaceholder = isSmart ? 'e.g. material, finish, binding' : 'e.g. Size, Color, Material';
        row.innerHTML = '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>' +
            '<input type="text" name="attribute_names[]" placeholder="' + namePlaceholder + '" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>' +
            '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>' +
            '<input type="text" name="attribute_values[]" placeholder="Attribute Value" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>' +
            '<div><button type="button" onclick="window._internal_removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
        container.appendChild(row);
    };

    window._internal_removeAttribute = function(button) {
        if (!button) return;
        var row = button.closest('.attribute-row');
        if (row) row.remove();
    };

    window._internal_showProperVariantModal = function() {
        // Remove any existing overlay
        var existing = document.getElementById('variantModal');
        if (existing) existing.remove();

        var overlay = document.createElement('div');
        overlay.id = 'variantModal';
        overlay.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 99999; display: flex; align-items: center; justify-content: center; overflow-y: auto;';

        var modalHtml = '<div style="background: white; border-radius: 8px; width: 90%; max-width: 800px; max-height: 90vh; overflow-y: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">';

        var headerStyle = isSmartPrint ? 'background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%); border-bottom: 2px solid #007bff;' : 'background: #f8f9fa; border-bottom: 1px solid #dee2e6;';
        var titleStyle = isSmartPrint ? 'color: #007bff; font-weight: 600;' : 'color: #495057;';
        var modalTitle = isSmartPrint ? 'Add Smart Print Variant' : 'Add Product Variant';

        modalHtml += '<div style="padding: 20px; ' + headerStyle + '">';
        modalHtml += '<div style="display: flex; justify-content: space-between; align-items: center;">';
        modalHtml += '<h4 style="margin: 0; ' + titleStyle + '">' + modalTitle + '</h4>';
        modalHtml += '<button onclick="window._internal_closeVariantModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6c757d;">&times;</button>';
        modalHtml += '</div></div>';

        modalHtml += '<div style="padding: 30px;">';
        modalHtml += '<form id="variantForm">';
        modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Variant Name *</label>';
        modalHtml += '<input type="text" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">SKU *</label>';
        modalHtml += '<input type="text" name="sku" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '</div>';

        modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Jual *</label>';
        modalHtml += '<input type="number" name="price" step="0.01" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Harga Beli</label>';
        modalHtml += '<input type="number" name="harga_beli" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Stock *</label>';
        modalHtml += '<input type="number" name="stock" required style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '</div>';

        modalHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Berat (kg)</label>';
        modalHtml += '<input type="number" name="weight" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Panjang (cm)</label>';
        modalHtml += '<input type="number" name="length" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Lebar (cm)</label>';
        modalHtml += '<input type="number" name="width" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Tinggi (cm)</label>';
        modalHtml += '<input type="number" name="height" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
        modalHtml += '</div>';

        modalHtml += '<hr style="margin: 30px 0; border: none; border-top: 2px solid #e9ecef;">';
        modalHtml += '<h5 style="margin-bottom: 15px; color: #495057;">Variant Attributes</h5>';

        if (isSmartPrint) {
            modalHtml += '<div style="background: #e3f2fd; border: 1px solid #1976d2; border-radius: 4px; padding: 15px; margin-bottom: 20px;">';
            modalHtml += '<div style="display: flex; align-items: center; color: #1976d2;"><i class="fas fa-info-circle" style="margin-right: 8px;"></i>';
            modalHtml += '<strong>Smart Print Service:</strong> paper_size dan print_type fields sudah dioptimalkan untuk layanan print</div>';
            modalHtml += '</div>';
        } else {
            modalHtml += '<div style="background: #f8f9fa; border: 1px solid #6c757d; border-radius: 4px; padding: 15px; margin-bottom: 20px;">';
            modalHtml += '<div style="display: flex; align-items: center; color: #6c757d;"><i class="fas fa-box" style="margin-right: 8px;"></i>';
            modalHtml += '<strong>Regular Product:</strong> konfigurasi atribut fleksibel untuk produk umum</div>';
            modalHtml += '</div>';
        }

        modalHtml += '<div id="attributeContainer">';
        if (isSmartPrint) {
            modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
            modalHtml += '<input type="text" name="attribute_names[]" value="paper_size" readonly style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; background-color: #f8f9fa;"></div>';
            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
            modalHtml += '<input type="text" name="attribute_values[]" placeholder="e.g. A4, A3, Letter" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
            modalHtml += '<div><button type="button" onclick="window._internal_removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
            modalHtml += '</div>';
            modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
            modalHtml += '<input type="text" name="attribute_names[]" value="print_type" readonly style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; background-color: #f8f9fa;"></div>';
            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
            modalHtml += '<input type="text" name="attribute_values[]" placeholder="e.g. bw, color, grayscale" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
            modalHtml += '<div><button type="button" onclick="window._internal_removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
            modalHtml += '</div>';
        } else {
            modalHtml += '<div class="attribute-row" style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 10px; align-items: end;">';
            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Name</label>';
            modalHtml += '<input type="text" name="attribute_names[]" placeholder="e.g. Size, Color, Material" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
            modalHtml += '<div><label style="display: block; margin-bottom: 5px; font-weight: 600;">Attribute Value</label>';
            modalHtml += '<input type="text" name="attribute_values[]" placeholder="Attribute Value" style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px;"></div>';
            modalHtml += '<div><button type="button" onclick="window._internal_removeAttribute(this)" style="padding: 10px 15px; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Remove</button></div>';
            modalHtml += '</div>';
        }
        modalHtml += '</div>';

        modalHtml += '<button type="button" onclick="window._internal_addAttribute(' + isSmartPrint + ')" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 10px;">+ Add Attribute</button>';

        modalHtml += '</form></div>';

    modalHtml += '<div style="padding: 20px; background: #f8f9fa; border-top: 1px solid #dee2e6; display: flex; justify-content: flex-end; gap: 10px;">';
    modalHtml += '<button id="modalCancelVariantBtn" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>';
    modalHtml += '<button id="modalSaveVariantBtn" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Variant</button>';
    modalHtml += '</div>';

        modalHtml += '</div>';

        overlay.innerHTML = modalHtml;
        document.body.appendChild(overlay);

        // Attach handlers to avoid relying on inline onclick and ensure global wrapper is used
        try {
            var cancelBtn = document.getElementById('modalCancelVariantBtn');
            if (cancelBtn) cancelBtn.addEventListener('click', window._internal_closeVariantModal);

            var saveBtn = document.getElementById('modalSaveVariantBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    try {
                        // Call the internal submit implementation to avoid any global recursion
                        if (typeof window._internal_submitVariant === 'function') {
                            return window._internal_submitVariant(productId, saveBtn);
                        }
                        // As a last resort, try preserved caller
                        if (typeof window._call_saveVariant === 'function') {
                            return window._call_saveVariant(productId);
                        }
                        alert('Save implementation not available. Check console for details.');
                    } catch (ex) {
                        console.error('Error invoking internal submit:', ex);
                        alert('Error saving variant. See console for details.');
                    }
                });
            }
        } catch (e) { console.warn('Failed to attach modal handlers', e); }
    };

    // Self-contained submit implementation to avoid calling external wrappers which caused recursion in some page states.
    try {
        if (typeof window._internal_submitVariant === 'undefined') {
            window._internal_submitVariant = function(productId, saveBtn) {
                try {
                    var form = document.getElementById('variantForm');
                    if (!form) { alert('Form not found'); return; }

                    // Validate required fields
                    var req = ['name','sku','price','stock'];
                    for (var i=0;i<req.length;i++){
                        var el = form.querySelector('[name="' + req[i] + '"]');
                        if (!el || !String(el.value).trim()) { alert(req[i] + ' is required'); if (el) el.focus(); return; }
                    }

                    var fd = new FormData();
                    // Attributes
                    var nameInputs = Array.from(form.querySelectorAll('input[name="attribute_names[]"]'));
                    var valueInputs = Array.from(form.querySelectorAll('input[name="attribute_values[]"]'));
                    if (nameInputs.length === 0) { alert('Please add at least one attribute for this variant.'); return; }
                    for (var j=0;j<nameInputs.length;j++){
                        var aName = nameInputs[j] ? nameInputs[j].value : '';
                        var aValue = valueInputs[j] ? valueInputs[j].value : '';
                        if (!aName || !aValue) { alert('Each attribute must have a name and value'); return; }
                        fd.append('attributes['+j+'][attribute_name]', aName);
                        fd.append('attributes['+j+'][attribute_value]', aValue);
                    }
                    ['name','sku','price','harga_beli','stock','weight','length','width','height'].forEach(function(k){
                        var el = form.querySelector('[name="'+k+'"]'); if (el) fd.append(k, el.value);
                    });
                    fd.append('product_id', productId);

                    if (saveBtn) { try { saveBtn.disabled = true; saveBtn.textContent = 'Saving...'; } catch(e){} }

                    console.log('Submitting variant via internal submit (product_id):', productId);

                    fetch('/admin/variants/create', {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(response){
                        var contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            return response.text().then(function(text){ throw new Error('Server returned non-JSON response. Status: '+response.status+'\n'+text); });
                        }
                        return response.json().then(function(json){ return { status: response.status, json: json }; });
                    })
                    .then(function(res){
                        var status = res.status, json = res.json;
                        if (status === 422) {
                            var errors = json.errors || {}; var messages = [];
                            Object.keys(errors).forEach(function(k){ if (Array.isArray(errors[k])) messages = messages.concat(errors[k]); else if (typeof errors[k] === 'string') messages.push(errors[k]); });
                            alert('Validation failed:\n'+ (messages.length?messages.join('\n'):(json.message||'Validation failed')) );
                            if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; }
                            return;
                        }
                        if (json && (json.success || (json.message && String(json.message).toLowerCase().includes('created')))) {
                            alert('Variant saved successfully!');
                            window._internal_closeVariantModal();
                            location.reload();
                            return;
                        }
                        alert('Error: '+(json.message||'Failed to save variant'));
                        if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; }
                    })
                    .catch(function(err){ console.error('Internal submit error:', err); alert('Error: '+err.message+'\n\nCheck browser console for details.'); if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; } });
                } catch (e) { console.error('Unexpected error in _internal_submitVariant', e); alert('Unexpected error. See console.'); if (saveBtn) { try { saveBtn.disabled = false; saveBtn.textContent = 'Save Variant'; } catch(e){} } }
            };
        }
    } catch (e) { console.warn('Failed to define _internal_submitVariant', e); }

    // Expose aliased names for backwards compatibility
    try { if (typeof window.showProperVariantModal === 'undefined') window.showProperVariantModal = function(){ return window._internal_showProperVariantModal(); }; } catch(e) {}
    try { if (typeof window.showVariantModal === 'undefined') window.showVariantModal = function(){ return window._internal_showProperVariantModal(); }; } catch(e) {}
    // Capture any existing original saveVariant implementation (avoid overwriting it)
    try {
        if (typeof window.saveVariant === 'function') {
            // If there's already a global saveVariant, preserve it under _original_saveVariant
            window._original_saveVariant = window.saveVariant;
        } else if (typeof saveVariant === 'function') {
            // If function name exists in scope (non-window), capture it too
            window._original_saveVariant = saveVariant;
        }
    } catch (e) {
        console.warn('Failed to capture original saveVariant', e);
    }

    // Provide a safe caller that invokes the preserved original implementation without risk of recursion
    try {
        if (typeof window._call_saveVariant === 'undefined') {
            window._call_saveVariant = function(productId) {
                try {
                    if (typeof window._original_saveVariant === 'function') {
                        return window._original_saveVariant(productId);
                    }
                    // Last-resort fallbacks (very defensive)
                    if (typeof window.saveVariant === 'function' && window.saveVariant !== window._call_saveVariant) {
                        return window.saveVariant(productId);
                    }
                    if (typeof saveVariant === 'function') {
                        return saveVariant(productId);
                    }
                    console.warn('No original saveVariant implementation found');
                    alert('Save function unavailable. Please check console for details.');
                } catch (ex) {
                    console.error('Error inside _call_saveVariant:', ex);
                    alert('Error saving variant. See console for details.');
                }
            };
        }
    } catch (e) { console.warn('Failed to define _call_saveVariant', e); }
})();
</script>
