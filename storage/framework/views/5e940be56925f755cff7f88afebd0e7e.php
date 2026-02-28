

<?php $__env->startSection('title', 'Admission Billing'); ?>

<?php $__env->startSection('page-title', 'Admission Billing'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(url('/dashboard')); ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('students.admission')); ?>">Admission</a></li>
    <li class="breadcrumb-item active">Billing</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- PDF-lib Library -->
<script src="https://unpkg.com/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

<?php
use App\Models\BillLayout;
$schoolId = auth()->user()->school_id;
$defaultLayouts = BillLayout::getDefaultAdmissionLayout();

try {
    $billLayouts = BillLayout::getLayouts($schoolId, 'admission');
    
    // Merge defaults with saved layouts
    $layoutData = [];
    foreach ($defaultLayouts as $key => $default) {
        $layoutData[$key] = [
            'x' => isset($billLayouts[$key]) ? $billLayouts[$key]->x_position : $default['x'],
            'y' => isset($billLayouts[$key]) ? $billLayouts[$key]->y_position : $default['y'],
            'font_size' => isset($billLayouts[$key]) ? $billLayouts[$key]->font_size : $default['font_size']
        ];
    }
} catch (\Exception $e) {
    // If table doesn't exist, use defaults
    $layoutData = $defaultLayouts;
}
?>

<div class="card">
    <div class="card-header">
        <h5>Admission Billing - <?php echo e($student->name); ?> (<?php echo e($student->student_id); ?>)</h5>
    </div>
    <div class="card-body">
        <form id="bill-form" action="<?php echo e(route('students.billing.process', $student->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            
            <div class="row">
                <!-- LEFT SIDE -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Receipt No:</label>
                        <input type="text" class="form-control" name="receipt_no" id="receiptNo" value="<?php echo e($receiptNo); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Student Name:</label>
                        <input type="text" class="form-control" id="studentName" value="<?php echo e($student->name); ?>" readonly>
                        <input type="hidden" id="studentSchoolId" value="<?php echo e($student->student_id); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Class:</label>
                        <input type="text" class="form-control" id="studentClass" value="<?php echo e($student->schoolClass ? $student->schoolClass->class_name : '-'); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Less Advance:</label>
                        <input type="text" class="form-control" id="lessAdvance" value="<?php echo e($advance); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Old Due:</label>
                        <input type="text" class="form-control" id="oldDueVal" value="<?php echo e($oldDue); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Due:</label>
                        <input type="text" class="form-control" id="newDue" name="new_due" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Due Paid:</label>
                        <input type="text" class="form-control" id="duePaid" name="due_paid" readonly>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Discount (₹):</label>
                        <input type="number" class="form-control" id="discount" name="discount" value="0" min="0" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fee Type:</label>
                        <input type="text" class="form-control" id="feeType" value="Admission" readonly>
                        <input type="hidden" id="feeValue" value="<?php echo e($admissionFee ? $admissionFee->amount : 0); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date:</label>
                        <input type="date" class="form-control" name="billing_date" id="billingDate" value="<?php echo e(date('Y-m-d')); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Advance:</label>
                        <input type="text" class="form-control" id="advance" name="advance" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Amount:</label>
                        <input type="text" class="form-control" id="totalAmount" name="total_amount" readonly>
                    </div>

                    <!-- Amount Paid - Radio Options -->
                    <div class="mb-3">
                        <label class="form-label">Amount Paid:</label><br>
                        <input type="radio" name="paymentOption" value="total" checked> Total
                        <input type="radio" name="paymentOption" value="custom" class="ms-3"> Custom
                        <input type="number" class="form-control mt-2" name="amount_paid" id="amountPaid" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Mode</label>
                        <select name="payment_mode" id="paymentMode" required class="form-control">
                            <option value="">Select</option>
                            <option value="cash">Cash</option>
                            <option value="online">Online</option>
                        </select>
                    </div>

