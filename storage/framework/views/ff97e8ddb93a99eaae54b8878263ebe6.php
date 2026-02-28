

<?php $__env->startSection('title', 'Bill Layout Designer'); ?>

<?php $__env->startSection('page-title', 'Bill Layout Designer'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item active">Bill Layout Designer</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- PDF-lib Library -->
<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

<?php
use App\Models\BillLayout;
$schoolId = auth()->user()->school_id;
$defaultLayouts = BillLayout::getDefaultAdmissionLayout();
$variableLabels = BillLayout::getVariableLabels();

try {
    $billLayouts = BillLayout::getLayouts($schoolId, $billType);
    
    $variables = [];
    foreach ($defaultLayouts as $key => $default) {
        $variables[$key] = [
            'label' => $variableLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
            'x' => isset($billLayouts[$key]) ? $billLayouts[$key]->x_position : $default['x'],
            'y' => isset($billLayouts[$key]) ? $billLayouts[$key]->y_position : $default['y'],
            'font_size' => isset($billLayouts[$key]) ? $billLayouts[$key]->font_size : $default['font_size']
        ];
    }
} catch (\Exception $e) {
    $variables = [];
    foreach ($defaultLayouts as $key => $default) {
        $variables[$key] = [
            'label' => $variableLabels[$key] ?? ucfirst(str_replace('_', ' ', $key)),
            'x' => $default['x'],
            'y' => $default['y'],
            'font_size' => $default['font_size']
        ];
    }
}

// Get saved template path
$templatePath = 'pdf-templates/' . $billType . '_template.pdf';

// Sample data for preview
$previewData = [
    'receipt_no' => '999',
    'student_roll' => '24',
    'student_name' => 'Soumyadeb Dutta',
    'student_class' => 'Pre-Primary',
    'student_section' => 'A',
    'student_medium' => 'Bengali',
    'purpose_for' => 'Admission Fee',
    'mode_title' => 'Donate to school fund',
    'donate_amount' => '500',
    'conveyance_fees' => '600',
    'subtotal' => '1100',
    'less_amount' => '0',
    'less_advance' => '0',
    'due_paid' => '0',
    'total_amount' => '500',
    'advance_amount' => '0',
    'due_amount' => '0',
    'paid_by' => 'Cash',
    'received_by' => 'Office Staff',
    'date' => '05/02/2025',
    // Duplicate copy data
    'receipt_no_dup' => '999',
    'student_roll_dup' => '24',
    'student_name_dup' => 'Soumyadeb Dutta',
    'student_class_dup' => 'Pre-Primary',
    'student_section_dup' => 'A',
    'student_medium_dup' => 'Bengali',
    'purpose_for_dup' => 'Admission Fee',
    'mode_title_dup' => 'Donate to school fund',
    'donate_amount_dup' => '500',
    'conveyance_fees_dup' => '600',
    'subtotal_dup' => '1100',
    'less_amount_dup' => '0',
    'less_advance_dup' => '0',
    'due_paid_dup' => '0',
    'total_amount_dup' => '500',
    'advance_amount_dup' => '0',
    'due_amount_dup' => '0',
    'paid_by_dup' => 'Cash',
    'received_by_dup' => 'Office Staff',
    'date_dup' => '05/02/2025',
];
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Configure Bill Layout - <?php echo e(ucfirst($billType)); ?> Bill</h5>
                    <div>
                        <a href="<?php echo e(route('school-admin.bill-layouts.designer', ['bill_type' => 'admission'])); ?>" 
                           class="btn btn-sm <?php echo e($billType == 'admission' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                            Admission
                        </a>
                        <a href="<?php echo e(route('school-admin.bill-layouts.designer', ['bill_type' => 'registration'])); ?>" 
                           class="btn btn-sm <?php echo e($billType == 'registration' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                            Registration
                        </a>
                        <a href="<?php echo e(route('school-admin.bill-layouts.designer', ['bill_type' => 'monthly'])); ?>" 
                           class="btn btn-sm <?php echo e($billType == 'monthly' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                            Monthly
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- PDF Upload Section -->
                <div class="alert alert-info mb-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <strong><i class="fas fa-info-circle me-2"></i>Instructions:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Upload a blank PDF template for the <?php echo e($billType); ?> bill</li>
                                <li>Click on any field below to select it, then click on the PDF to place it</li>
                                <li>Enter X and Y coordinates directly in the fields below</li>
                                <li>Adjust font size as needed</li>
                                <li>Click Save Layout to save your changes</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <form action="<?php echo e(route('school-admin.bill-layouts.upload')); ?>" method="POST" enctype="multipart/form-data" class="mb-3" id="uploadForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="bill_type" value="<?php echo e($billType); ?>">
                    <div class="row align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Upload PDF Template:</label>
                            <input type="file" name="pdf_template" accept=".pdf" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Upload PDF
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Current Template Info -->
                <?php if(file_exists(public_path($templatePath))): ?>
                <div class="alert alert-success mb-3">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Template Loaded:</strong> <?php echo e($templatePath); ?>

                </div>
                <?php endif; ?>

                <!-- Position Settings Bar - Above PDF -->
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Position Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <label class="form-label">Selected Field:</label>
                                <input type="text" class="form-control" id="selectedFieldName" readonly placeholder="Click a field below">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">X Position:</label>
                                <input type="number" class="form-control" id="inputX" min="0" max="595" onchange="updatePositionFromInput()">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Y Position:</label>
                                <input type="number" class="form-control" id="inputY" min="0" max="842" onchange="updatePositionFromInput()">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Font Size:</label>
                                <input type="number" class="form-control" id="inputFontSize" min="6" max="24" onchange="updatePositionFromInput()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Controls:</label>
                                <div>
                                    <span class="badge bg-dark">Arrow Keys to Move | Shift+Arrow for 10px</span>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="clearSelection()">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Designer Area -->
                <div class="designer-container" style="position: relative; overflow: auto; background: #525659; padding: 20px; min-height: 500px;">
                    
                    <!-- PDF Canvas -->
                    <div id="pdfContainer" style="position: relative; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                        <?php if(file_exists(public_path($templatePath))): ?>
                        <iframe id="pdfFrame" src="<?php echo e(asset($templatePath)); ?>#toolbar=0&navpanes=0&scrollbar=0" 
                                style="width: 892px; height: 1263px; border: none;" allowtransparency="true"></iframe>
                        <?php else: ?>
                        <div style="width: 892px; height: 1263px; background: white; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-file-pdf fa-4x text-muted mb-3"></i>
                            <p class="text-muted">No PDF template uploaded yet</p>
                            <p class="text-muted">Upload a PDF to get started</p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Draggable Fields Container -->
                        <div id="fieldsContainer" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none;">
                            <!-- Fields will be rendered here -->
                        </div>
                    </div>
                </div>

                <!-- Field Labels - Click to Select -->
                <div class="mt-3 p-3 bg-dark rounded">
                    <h6 class="text-white mb-3"><i class="fas fa-mouse-pointer me-2"></i>Available Fields - Click to Select:</h6>
                    <div class="d-flex flex-wrap gap-2" id="fieldPalette">
                        <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" class="btn btn-sm btn-outline-light field-btn" 
                             data-field="<?php echo e($key); ?>"
                             data-label="<?php echo e($var['label']); ?>"
                             data-x="<?php echo e($var['x']); ?>"
                             data-y="<?php echo e($var['y']); ?>"
                             data-font-size="<?php echo e($var['font_size']); ?>">
                            <?php echo e($var['label']); ?>

                        </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Hidden Form to Save Layout -->
                <form action="<?php echo e(route('school-admin.bill-layouts.store')); ?>" method="POST" id="layoutForm">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="bill_type" value="<?php echo e($billType); ?>">
                    <input type="hidden" name="variables_json" id="variablesJson" value="">
                </form>

                <!-- Save Button -->
                <div class="mt-3">
                    <button type="button" class="btn btn-success" onclick="saveLayout()">
                        <i class="fas fa-save me-2"></i>Save Layout
                    </button>
                    <a href="<?php echo e(route('school-admin.bill-layouts.reset', ['bill_type' => $billType])); ?>" 
                       class="btn btn-warning"
                       onclick="return confirm('Are you sure you want to reset the layout?')">
                        <i class="fas fa-redo me-2"></i>Reset Layout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Field positions storage
let fieldPositions = {};
let selectedField = null;

// PDF dimensions (A4) - Scaled for bigger preview
const PDF_WIDTH = 595;
const PDF_HEIGHT = 842;
const SCALE = 1.5; // Make PDF bigger

// Sample data for preview
const previewData = <?php echo json_encode($previewData, 15, 512) ?>;

// Initialize from saved data
<?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
fieldPositions['<?php echo e($key); ?>'] = {
    label: '<?php echo e($var["label"]); ?>',
    value: previewData['<?php echo e($key); ?>'] || '',
    x: <?php echo e($var['x']); ?>,
    y: <?php echo e($var['y']); ?>,
    fontSize: <?php echo e($var['font_size']); ?>,
    placed: <?php echo e($var['x'] > 0 || $var['y'] > 0 ? 'true' : 'false'); ?>

};
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

document.addEventListener('DOMContentLoaded', function() {
    const pdfContainer = document.getElementById('pdfContainer');
    const fieldsContainer = document.getElementById('fieldsContainer');
    const fieldPalette = document.getElementById('fieldPalette');
    
    // Render existing fields
    renderFields();
    
    // Add click handlers for field buttons (new click-based selection)
    document.querySelectorAll('.field-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const field = this.dataset.field;
            selectedField = field;
            document.getElementById('selectedFieldControls').style.display = 'block';
            document.getElementById('selectedFieldName').value = this.dataset.label;
            document.getElementById('inputX').value = this.dataset.x;
            document.getElementById('inputY').value = this.dataset.y;
            document.getElementById('inputFontSize').value = this.dataset.fontSize;
            document.querySelectorAll('.field-btn').forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-outline-light'); });
            this.classList.remove('btn-outline-light');
            this.classList.add('btn-primary');
        });
    });
    
    // Add click handler for PDF container to place selected field
    pdfContainer.addEventListener('click', function(e) {
        if (!selectedField) return;
        const rect = pdfContainer.getBoundingClientRect();
        const x = Math.round(e.clientX - rect.left);
        const y = Math.round(PDF_HEIGHT - (e.clientY - rect.top));
        document.getElementById('inputX').value = Math.max(0, Math.min(x, PDF_WIDTH - 50));
        document.getElementById('inputY').value = Math.max(0, Math.min(y, PDF_HEIGHT - 20));
        fieldPositions[selectedField].x = parseInt(document.getElementById('inputX').value);
        fieldPositions[selectedField].y = parseInt(document.getElementById('inputY').value);
        fieldPositions[selectedField].placed = true;
        renderFields();
    });
    
    // Make palette items draggable
    document.querySelectorAll('.draggable-field').forEach(item => {
        item.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', JSON.stringify({
                field: this.dataset.field,
                label: this.dataset.label
            }));
        });
    });
    
    // Allow dropping on container
    pdfContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    
    pdfContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        const data = JSON.parse(e.dataTransfer.getData('text/plain'));
        
        // Calculate position relative to PDF container
        const rect = pdfContainer.getBoundingClientRect();
        const x = Math.round(e.clientX - rect.left);
        const y = Math.round(PDF_HEIGHT - (e.clientY - rect.top)); // Convert to PDF coordinates (bottom-left origin)
        
        // Update position
        if (fieldPositions[data.field]) {
            fieldPositions[data.field].x = Math.max(0, Math.min(x, PDF_WIDTH - 50));
            fieldPositions[data.field].y = Math.max(0, Math.min(y, PDF_HEIGHT - 20));
            fieldPositions[data.field].placed = true;
        }
        
        renderFields();
    });
    
    // Make positioned fields draggable
    function makeFieldDraggable(element, fieldKey) {
        let isDragging = false;
        let startX, startY;
        
        element.addEventListener('mousedown', function(e) {
            if (e.target.classList.contains('delete-btn')) return;
            
            isDragging = true;
            startX = e.clientX;
            startY = e.clientY;
            element.style.cursor = 'grabbing';
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const dx = e.clientX - startX;
            const dy = -(e.clientY - startY); // Negative because Y is inverted in PDF coordinates
            
            const rect = pdfContainer.getBoundingClientRect();
            
            fieldPositions[fieldKey].x = Math.max(0, Math.min(Math.round(fieldPositions[fieldKey].x + dx), PDF_WIDTH - 50));
            fieldPositions[fieldKey].y = Math.max(0, Math.min(Math.round(fieldPositions[fieldKey].y + dy), PDF_HEIGHT - 20));
            
            startX = e.clientX;
            startY = e.clientY;
            
            renderFields();
        });
        
        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                element.style.cursor = 'move';
            }
        });
    }
    
    function renderFields() {
        fieldsContainer.innerHTML = '';
        
        Object.keys(fieldPositions).forEach(fieldKey => {
            const field = fieldPositions[fieldKey];
            if (field.placed) {
                const el = document.createElement('div');
                el.className = 'placed-field';
                el.style.cssText = `
                    position: absolute;
                    left: ${field.x}px;
                    bottom: ${field.y}px;
                    background: rgba(0, 123, 255, 0.9);
                    color: white;
                    padding: 6px 10px;
                    border-radius: 4px;
                    font-size: ${field.fontSize}px;
                    cursor: move;
                    pointer-events: auto;
                    white-space: nowrap;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                    display: flex;
                    align-items: center;
                    gap: 8px;
                `;
                el.innerHTML = `
                    <span style="font-weight: bold;">${field.value}</span>
                    <span class="delete-btn" style="cursor: pointer; color: #ff6b6b; font-size: 14px;" onclick="removeField('${fieldKey}')">×</span>
                    <input type="number" class="font-size-input" style="width: 50px; padding: 2px; font-size: 11px; border: none; border-radius: 3px;" 
                           value="${field.fontSize}" min="6" max="24" 
                           onclick="event.stopPropagation()" 
                           onchange="updateFontSize('${fieldKey}', this.value)" title="Font size">
                `;
                
                makeFieldDraggable(el, fieldKey);
                fieldsContainer.appendChild(el);
            }
        });
    }
    
    // Expose functions to global scope
    window.removeField = function(fieldKey) {
        fieldPositions[fieldKey].x = 0;
        fieldPositions[fieldKey].y = 0;
        fieldPositions[fieldKey].placed = false;
        renderFields();
    };
    
    window.adjustFontSize = function(fieldKey) {
        const newSize = prompt('Enter font size (8-24):', fieldPositions[fieldKey].fontSize);
        if (newSize && newSize >= 8 && newSize <= 24) {
            fieldPositions[fieldKey].fontSize = parseInt(newSize);
            renderFields();
        }
    };
    
    window.updateFontSize = function(fieldKey, size) {
        const newSize = parseInt(size);
        if (newSize && newSize >= 6 && newSize <= 24) {
            fieldPositions[fieldKey].fontSize = newSize;
            renderFields();
        }
    };
});

