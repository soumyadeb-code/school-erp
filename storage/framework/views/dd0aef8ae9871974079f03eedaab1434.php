<!DOCTYPE html>
<html>
<head>
    <title>Receipt Print</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .page {
            width: 100%;
            display: flex;
            justify-content: space-between;
        }

        .receipt {
            width: 48%;
            border: 2px solid black;
            padding: 15px;
            box-sizing: border-box;
            position: relative;
        }

        /* Receipt No. – moved to top-left */
        .receipt-no {
            position: absolute;
            top: 10px;
            left: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        .title {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 25px;
        }

        .title h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .small {
            font-size: 13px;
            text-align: center;
        }

        .section {
            margin-top: 12px;
            font-size: 14px;
        }

         .label {
            
            width: 60px; /* ensures equal alignment */
        }

        .dots-line {
margin-left: 15px;
            border-bottom: 1px dotted black;
            
            min-width: 200px;
        }

        .table-box {
            width: 100%;
            border: 2px solid black;
            margin-top: 15px;
            padding: 0;
            box-sizing: border-box;
        }

        .table-header {
            display: flex;
            border-bottom: 2px solid black;
            font-weight: bold;
        }

        .table-header div {
            padding: 5px;
            font-size: 14px;
        }

        .left {
            width: 70%;
            border-right: 2px solid black;
        }
        .right {
            width: 30%;
        }

        .table-row {
            display: flex;
            border-bottom: 1px solid black;
        }

        .table-row div {
            padding: 5px;
        }

        .bottom-section {
            margin-top: 20px;
            font-size: 14px;
        }

.footer-row {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    width: 100%;
}

.footer-left {
    font-size: 14px;
}

.dotted-line {
    display: inline-block;
    border-bottom: 1px dotted black;
    width: 150px;   /* adjust as needed */
}

.footer-right {
    text-align: center;
    width: 200px; /* controls how wide the signature area is */
}

.signature-line {
margin-top: 30px;
    display: block;
    width: 100%;
    border-top: 1px solid black;
    height: 12px;
}

.received-text {
    
    font-size: 14px;
}
        /* Center dotted cut-line */
        .center-line {
            width: 2%;
            text-align: center;
            font-size: 22px;
            padding-top: 100px;
            color: black;
            font-weight: bold;
        }


        /* Center dotted cut-line */
        .center-line {
            width: 2%;
            text-align: center;
            font-size: 22px;
            padding-top: 100px;
            color: black;
            font-weight: bold;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="page">

    <!-- ORIGINAL COPY -->
    <div class="receipt">
        <div class="receipt-no">Receipt No.: <span class="dots-line" style="min-width:60px;"><?php echo e($receipt->receipt_no); ?></span></div>

        <div style="float:right; font-size:14px;">Original</div>

        <div class="title">
            <h2><?php echo e($school->name); ?></h2>
            <div class="small">
                Org. by <u><?php echo e($school->trust_name ?? 'Trust Name'); ?></u><br>
                <?php echo e($school->address ?? 'Address'); ?>

            </div>
        </div>

        <!-- FIELDS -->
        <div class="section">
            <div style="display:flex; align-items:center; width:100%;">
    
    <span style="width:80px; font-weight:600;">Name:</span>
    
    <span style="
        flex:1;
        border-bottom:1px dotted black;
        text-align:center;
        padding-bottom:2px;
        display:block;
    ">
        <span style="font-size:14px;"><?php echo e($receipt->student->name); ?></span>
    </span>

</div>
<br>
            <span class="label" style="font-weight:600;">Class:</span> 
            <span class="dots-line" style="min-width:80px;"><?php echo e($receipt->student->schoolClass->class_name ?? '-'); ?></span>

            
<span class="label" style="margin-left:15px; font-weight:600;">Std Id.:</span> 
            <span class="dots-line" style="min-width:50px;"><?php echo e($receipt->student->student_id); ?></span>

<span class="label" style="margin-left:20px; font-weight:600;">Medium:</span> 
            <span class="dots-line" style="min-width:10px;"><?php echo e(ucfirst($receipt->student->medium)); ?></span><br>
<br>

<span class="label" style=" font-weight:600;">For the:</span> 
            <span class="dots-line" style="min-width:10px;"><?php echo e(ucfirst($receipt->bill_type)); ?></span>



        </div>

        <!-- TABLE -->
        <div class="table-box">
            <div class="table-header">
                <div class="left">Mode :</div>
                <div class="right">Rs.</div>
            </div>

            <div class="table-row">
                <div class="left">Donate to school fund</div>
                <div class="right"><?php echo e(number_format($receipt->total_amount, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Less</div>
                <div class="right"><?php echo e(number_format($receipt->discount, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Less Advance</div>
                <div class="right"><?php echo e(number_format($receipt->less_advance, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Due Paid</div>
                <div class="right"><?php echo e(number_format($receipt->old_due_paid, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left" style="font-weight:bold;">TOTAL</div>
                <div class="right" style="font-weight:bold;"><?php echo e(number_format($receipt->total_amount + $receipt->old_due_paid - $receipt->discount - $receipt->less_advance, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Advance</div>
                <div class="right"><?php echo e(number_format($receipt->advance_amount, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Due</div>
                <div class="right"><?php echo e(number_format($receipt->due_amount, 0)); ?></div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer-row">
    
    <!-- LEFT SIDE: Paid + Date -->
    <div class="footer-left">
        <div>
            Paid <span class="dotted-line"><?php echo e(number_format($receipt->paid_amount, 0)); ?></span>
        </div>
        <div style="margin-top: 10px;">
            Date <span class="dotted-line"><?php echo e(\Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y')); ?></span>
        </div>
    </div>

    <!-- RIGHT SIDE: Signature line + Received by -->
    <div class="footer-right">
        <span class="signature-line"></span>
        <div class="received-text">Received by</div>
    </div>

    </div>


    </div>


    <!-- CENTER CUT LINE -->
    <div class="center-line">⋮<br>⋮<br>⋮<br>⋮</div>


    <!-- DUPLICATE COPY -->
    <div class="receipt">
        <div class="receipt-no">Receipt No.: <span class="dots-line" style="min-width:60px;"><?php echo e($receipt->receipt_no); ?></span></div>

        <div style="float:right; font-size:14px;">Duplicate</div>

        <div class="title">
            <h2><?php echo e($school->name); ?></h2>
            <div class="small">
                Org. by <u><?php echo e($school->trust_name ?? 'Trust Name'); ?></u><br>
                <?php echo e($school->address ?? 'Address'); ?>

            </div>
        </div>

        <!-- Same section as above -->
              <div class="section">
            <div style="display:flex; align-items:center; width:100%;">
    
    <span style="width:80px; font-weight:600;">Name:</span>
    
    <span style="
        flex:1;
        border-bottom:1px dotted black;
        text-align:center;
        padding-bottom:2px;
        display:block;
    ">
        <span style="font-size:14px;"><?php echo e($receipt->student->name); ?></span>
    </span>

</div>
<br>
            <span class="label" style="font-weight:600;">Class:</span> 
            <span class="dots-line" style="min-width:80px;"><?php echo e($receipt->student->schoolClass->class_name ?? '-'); ?></span>

            
<span class="label" style="margin-left:15px; font-weight:600;">Std Id.:</span> 
            <span class="dots-line" style="min-width:50px;"><?php echo e($receipt->student->student_id); ?></span>

<span class="label" style="margin-left:20px; font-weight:600;">Medium:</span> 
            <span class="dots-line" style="min-width:10px;"><?php echo e(ucfirst($receipt->student->medium)); ?></span><br>
<br>

<span class="label" style=" font-weight:600;">For the:</span> 
            <span class="dots-line" style="min-width:10px;"><?php echo e(ucfirst($receipt->bill_type)); ?></span>
        </div>

        <!-- TABLE (duplicate) -->
        <div class="table-box">
            <div class="table-header">
                <div class="left">Mode :</div>
                <div class="right">Rs.</div>
            </div>

            <div class="table-row">
                <div class="left">Donate to school fund</div>
                <div class="right"><?php echo e(number_format($receipt->total_amount, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Less</div>
                <div class="right"><?php echo e(number_format($receipt->discount, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Less Advance</div>
                <div class="right"><?php echo e(number_format($receipt->less_advance, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Due Paid</div>
                <div class="right"><?php echo e(number_format($receipt->old_due_paid, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left" style="font-weight:bold;">TOTAL</div>
                <div class="right" style="font-weight:bold;"><?php echo e(number_format($receipt->total_amount + $receipt->old_due_paid - $receipt->discount - $receipt->less_advance, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Advance</div>
                <div class="right"><?php echo e(number_format($receipt->advance_amount, 0)); ?></div>
            </div>

            <div class="table-row">
                <div class="left">Due</div>
                <div class="right"><?php echo e(number_format($receipt->due_amount, 0)); ?></div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer-row">
    
    <!-- LEFT SIDE: Paid + Date -->
    <div class="footer-left">
        <div>
            Paid <span class="dotted-line"><?php echo e(number_format($receipt->paid_amount, 0)); ?></span>
        </div>
        <div style="margin-top: 10px;">
            Date <span class="dotted-line"><?php echo e(\Carbon\Carbon::parse($receipt->billing_date)->format('d-m-Y')); ?></span>
        </div>
    </div>

    <!-- RIGHT SIDE: Signature line + Received by -->
    <div class="footer-right">
        <span class="signature-line"></span>
        <div class="received-text">Received by</div>
    </div>



</div>

</body>
</html>
<?php /**PATH E:\AI\Laravel\Blackbox-school\resources\views/students/admission-receipt-print.blade.php ENDPATH**/ ?>