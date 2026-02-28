

<?php $__env->startSection('title', 'Bill History - School ERP'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bill History</h4>
                </div>
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Total Bills</h5>
                                    <h3 id="totalReceipts"><?php echo e($totalReceipts); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>Total Amount</h5>
                                    <h3 id="totalAmount">₹<?php echo e(number_format($totalAmount, 2)); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Total Paid</h5>
                                    <h3 id="totalPaid">₹<?php echo e(number_format($totalPaid, 2)); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Total Due</h5>
                                    <h3 id="totalDue">₹<?php echo e(number_format($totalDue, 2)); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <select id="search_type" class="form-select">
                                <option value="receipt">Receipt No</option>
                                <option value="student">Student Name</option>
                                <option value="phone">Phone</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="query" class="form-control" placeholder="Search..." value="">
                        </div>
                        <div class="col-md-2">
                            <select id="bill_type" class="form-select">
                                <option value="">All Types</option>
                                <option value="admission">Admission</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="paid">Paid</option>
                                <option value="due">Due</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="from_date" class="form-control" value="" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" id="to_date" class="form-control" value="" placeholder="To Date">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="button" id="filterBtn" class="btn btn-primary">Filter</button>
                            <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>

                    <!-- Bills Table -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Receipt No</th>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Type</th>
                                    <th>Total Amount</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="billsTableBody">
                                <?php $__empty_1 = true; $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($receipt->receipt_no); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y')); ?></td>
                                    <td>
                                        <?php if($receipt->student): ?>
                                            <?php echo e($receipt->student->name); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e($receipt->student->student_id); ?></small>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($receipt->student && $receipt->student->schoolClass): ?>
                                            <?php echo e($receipt->student->schoolClass->class_name); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($receipt->bill_type == 'admission'): ?>
                                            <span class="badge bg-primary">Admission</span>
                                        <?php elseif($receipt->bill_type == 'monthly'): ?>
                                            <span class="badge bg-info">Monthly</span>
                                        <?php else: ?>
                                            <?php echo e(ucfirst($receipt->bill_type)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>₹<?php echo e(number_format($receipt->total_amount, 2)); ?></td>
                                    <td>₹<?php echo e(number_format($receipt->paid_amount, 2)); ?></td>
                                    <td>₹<?php echo e(number_format($receipt->due_amount, 2)); ?></td>
                                    <td>
                                        <?php if($receipt->status == 'paid'): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php elseif($receipt->status == 'due'): ?>
                                            <span class="badge bg-warning">Due</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e(ucfirst($receipt->status)); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('students.receipt-view', $receipt->id)); ?>" class="btn btn-sm btn-info" title="View Receipt">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center">No bills found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center" id="paginationLinks">
                        <?php echo e($receipts->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Load data on page load via AJAX
    fetchBills(1);

    // Debounce function for search input
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // Fetch bills with filters (AJAX)
    function fetchBills(page = 1) {
        const searchType = $('#search_type').val();
        const query = $('#query').val();
        const billType = $('#bill_type').val();
        const status = $('#status').val();
        const fromDate = $('#from_date').val();
        const toDate = $('#to_date').val();

        $.ajax({
            url: '<?php echo e(route("students.bill-history.ajax")); ?>',
            type: 'GET',
            data: {
                search_type: searchType,
                query: query,
                bill_type: billType,
                status: status,
                from_date: fromDate,
                to_date: toDate,
                page: page
            },
            success: function(response) {
                // Update summary cards
                $('#totalReceipts').text(response.summary.totalReceipts);
                $('#totalAmount').text('₹' + parseFloat(response.summary.totalAmount).toFixed(2));
                $('#totalPaid').text('₹' + parseFloat(response.summary.totalPaid).toFixed(2));
                $('#totalDue').text('₹' + parseFloat(response.summary.totalDue).toFixed(2));

                // Update table body
                let html = '';
                if (response.receipts.length > 0) {
                    response.receipts.forEach(function(receipt) {
                        const studentName = receipt.student ? receipt.student.name : 'N/A';
                        const studentId = receipt.student ? receipt.student.student_id : '';
                        const className = (receipt.student && receipt.student.school_class) ? receipt.student.school_class.class_name : 'N/A';
                        
                        let billTypeBadge = '';
                        if (receipt.bill_type === 'admission') {
                            billTypeBadge = '<span class="badge bg-primary">Admission</span>';
                        } else if (receipt.bill_type === 'monthly') {
                            billTypeBadge = '<span class="badge bg-info">Monthly</span>';
                        } else {
                            billTypeBadge = '<span class="badge bg-secondary">' + receipt.bill_type.charAt(0).toUpperCase() + receipt.bill_type.slice(1) + '</span>';
                        }

                        let statusBadge = '';
                        if (receipt.status === 'paid') {
                            statusBadge = '<span class="badge bg-success">Paid</span>';
                        } else if (receipt.status === 'due') {
                            statusBadge = '<span class="badge bg-warning">Due</span>';
                        } else {
                            statusBadge = '<span class="badge bg-secondary">' + receipt.status.charAt(0).toUpperCase() + receipt.status.slice(1) + '</span>';
                        }

                        let actionBtn = '<a href="/students/receipt-view/' + receipt.id + '" class="btn btn-sm btn-info" title="View Receipt"><i class="fas fa-eye"></i></a>';

                        const billingDate = new Date(receipt.billing_date).toLocaleDateString('en-GB');

                        html += '<tr>';
                        html += '<td>' + receipt.receipt_no + '</td>';
                        html += '<td>' + billingDate + '</td>';
                        html += '<td>' + studentName + (studentId ? '<br><small class="text-muted">' + studentId + '</small>' : '') + '</td>';
                        html += '<td>' + className + '</td>';
                        html += '<td>' + billTypeBadge + '</td>';
                        html += '<td>₹' + parseFloat(receipt.total_amount).toFixed(2) + '</td>';
                        html += '<td>₹' + parseFloat(receipt.paid_amount).toFixed(2) + '</td>';
                        html += '<td>₹' + parseFloat(receipt.due_amount).toFixed(2) + '</td>';
                        html += '<td>' + statusBadge + '</td>';
                        html += '<td>' + actionBtn + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="10" class="text-center">No bills found</td></tr>';
                }
                $('#billsTableBody').html(html);

                // Update pagination
                let paginationHtml = '';
                if (response.pagination.last_page > 1) {
                    paginationHtml += '<nav><ul class="pagination">';
                    
                    // Previous button
                    if (response.pagination.current_page > 1) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (response.pagination.current_page - 1) + '">Previous</a></li>';
                    }
                    
                    // Page numbers
                    for (let i = 1; i <= response.pagination.last_page; i++) {
                        const activeClass = (i === response.pagination.current_page) ? 'active' : '';
                        paginationHtml += '<li class="page-item ' + activeClass + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
                    }
                    
                    // Next button
                    if (response.pagination.current_page < response.pagination.last_page) {
                        paginationHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (response.pagination.current_page + 1) + '">Next</a></li>';
                    }
                    
                    paginationHtml += '</ul></nav>';
                }
                $('#paginationLinks').html(paginationHtml);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching bills:', error);
                alert('Error fetching bills. Please try again.');
            }
        });
    }

    // Filter button click
    $('#filterBtn').on('click', function() {
        fetchBills(1);
    });

    // Reset button click
    $('#resetBtn').on('click', function() {
        $('#search_type').val('receipt');
        $('#query').val('');
        $('#bill_type').val('');
        $('#status').val('');
        $('#from_date').val('');
        $('#to_date').val('');
        fetchBills(1);
    });

    // Real-time search on input (debounced)
    $('#query').on('keyup', debounce(function() {
        fetchBills(1);
    }, 500));

    // Filter dropdowns change
    $('#bill_type, #status').on('change', function() {
        fetchBills(1);
    });

    // Date inputs change
    $('#from_date, #to_date').on('change', function() {
        fetchBills(1);
    });

    // Pagination click
    $(document).on('click', '#paginationLinks .page-link', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            fetchBills(page);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/bill-history.blade.php ENDPATH**/ ?>