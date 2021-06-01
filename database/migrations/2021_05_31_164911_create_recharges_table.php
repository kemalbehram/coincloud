<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharges', function (Blueprint $table) {
            $table->id();
            $table->string('order_sn')->comment('订单编号');
            $table->integer('user_id')->comment('所属用户 ID');
            $table->integer('wallet_type_id')->comment('支付方式钱包类型 ID');
            $table->decimal('coin', 16, 5)->comment('金额');
            $table->tinyInteger('pay_type')->default('1')->comment('支付类型 1-充值 2-账户转入 3-公司代充值');
            $table->string('pay_image')->nullable()->comment('支付凭证图片');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
            $table->timestamp('confirm_time')->nullable()->comment('确认时间');
            $table->tinyInteger('pay_status')->default('0')->comment('支付状态 0-未提交 1-审核中 2-已完成');
            $table->tinyInteger('schedule')->default(0)->comment('排单状态 0-排单中 1-已排单 2-已略过');
            $table->timestamp('schedule_time')->nullable()->comment('排单时间');
            $table->tinyInteger('is_return')->default('0')->comment('是否退回 0-默认未退回 1-已退回');
            $table->decimal('return_coin', 16, 5)->comment('退回金额');
            $table->string('reason')->comment('取消原因');
            $table->timestamp('canceled_time')->nullable()->comment('取消时间');
            $table->timestamps();
            $table->softDeletes();
        });
        DB::statement("ALTER TABLE recharges comment '充值'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharges');
    }
}