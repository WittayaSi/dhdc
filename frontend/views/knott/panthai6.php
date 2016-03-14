<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'แพทย์แผนไทย', 'url' => ['knott/index']];
$this->params['breadcrumbs'][] = 'รายงานการให้บริการ นวด อบ ประคบ';
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
                    'onChange' => 'this.form.submit()'
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
                'attribute' => 'hospcode',
                'header' => 'รหัส'
            ],
            [
                'attribute' => 'hosname',
                'label' => 'สถานบริการ',
                'noWrap' => true
            ],
            [
                'attribute' => 'instype',
                'label' => 'สิทธิรักษา'
            ],
            [
                'attribute' => 'pt_all',
                'header' => 'รับบริการ<br>ทั้งหมด<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_all',
                'header' => 'รับบริการ<br>ทั้งหมด<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m10',
                'header' => 'รับบริการ<br>ต.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m10',
                'header' => 'รับบริการ<br>ต.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m11',
                'header' => 'รับบริการ<br>พ.ย.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m11',
                'header' => 'รับบริการ<br>พ.ย.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m12',
                'header' => 'รับบริการ<br>ธ.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m12',
                'header' => 'รับบริการ<br>ธ.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m01',
                'header' => 'รับบริการ<br>ม.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m01',
                'header' => 'รับบริการ<br>ม.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m02',
                'header' => 'รับบริการ<br>ก.พ.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m02',
                'header' => 'รับบริการ<br>ก.พ.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m03',
                'header' => 'รับบริการ<br>มี.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m03',
                'header' => 'รับบริการ<br>มี.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m04',
                'header' => 'รับบริการ<br>เม.ย.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m04',
                'header' => 'รับบริการ<br>เม.ย.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m05',
                'header' => 'รับบริการ<br>พ.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m05',
                'header' => 'รับบริการ<br>พ.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m06',
                'header' => 'รับบริการ<br>มิ.ย.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m06',
                'header' => 'รับบริการ<br>มิ.ย.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m07',
                'header' => 'รับบริการ<br>ก.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m07',
                'header' => 'รับบริการ<br>ก.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m08',
                'header' => 'รับบริการ<br>ส.ค.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m08',
                'header' => 'รับบริการ<br>ส.ค.<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'pt_m09',
                'header' => 'รับบริการ<br>ก.ย.<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'service_m09',
                'header' => 'รับบริการ<br>ก.ย.<br>(ครั้ง)',
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