function saveLayout() {
    // Update hidden input with current positions
    document.getElementById('variablesJson').value = JSON.stringify(fieldPositions);
    
    // Submit the form
    document.getElementById('layoutForm').submit();
}

// Place field from controls
function placeField() {
    if (!selectedField) {
        alert('Please select a field first');
        return;
    }
    
    const x = parseInt(document.getElementById('inputX').value) || 0;
    const y = parseInt(document.getElementById('inputY').value) || 0;
    const fontSize = parseInt(document.getElementById('inputFontSize').value) || 12;
    
    fieldPositions[selectedField].x = x;
    fieldPositions[selectedField].y = y;
    fieldPositions[selectedField].fontSize = fontSize;
    fieldPositions[selectedField].placed = true;
    
    renderFields();
}

// Clear selection
function clearSelection() {
    selectedField = null;
    document.getElementById('selectedFieldControls').style.display = 'none';
    document.querySelectorAll('.field-btn').forEach(b => {
        b.classList.remove('btn-primary');
        b.classList.add('btn-outline-light');
    });
}

// Update position from input fields
function updatePositionFromInput() {
    if (!selectedField) return;
    const x = parseInt(document.getElementById('inputX').value) || 0;
    const y = parseInt(document.getElementById('inputY').value) || 0;
    const fontSize = parseInt(document.getElementById('inputFontSize').value) || 12;
    fieldPositions[selectedField].x = x;
    fieldPositions[selectedField].y = y;
    fieldPositions[selectedField].fontSize = fontSize;
    fieldPositions[selectedField].placed = true;
    renderFields();
}

