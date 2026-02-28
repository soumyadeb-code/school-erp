

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

// Helper function to get variable label
function getVariableLabel($variableName) {
    $labels = [
        'receipt_no' => 'Receipt No',
        'student_id' => 'Student ID',
        'student_name' => 'Student Name',
        'class' => 'Class',
        'fee_amount' => 'Fee Amount',
        'discount' => 'Discount',
        'advance' => 'Advance',
        'old_due' => 'Old Due',
        'new_due' => 'New Due',
        'payment_mode' => 'Payment Mode',
        'total_amount' => 'Total Amount',
        'amount_paid' => 'Amount Paid',
        'billing_date' => 'Billing Date',
        'fee_type' => 'Fee Type',
        'academic_year' => 'Academic Year',
    ];
    return $labels[$variableName] ?? ucfirst(str_replace('_', ' ', $variableName));
}

try {
    $billLayouts = BillLayout::getLayouts($schoolId, $billType);
    
    $variables = [];
    foreach ($defaultLayouts as $key => $default) {
        $variables[$key] = [
            'label' => getVariableLabel($key),
            'x' => isset($billLayouts[$key]) ? $billLayouts[$key]->x_position : $default['x'],
            'y' => isset($billLayouts[$key]) ? $billLayouts[$key]->y_position : $default['y'],
            'font_size' => isset($billLayouts[$key]) ? $billLayouts[$key]->font_size : $default['font_size']
        ];
    }
} catch (\Exception $e) {
    $variables = [];
    foreach ($defaultLayouts as $key => $default) {
        $variables[$key] = [
            'label' => getVariableLabel($key),
            'x' => $default['x'],
            'y' => $default['y'],
            'font_size' => $default['font_size']
        ];
    }
}

// Get saved template path
$templatePath = 'pdf-templates/' . $billType . '_template.pdf';
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
                                <li>The PDF will be displayed below</li>
                                <li>Drag and drop the field labels onto the PDF to position them</li>
                                <li>Click on any positioned field to adjust its font size</li>
                                <li>Your layout is automatically saved when you position fields</li>
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
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Template Loaded:</strong> <?php echo e($templatePath); ?>

                </div>
                <?php endif; ?>

                <!-- Designer Area -->
                <div class="designer-container" style="position: relative; overflow: auto; background: #525659; padding: 20px; min-height: 500px;">
                    
                    <!-- PDF Canvas -->
                    <div id="pdfContainer" style="position: relative; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                        <?php if(file_exists(public_path($templatePath))): ?>
                        <iframe id="pdfFrame" src="<?php echo e(asset($templatePath)); ?>#toolbar=0&navpanes=0&scrollbar=0" 
                                style="width: 595px; height: 842px; border: none;" allowtransparency="true"></iframe>
                        <?php else: ?>
                        <div style="width: 595px; height: 842px; background: white; display: flex; align-items: center; justify-content: center; flex-direction: column;">
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

                    <!-- Draggable Field Labels -->
                    <div class="mt-3 p-3 bg-dark rounded">
                        <h6 class="text-white mb-3"><i class="fas fa-grip-vertical me-2"></i>Available Fields - Drag onto PDF:</h6>
                        <div class="d-flex flex-wrap gap-2" id="fieldPalette">
                            <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="draggable-field badge bg-primary fs-6 p-2" 
                                 draggable="true" 
                                 data-field="<?php echo e($key); ?>"
                                 data-label="<?php echo e($var['label']); ?>"
                                 data-x="<?php echo e($var['x']); ?>"
                                 data-y="<?php echo e($var['y']); ?>"
                                 data-font-size="<?php echo e($var['font_size']); ?>"
                                 style="cursor: grab; pointer-events: auto;">
                                <i class="fas fa-grip-lines me-1"></i><?php echo e($var['label']); ?>

                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
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

// PDF dimensions (A4)
const PDF_WIDTH = 595;
const PDF_HEIGHT = 842;

// Initialize from saved data
<?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $var): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
fieldPositions['<?php echo e($key); ?>'] = {
    label: '<?php echo e($var["label"]); ?>',
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
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: ${field.fontSize}px;
                    cursor: move;
                    pointer-events: auto;
                    white-space: nowrap;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                `;
                el.innerHTML = `
                    ${field.label}
                    <span class="delete-btn" style="margin-left: 8px; cursor: pointer; color: #ff6b6b;" onclick="removeField('${fieldKey}')">×</span>
                    <span class="font-btn" style="margin-left: 4px; cursor: pointer; color: #ffd93d;" onclick="adjustFontSize('${fieldKey}')" title="Click to adjust font size">A</span>
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
});

function saveLayout() {
    // Update hidden input with current positions
    document.getElementById('variablesJson').value = JSON.stringify(fieldPositions);
    
    // Submit the form
    document.getElementById('layoutForm').submit();
}
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