# Task: Age-based Class Selection in Admission Form

## Steps:
- [x] 1. Add AJAX endpoint in StudentController to get eligible classes based on age
- [x] 2. Add route for the AJAX endpoint in web.php
- [x] 3. Update admission.blade.php with JavaScript for real-time class filtering

## Implementation Details:
1. **StudentController.php**: Added `getEligibleClasses` method that accepts DOB, calculates age, and returns classes where minimum_age <= student_age
2. **routes/web.php**: Added route for `/students/eligible-classes` endpoint
3. **admission.blade.php**: Added JavaScript to listen to DOB change and make AJAX call to filter classes

## How it works:
1. When user enters Date of Birth in the admission form, JavaScript detects the change
2. It calculates the student's age and sends an AJAX request to the server
3. The server returns eligible classes where student's age >= minimum_age
4. The class dropdown is dynamically updated to show only eligible classes
5. A hint text shows the student's calculated age and number of eligible classes

---

# Additional Changes: Billing Pages Updates

## Changes Made:

### 1. admission-billing.blade.php
- Added Medium field (using ucfirst($student->medium))
- Fixed Total/Custom radio buttons:
  - When "Total" is selected: amount_paid is readonly and shows total amount
  - When "Custom" is selected: amount_paid becomes editable for custom amount
- Added proper form-check styling for radio buttons

### 2. registration-billing.blade.php
- Already had Medium field - verified working
- Fixed Total/Custom radio buttons functionality to match admission billing

### 3. admission-receipt-print.blade.php
- Made "For the" field dynamic - now shows "Admission" or "Registration" based on $receipt->bill_type
- Added ucfirst() to Medium field for proper capitalization
