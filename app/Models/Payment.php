<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id', 'student_membership_id', 'amount', 'paid_amount', 'pending_amount', 'admission_fee',
        'security_deposit', 'payment_method', 'transaction_id', 'payment_date',
        'receipt_number', 'note', 'status'
    ];

    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function membership(): BelongsTo { return $this->belongsTo(StudentMembership::class, 'student_membership_id'); }
}
