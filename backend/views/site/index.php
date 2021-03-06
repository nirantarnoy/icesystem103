<?php

use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;

$this->title = 'ภาพรวมระบบ';
$dash_date = null;
if ($f_date != null && $t_date != null) {
    $dash_date = date('d/m/Y', strtotime($f_date)) . ' - ' . date('d/m/Y', strtotime($t_date));
}

?>
<br/>
<div class="site-index">
    <div class="body-content">
        <form id="form-dashboard" action="<?= Url::to(['site/index'], true) ?>" method="post">
            <div class="row">
                <div class="col-lg-4">
                    <div class="label">เลือกดูตามช่วงวันที่</div>
                    <?php
                    echo \kartik\daterange\DateRangePicker::widget([
                        'name' => 'dashboard_date',
                        'value' => $dash_date,
                        'pluginOptions' => [
                            'format' => 'DD/MM/YYYY',
                            'locale' => [
                                'format' => 'DD/MM/YYYY'
                            ],
                        ],
                        'options' => [
                            'class' => 'form-control',
                            'onchange' => '$("#form-dashboard").submit();'
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </form>
        <br>
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= number_format($prod_cnt) ?></h3>

                        <p>จำนวนสินค้าทั้งหมด</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="<?= Url::to(['product/index'], true) ?>" class="small-box-footer">ไปยังสินค้า <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= number_format($route_cnt) ?></h3>
                        <!--                        <sup style="font-size: 20px">%</sup>-->
                        <p>จำนวนสายส่ง</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="<?= Url::to(['deliveryroute/index'], true) ?>" class="small-box-footer">ไปยังสายส่ง <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= number_format($car_cnt) ?></h3>
                        <p>จำนวนรถ</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="<?= Url::to(['car/index'], true) ?>" class="small-box-footer">ไปยังข้อมูลรถ <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-fuchsia">
                    <div class="inner">
                        <h3><?= number_format($order_cnt) ?></h3>
                        <p>จำนวนใบสั่งขาย</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="<?= Url::to(['orders/index'], true) ?>" class="small-box-footer">ไปยังรายการขาย <i
                                class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>

    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <!--                    <div class="card">-->
                    <!--                        <div class="card-header border-0">-->
                    <!--                            <div class="d-flex justify-content-between">-->
                    <!--                                <h3 class="card-title">กราฟแสดงรายรับ-รายจ่าย</h3>-->
                    <!--                                <a href="javascript:void(0);">รายละเอียด</a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                        <div class="card-body">-->
                    <!--                            <div class="d-flex">-->
                    <!--                                <p class="d-flex flex-column">-->
                    <!--                                    <span class="text-bold text-lg">82,000</span>-->
                    <!--                                    <span>มูลค่า</span>-->
                    <!--                                </p>-->
                    <!--                                <p class="ml-auto d-flex flex-column text-right">-->
                    <!--                    <span class="text-success">-->
                    <!--                      <i class="fas fa-arrow-up"></i> 12.5%-->
                    <!--                    </span>-->
                    <!--                                    <span class="text-muted">Since last week</span>-->
                    <!--                                </p>-->
                    <!--                            </div>-->
                    <!--                            <!-- /.d-flex -->
                    <!---->
                    <!--                            <div class="position-relative mb-4">-->
                    <!--                                <canvas id="visitors-chart" height="200"></canvas>-->
                    <!--                            </div>-->
                    <!---->
                    <!--                            <div class="d-flex flex-row justify-content-end">-->
                    <!--                  <span class="mr-2">-->
                    <!--                    <i class="fas fa-square text-primary"></i> เดือนนี้-->
                    <!--                  </span>-->
                    <!---->
                    <!--                                <span>-->
                    <!--                    <i class="fas fa-square text-gray"></i> เดือนที่แล้ว-->
                    <!--                  </span>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!-- /.card -->
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">ยอดขายแยกประเภทขาย</h3>
                                <a href="javascript:void(0);">รายละเอียด</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!--                            <div class="d-flex">-->
                            <!--                                <p class="d-flex flex-column">-->
                            <!--                                    <span class="text-bold text-lg">18,230.00</span>-->
                            <!--                                    <span>มูลค่า</span>-->
                            <!--                                </p>-->
                            <!--                            </div>-->
                            <!-- /.d-flex -->

                            <div class="position-relative mb-12">
                                <?php
                                echo Highcharts::widget([
                                    'options' => [
                                        'title' => ['text' => ''],
                                        'subtitle' => ['text' => ''],
                                        'xAxis' => [
                                            'categories' => $category
                                        ],
                                        'yAxis' => [
                                            'title' => ['text' => 'ยอดเงิน']
                                        ],
                                        'series' => $data_by_type
                                    ]
                                ]);
                                ?>
                            </div>

                        </div>
                    </div>

                    <!--                     -------->

                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">รายการขายล่าสุด</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-tool btn-sm">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                <tr>
                                    <th>เลขที่ขาย</th>
                                    <th>วันที่</th>
                                    <th>ลูกค้า</th>
                                    <th>ยอดรวม</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($order_lastest as $value): ?>
                                    <tr>
                                        <td><?= $value->order_no ?></td>
                                        <td><?= date('d/m/Y', strtotime($value->order_date)) ?></td>
                                        <td><?= $value->name ?></td>
                                        <td><?= number_format($value->order_total_amt) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">ยอดขายแยกตามประเภทสินค้า</h3>
                                <a href="javascript:void(0);">รายละเอียด</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!--                            <div class="d-flex">-->
                            <!--                                <p class="d-flex flex-column">-->
                            <!--                                    <span class="text-bold text-lg">18,230.00</span>-->
                            <!--                                    <span>มูลค่า</span>-->
                            <!--                                </p>-->
                            <!--                                <p class="ml-auto d-flex flex-column text-right">-->
                            <!--                    <span class="text-success">-->
                            <!--                      <i class="fas fa-arrow-up"></i> 33.1%-->
                            <!--                    </span>-->
                            <!--                                    <span class="text-muted">Since last month</span>-->
                            <!--                                </p>-->
                            <!--                            </div>-->
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <?php
                                //  print_r($data_by_prod_type);
                                echo Highcharts::widget([
                                    'options' => [
                                        'chart' => [
                                            'type' => 'pie',
                                        ],
                                        'allowPointSelect' => true,
                                        'title' => ['text' => ''],
                                        'subtitle' => ['text' => ''],
                                        'showInLegend' => true,
                                        'xAxis' => [
                                            'categories' => ''
                                        ],
                                        'yAxis' => [
                                            'title' => ['text' => 'ยอดเงิน']
                                        ],
                                        'series' => [
                                            $data_by_prod_type[0]
                                        ]
                                    ]
                                ]);
                                ?>
                            </div>

                        </div>
                    </div>
                    <!-- /.card -->

                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">ยอดขายแสดงตามช่องทางการขาย</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-sm btn-tool">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-tool">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                <p class="text-success text-xl">
                                    <i class="ion ion-ios-refresh-empty"></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-success"></i> <?= number_format($order_pos_cnt) ?>
                    </span>
                                    <span class="text-muted">POS</span>
                                </p>
                            </div>
                            <!-- /.d-flex -->
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                                <p class="text-warning text-xl">
                                    <i class="ion ion-ios-cart-outline"></i>
                                </p>
                                <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-warning"></i> <?= number_format($order_normal_cnt) ?>
                    </span>
                                    <span class="text-muted">ขายหน่วยรถ</span>
                                </p>
                            </div>
                            <!-- /.d-flex -->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
</div>
<br/>

<?php

$js = <<<JS
$(function(){
    //aleret();
})
JS;

$this->registerJs($js, static::POS_END);
?>
