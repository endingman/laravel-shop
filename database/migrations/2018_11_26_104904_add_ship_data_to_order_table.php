<?php
// +----------------------------------------------------------------------+
// | @describe 之前迁移写少了字段，这里补上
// +----------------------------------------------------------------------+
// | Copyright (c) 2015-2017 CN,  All rights reserved.
// +----------------------------------------------------------------------+
// | @Authors: The PHP Dev LiuManMan, Web, <liumansky@126.com>.
// | @Script:
// | @date     2018-11-26 10:52:172018-11-26
// +----------------------------------------------------------------------+
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipDataToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('ship_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('ship_data');
        });
    }
}
