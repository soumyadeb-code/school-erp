<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'name',
        'dob',
        'class_id',
        'section',
        'roll',
        'medium',
        'admission_date',
        'gender',
        'aadhaar',
        'government_id',
        'blood_group',
        'social_category',
        'religion',
        'father_name',
        'father_education',
        'mother_name',
        'mother_education',
        'yearly_income',
        'phone',
        'whatsapp',
        'email',
        'address',
        'village',
        'post_office',
        'police_station',
        'district',
        'pin',
        'panchayat',
        'icds_name',
        'icds_center_no',
        'icds_code',
        'bus_destination_id',
        'photo',
        'status',
        'admission_status',
        'registration_status',
    ];

    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'date',
        'yearly_income' => 'decimal:2',
    ];

    /**
     * Get the school that owns this student.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the class.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Get the bus destination.
     */
    public function busDestination(): BelongsTo
    {
        return $this->belongsTo(BusFee::class, 'bus_destination_id');
    }

    /**
     * Get all receipts for this student.
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * Get all monthly payments for this student.
     */
    public function monthlyPayments(): HasMany
    {
        return $this->hasMany(MonthlyPayment::class);
    }

    /**
     * Get academic history for this student.
     */
    public function academicHistory(): HasMany
    {
        return $this->hasMany(StudentAcademicHistory::class);
    }

    /**
     * Get the student's current due.
     */
    public function currentDue(): HasOne
    {
        return $this->hasOne(StudentDue::class)->latestOfMany();
    }

    /**
     * Get the student's current advance.
     */
    public function currentAdvance(): HasOne
    {
        return $this->hasOne(StudentAdvance::class)->latestOfMany();
    }

    /**
     * Calculate age from DOB.
     */
    public function getAge(): int
    {
        return $this->dob->age;
    }

    /**
     * Generate student ID.
     */
    public static function generateStudentId($schoolCode, $year, $formNo): string
    {
        $yearPrefix = substr($year, -2);
        return $yearPrefix . strtoupper($schoolCode) . str_pad($formNo, 3, '0', STR_PAD_LEFT);
    }
}
