<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'แพทย์แผนไทย', 'url' => ['knott/index']];
$this->params['breadcrumbs'][] = 'มูลค่าการจ่ายยาแผนไทยผู้ป่วยนอก';
$this->title = 'DHDC-รายงานแพทย์แผนไทย';
?>

<div class='alert alert-success'>
    <form method="POST" align="center">
        ปีงบประมาณ:
        <div class='row'>
            <div class="col-sm-5">
            </div>

            <div class='col-sm-2'>  
                <?php
                $list_year = [
                    '2014' => '2557',
                    '2015' => '2558',
                    '2016' => '2559',
                    '2017' => '2560'];

                echo Html::dropDownList('selyear', $selyear, $list_year, [
                    'class' => 'form-control',
                    'onChange' => 'this.form.submit()',
                ]);
                ?>
            </div>
        </div>
    </form>
</div>

<a href="#" id="btn_sql">ชุดคำสั่ง</a>
<div id="sql" style="display: none"><?= $sql ?></div>

<?php
if (isset($dataProvider)) {


    $y = $selyear + 543;
    $y = substr($y, 2, 2);
    $py = $y - 1;

    //echo yii\grid\GridView::widget([
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'hover' => true,
        //'floatHeader' => true,
        'showPageSummary' => true,
        'formatter' => [
            'class' => 'yii\i18n\formatter',
            'nullDisplay' => '-',
        ],
        'panel' => [
            'before' => '',
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
        //'after' => 'โดย ' . $dev
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'hoscode',
                'header' => 'รหัส'
            ],
            [
                'attribute' => 'hosname',
                'label' => 'สถานบริการ',
                'noWrap' => true
            ],
            [
                'attribute' => 'm10_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>ต.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm10_panth_drug',
                'header' => 'ยาแผนไทย<br>ต.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm11_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>พ.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm11_panth_drug',
                'header' => 'ยาแผนไทย<br>พ.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm12_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>ธ.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm12_panth_drug',
                'header' => 'ยาแผนไทย<br>ธ.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm01_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>ม.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm01_panth_drug',
                'header' => 'ยาแผนไทย<br>ม.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm02_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>ก.พ.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm02_panth_drug',
                'header' => 'ยาแผนไทย<br>ก.พ.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm03_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>มี.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm03_panth_drug',
                'header' => 'ยาแผนไทย<br>มี.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm04_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>เม.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm04_panth_drug',
                'header' => 'ยาแผนไทย<br>เม.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm05_price_drug',
                'header' => 'ยาแผนปัจจุบัน<br>พ.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm05_panth_drug',
                'header' => 'ยาแผนไทย <br>พ.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm06_price_drug',
                'header' => 'ยาแผนปัจจุบัน <br>มิ.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm06_panth_drug',
                'header' => 'ยาแผนไทย <br>มิ.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm07_price_drug',
                'header' => 'ยาแผนปัจจุบัน <br>ก.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm07_panth_drug',
                'header' => 'ยาแผนไทย <br>ก.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm08_price_drug',
                'header' => 'ยาแผนปัจจุบัน <br>ส.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm08_panth_drug',
                'header' => 'ยาแผนไทย <br>ส.ค.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm09_price_drug',
                'header' => 'ยาแผนปัจจุบัน <br>ก.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'm09_panth_drug',
                'header' => 'ยาแผนไทย <br>ก.ย.<br>(บาท)',
                'format' => ['decimal', 2],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
        ],
    ]);
}
?>

<?php
$script = <<< JS
$('#btn_sql').on('click', function(e) {
    
   $('#sql').toggle();
});
JS;
$this->registerJs($script);
?>