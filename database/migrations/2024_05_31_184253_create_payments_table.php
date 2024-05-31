<?php

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('amount')->default(0);
            $table->enum('status',
                [
                    PaymentStatusEnum::CREATED->value,
                    PaymentStatusEnum::FAILED->value,
                    PaymentStatusEnum::CONFIRM->value,
                    PaymentStatusEnum::WAITING->value,
                    PaymentStatusEnum::CANCELED->value
                ]
            )->default(PaymentStatusEnum::CREATED->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
