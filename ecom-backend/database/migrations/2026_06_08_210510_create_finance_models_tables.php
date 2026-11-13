<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Physical Stores Table
        Schema::create('physical_stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('monthly_rent', 12, 2)->default(0.00);
            $table->string('status')->default('Active'); // Active, Closed
            $table->timestamps();
        });

        // 2. Employees Table (Office and Store Staff)
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('employee_type'); // Admin Office, Physical Store
            $table->foreignId('physical_store_id')->nullable()->constrained('physical_stores')->nullOnDelete();
            $table->string('role'); // Admin Staff, Store Staff, Manager, Courier, etc.
            $table->decimal('salary', 12, 2)->default(0.00);
            $table->string('status')->default('Active'); // Active, Inactive
            $table->date('hired_at')->nullable();
            $table->timestamps();
        });

        // 3. Physical Store Sales and Expenses logs
        Schema::create('physical_store_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('physical_store_id')->constrained('physical_stores')->onDelete('cascade');
            $table->string('type'); // Sale, Expense
            $table->string('category'); // Sales, Utilities, Rent, Local Purchase, Salaries, Others
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Udhar Registry / Credit / Receivables
        Schema::create('udhar_registries', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('phone')->nullable();
            $table->text('description')->nullable(); // items purchased, reasons
            $table->decimal('amount', 12, 2);
            $table->date('due_date')->nullable();
            $table->string('status')->default('Pending'); // Pending, Settled
            $table->date('settled_at')->nullable();
            $table->timestamps();
        });

        // 5. Delivery Partners (TCS, M&P, Leopards, etc.)
        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // TCS, M&P, Leopards, etc.
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 6. Delivery Shippings (COD vs Online Payment settlements)
        Schema::create('delivery_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('delivery_partner_id')->constrained('delivery_partners')->cascadeOnDelete();
            $table->string('tracking_number');
            $table->decimal('cod_amount', 12, 2)->default(0.00);
            $table->string('payment_method'); // COD, Online
            $table->string('payment_channel')->nullable(); // Cash, JazzCash, EasyPaisa, Bank Transfer, etc.
            $table->string('payment_status')->default('Pending'); // Pending (to receive from courier/bank), Received
            $table->date('received_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_shippings');
        Schema::dropIfExists('delivery_partners');
        Schema::dropIfExists('udhar_registries');
        Schema::dropIfExists('physical_store_logs');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('physical_stores');
    }
};