<!-- Hidden fields for PDF data -->
                    <input type="hidden" id="admissionYear" value="<?php echo e($academicYear ? $academicYear->year : date('Y')); ?>">
                    
                    <!-- Hidden field for layout coordinates -->
                    <input type="hidden" id="billLayoutData" value="<?php echo e(json_encode($layoutData)); ?>">

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary mt-3" id="submitBtn">Submit & Generate PDF</button>
                        <a href="<?php echo e(route('students.admission')); ?>" class="btn btn-secondary mt-3">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get elements
    var feeValue = parseFloat(document.getElementById('feeValue').value) || 0;
    var oldDueVal = parseFloat(document.getElementById('oldDueVal').value) || 0;
    var lessAdvance = parseFloat(document.getElementById('lessAdvance').value) || 0;
    var discountInput = document.getElementById('discount');
    var totalAmountInput = document.getElementById('totalAmount');
    var amountPaidInput = document.getElementById('amountPaid');
    var newDueInput = document.getElementById('newDue');
    var duePaidInput = document.getElementById('duePaid');
    var advanceInput = document.getElementById('advance');
    var paymentOptions = document.getElementsByName('paymentOption');
    
    // Calculate total
    function calculateTotal() {
        var discount = parseFloat(discountInput.value) || 0;
        var total = oldDueVal + feeValue - discount - lessAdvance;
        if (total < 0) total = 0;
        totalAmountInput.value = total.toFixed(2);
        return total;
    }
    
    // Calculate due/advance
    function calculateDueAdvance() {
        var total = parseFloat(totalAmountInput.value) || 0;
        var amountPaid = parseFloat(amountPaidInput.value) || 0;
        
        var duePaid = 0;
        var newDue = 0;
        var advance = 0;
        var oldDue = oldDueVal;
        
        if (amountPaid > 0) {
            if (amountPaid > total) {
                advance = amountPaid - total;
                newDue = 0;
            } else {
                newDue = total - amountPaid;
            }
            
            if (amountPaid >= oldDueVal) {
                duePaid = oldDueVal;
                oldDue = 0;
            } else {
                duePaid = amountPaid;
                oldDue = oldDueVal - duePaid;
            }
        }
        
        duePaidInput.value = duePaid.toFixed(2);
        document.getElementById('oldDueVal').value = oldDue.toFixed(2);
        newDueInput.value = newDue.toFixed(2);
        advanceInput.value = advance.toFixed(2);
    }
    
    // Initialize
    var initialTotal = calculateTotal();
    amountPaidInput.value = initialTotal.toFixed(2);
    calculateDueAdvance();
    
    // Discount change
    discountInput.addEventListener('input', function() {
        calculateTotal();
        calculateDueAdvance();
    });
    
    // Amount paid change
    amountPaidInput.addEventListener('input', function() {
        calculateDueAdvance();
    });
    
    // Payment option change
    for (var i = 0; i < paymentOptions.length; i++) {
        paymentOptions[i].addEventListener('change', function() {
            if (this.value === 'total') {
                amountPaidInput.value = totalAmountInput.value;
            } else {
                amountPaidInput.value = '';
                amountPaidInput.focus();
            }
            calculateDueAdvance();
        });
    }
    
    // Load custom font
    async function loadCustomFont() {
        try {
            const fontUrl = '/fonts/Poppins-Bold.ttf';
            const response = await fetch(fontUrl);
            if (!response.ok) {
                console.log('Custom font not found, using default');
                return null;
            }
            const fontBytes = await response.arrayBuffer();
            return await PDFLib.Font.load(fontBytes);
        } catch (error) {
            console.log('Could not load custom font:', error);
            return null;
        }
    }
    
    // PDF Generation Function
    async function generateAdmissionPDF() {
        try {
            // Load PDF template from public folder
            const templateUrl = '/pdf-templates/admission_registration.pdf';
            
            // Fetch the template
            const response = await fetch(templateUrl);
            if (!response.ok) {
                console.log('Template not found, creating new PDF');
                return null;
            }
            
            const templateBytes = await response.arrayBuffer();
            
            // Load PDF with PDF-lib
            const pdfDoc = await PDFLib.PDFDocument.load(templateBytes);
            const pages = pdfDoc.getPages();
            const firstPage = pages[0];
            
            // Load custom font
            const customFont = await loadCustomFont();
            
            // Get form fields
            const form = pdfDoc.getForm();
            
            // Get all data for PDF
            const receiptNo = document.getElementById('receiptNo').value;
            const studentName = document.getElementById('studentName').value;
            const studentSchoolId = document.getElementById('studentSchoolId').value;
            const studentClass = document.getElementById('studentClass').value;
            const feeAmount = parseFloat(document.getElementById('feeValue').value || 0).toFixed(2);
            const discount = parseFloat(document.getElementById('discount').value || 0).toFixed(2);
            const advance = parseFloat(document.getElementById('advance').value || 0).toFixed(2);
            const oldDue = parseFloat(document.getElementById('oldDueVal').value || 0).toFixed(2);
            const newDue = parseFloat(document.getElementById('newDue').value || 0).toFixed(2);
            const paymentMode = document.getElementById('paymentMode').value.toUpperCase();
            const totalAmount = parseFloat(document.getElementById('totalAmount').value || 0).toFixed(2);
            const amountPaid = parseFloat(document.getElementById('amountPaid').value || 0).toFixed(2);
            const billingDate = document.getElementById('billingDate').value;
            const feeType = document.getElementById('feeType').value;
            const admissionYear = document.getElementById('admissionYear').value;
            
            // Text drawing options with custom font
            const textOptions = customFont ? { font: customFont, size: 12 } : { size: 12 };
            const headerOptions = customFont ? { font: customFont, size: 20 } : { size: 20 };
            
            // Try to fill form fields if they exist
            try {
                const fields = form.getFields();
                fields.forEach(field => {
                    const fieldName = field.getName().toLowerCase();
                    if (fieldName.includes('receipt')) field.setText(receiptNo);
                    else if (fieldName.includes('student') && fieldName.includes('id')) field.setText(studentSchoolId);
                    else if (fieldName.includes('student') && fieldName.includes('name')) field.setText(studentName);
                    else if (fieldName.includes('class')) field.setText(studentClass);
                    else if (fieldName.includes('fee') && fieldName.includes('amount')) field.setText(feeAmount);
                    else if (fieldName.includes('discount')) field.setText(discount);
                    else if (fieldName.includes('advance')) field.setText(advance);
                    else if (fieldName.includes('old') && fieldName.includes('due')) field.setText(oldDue);
                    else if (fieldName.includes('new') && fieldName.includes('due')) field.setText(newDue);
                    else if (fieldName.includes('payment') && fieldName.includes('mode')) field.setText(paymentMode);
                    else if (fieldName.includes('total') && fieldName.includes('amount')) field.setText(totalAmount);
                    else if (fieldName.includes('amount') && fieldName.includes('paid')) field.setText(amountPaid);
                    else if (fieldName.includes('date')) field.setText(billingDate);
                    else if (fieldName.includes('fee') && fieldName.includes('type')) field.setText(feeType);
                    else if (fieldName.includes('year')) field.setText(admissionYear);
                });
            } catch (formError) {
                console.log('Form fields not found, using text placement');
            }
            
// Draw text at coordinates as fallback with custom font
            try {
                // Get layout data from hidden field
                var layoutData = JSON.parse(document.getElementById('billLayoutData').value || '{}');
                
                // Helper function to get coordinate with fallback
                function getCoord(key, defaultX, defaultY) {
                    return {
                        x: layoutData[key] ? layoutData[key].x : defaultX,
                        y: layoutData[key] ? layoutData[key].y : defaultY,
                        fontSize: layoutData[key] ? layoutData[key].font_size : 12
                    };
                }
                
                // Draw each field at its configured position
                var coord;
                
                coord = getCoord('receipt_no', 400, 700);
                firstPage.drawText(receiptNo, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('student_id', 150, 650);
                firstPage.drawText(studentSchoolId, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('student_name', 150, 620);
                firstPage.drawText(studentName, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('class', 150, 590);
                firstPage.drawText(studentClass, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('fee_amount', 150, 560);
                firstPage.drawText(feeAmount, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('discount', 300, 560);
                firstPage.drawText(discount, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('advance', 450, 560);
                firstPage.drawText(advance, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('old_due', 150, 530);
                firstPage.drawText(oldDue, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('new_due', 300, 530);
                firstPage.drawText(newDue, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('payment_mode', 450, 530);
                firstPage.drawText(paymentMode, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('total_amount', 150, 500);
                firstPage.drawText(totalAmount, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('amount_paid', 300, 500);
                firstPage.drawText(amountPaid, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('billing_date', 450, 500);
                firstPage.drawText(billingDate, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('fee_type', 150, 470);
                firstPage.drawText(feeType, { x: coord.x, y: coord.y, size: coord.fontSize });
                
                coord = getCoord('academic_year', 300, 470);
                firstPage.drawText(admissionYear, { x: coord.x, y: coord.y, size: coord.fontSize });
            } catch (drawError) {
                console.log('Could not draw text at coordinates:', drawError);
            }
            
            // Generate PDF as bytes
            const pdfBytes = await pdfDoc.save();
            return pdfBytes;
            
        } catch (error) {
            console.error('Error generating PDF:', error);
            return null;
        }
    }
    
    // Create PDF with blank template (fallback)
    async function createBlankAdmissionPDF() {
        try {
            const pdfDoc = await PDFLib.PDFDocument.create();
            const page = pdfDoc.addPage([595, 842]); // A4 size
            
            // Load custom font
            const customFont = await loadCustomFont();
            
            // Get all values
            const receiptNo = document.getElementById('receiptNo').value;
            const studentName = document.getElementById('studentName').value;
            const studentSchoolId = document.getElementById('studentSchoolId').value;
            const studentClass = document.getElementById('studentClass').value;
            const feeAmount = parseFloat(document.getElementById('feeValue').value || 0).toFixed(2);
            const discount = parseFloat(document.getElementById('discount').value || 0).toFixed(2);
            const advance = parseFloat(document.getElementById('advance').value || 0).toFixed(2);
            const oldDue = parseFloat(document.getElementById('oldDueVal').value || 0).toFixed(2);
            const newDue = parseFloat(document.getElementById('newDue').value || 0).toFixed(2);
            const paymentMode = document.getElementById('paymentMode').value.toUpperCase();
            const totalAmount = parseFloat(document.getElementById('totalAmount').value || 0).toFixed(2);
            const amountPaid = parseFloat(document.getElementById('amountPaid').value || 0).toFixed(2);
            const billingDate = document.getElementById('billingDate').value;
            const feeType = document.getElementById('feeType').value;
            const admissionYear = document.getElementById('admissionYear').value;
            
            // Text options with custom font
            const textOptions = customFont ? { font: customFont, size: 12 } : { size: 12 };
            const headerOptions = customFont ? { font: customFont, size: 20 } : { size: 20 };
            const subHeaderOptions = customFont ? { font: customFont, size: 14 } : { size: 14 };
            
            // Draw header with custom font
            page.drawText('ADMISSION FEE RECEIPT', { x: 180, y: 800, ...headerOptions });
            page.drawText('========================================', { x: 150, y: 780, ...subHeaderOptions });
            
            // Draw fields at X/Y coordinates
            let yPos = 740;
            
            page.drawText('Receipt No:', { x: 50, y: yPos, ...textOptions });
            page.drawText(receiptNo, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Student ID:', { x: 50, y: yPos, ...textOptions });
            page.drawText(studentSchoolId, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Student Name:', { x: 50, y: yPos, ...textOptions });
            page.drawText(studentName, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Class:', { x: 50, y: yPos, ...textOptions });
            page.drawText(studentClass, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Fee Type:', { x: 50, y: yPos, ...textOptions });
            page.drawText(feeType, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Academic Year:', { x: 50, y: yPos, ...textOptions });
            page.drawText(admissionYear, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Date:', { x: 50, y: yPos, ...textOptions });
            page.drawText(billingDate, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 50;
            page.drawText('----------------------------------------', { x: 50, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Fee Amount:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + feeAmount, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Discount:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + discount, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Less Advance:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + advance, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Old Due:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + oldDue, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('New Due:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + newDue, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 50;
            page.drawText('----------------------------------------', { x: 50, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Total Amount:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + totalAmount, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Amount Paid:', { x: 50, y: yPos, ...textOptions });
            page.drawText('₹' + amountPaid, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 30;
            page.drawText('Payment Mode:', { x: 50, y: yPos, ...textOptions });
            page.drawText(paymentMode, { x: 200, y: yPos, ...textOptions });
            
            yPos -= 50;
            page.drawText('========================================', { x: 150, y: yPos, ...subHeaderOptions });
            page.drawText('Thank you for your payment!', { x: 180, y: yPos - 30, ...textOptions });
            
            const pdfBytes = await pdfDoc.save();
            return pdfBytes;
            
        } catch (error) {
            console.error('Error creating blank PDF:', error);
            return null;
        }
    }
    
    // Open PDF in new tab
    async function openPDFInNewTab(pdfBytes) {
        if (!pdfBytes) return false;
        
        // Create blob
        const blob = new Blob([pdfBytes], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        
        // Open in new tab
        window.open(url, '_blank');
        
        return true;
    }
    
    // Form submission handler
    document.getElementById('bill-form').addEventListener('submit', async function(e) {
        var amountPaid = parseFloat(amountPaidInput.value) || 0;
        if (amountPaid <= 0) {
            e.preventDefault();
            alert('Please enter a valid Amount Paid.');
            amountPaidInput.focus();
            return false;
        }
        
        // Get payment mode
        var paymentMode = document.getElementById('paymentMode').value;
        if (!paymentMode) {
            e.preventDefault();
            alert('Please select Payment Mode.');
            document.getElementById('paymentMode').focus();
            return false;
        }
        
        // Prevent default temporarily
        e.preventDefault();
        
        // Generate PDF first
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = 'Generating PDF...';
        
        try {
            // Try to load template, if fails create blank
            let pdfBytes = await generateAdmissionPDF();
            
            if (!pdfBytes) {
                pdfBytes = await createBlankAdmissionPDF();
            }
            
            // Open PDF in new tab
            if (pdfBytes) {
                await openPDFInNewTab(pdfBytes);
            }
            
            // Now submit the form to save data
            this.submit();
            
        } catch (error) {
            console.error('Error:', error);
            alert('Error generating PDF. Form will be submitted anyway.');
            this.submit();
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/admission-billing.blade.php ENDPATH**/ ?>