// Keyboard arrow key support for moving selected field
document.addEventListener('keydown', function(e) {
    if (!selectedField) return;
    if (e.target.tagName === 'INPUT') return;
    
    const step = e.shiftKey ? 10 : 1;
    
    switch(e.key) {
        case 'ArrowUp':
            e.preventDefault();
            fieldPositions[selectedField].y = Math.min(PDF_HEIGHT - 20, fieldPositions[selectedField].y + step);
            document.getElementById('inputY').value = fieldPositions[selectedField].y;
            renderFields();
            break;
        case 'ArrowDown':
            e.preventDefault();
            fieldPositions[selectedField].y = Math.max(0, fieldPositions[selectedField].y - step);
            document.getElementById('inputY').value = fieldPositions[selectedField].y;
            renderFields();
            break;
        case 'ArrowRight':
            e.preventDefault();
            fieldPositions[selectedField].x = Math.min(PDF_WIDTH - 50, fieldPositions[selectedField].x + step);
            document.getElementById('inputX').value = fieldPositions[selectedField].x;
            renderFields();
            break;
        case 'ArrowLeft':
            e.preventDefault();
            fieldPositions[selectedField].x = Math.max(0, fieldPositions[selectedField].x - step);
            document.getElementById('inputX').value = fieldPositions[selectedField].x;
            renderFields();
            break;
        case 'Delete':
        case 'Backspace':
            e.preventDefault();
            removeField(selectedField);
            clearSelection();
            break;
    }
});
</script>

<style>
.placed-field:hover {
    background: rgba(0, 123, 255, 1) !important;
    transform: scale(1.05);
    transition: transform 0.1s;
}

.draggable-field:active {
    cursor: grabbing !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/school-admin/bill-layouts/designer.blade.php ENDPATH**/ ?>