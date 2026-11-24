<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('student_membership_id')->nullable()->constrained('student_memberships')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->decimal('admission_fee', 10, 2)->default(0);
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->string('payment_method'); // upi, cash, bank
            $table->string('transaction_id')->nullable();
            $table->date('payment_date');
            $table->string('receipt_number')->unique();
            $table->text('note')->nullable();
            $table->enum('status', ['PAID', 'PENDING', 'FAILED'])->default('PAID');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
