<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'แพทย์แผนไทย', 'url' => ['knott/index']];
$this->params['breadcrumbs'][] = 'รายงานสรุปสัดส่วนการให้บริการแพทย์แผนไทย';
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
        'responsive' => TRUE,
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
                'header' => 'สถานบริการ',
                'noWrap' => true
            ],
            [
                'attribute' => 'op_visit_pt_q1',
                'header' => 'จำนวน OP<br>Q1<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_q1',
                'header' => 'จำนวน OP<br>Q1<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_pt_q1',
                'header' => 'จำนวน tm <br>Q1<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_q1',
                'header' => 'จำนวน tm <br>Q1<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_pt_q2',
                'header' => 'จำนวน OP <br>Q2<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_q2',
                'header' => 'จำนวน OP <br>Q2<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_pt_q2',
                'header' => 'จำนวน tm <br>Q2<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_q2',
                'header' => 'จำนวน tm <br>Q2<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_pt_q3',
                'header' => 'จำนวน OP <br>Q3<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_q3',
                'header' => 'จำนวน OP <br>Q3<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_pt_q3',
                'header' => 'จำนวน tm <br>Q3<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_q3',
                'header' => 'จำนวน tm <br>Q3<br>(ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_pt_q4',
                'header' => 'จำนวน OP <br>Q4<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'op_visit_q4',
                'header' => 'จำนวน OP <br>Q4<br>ครั้ง)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_pt_q4',
                'header' => 'จำนวน tm <br>Q4<br>(คน)',
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'tm_visit_q4',
                'header' => 'จำนวน tm <br>Q4<br>(ครั้ง)',
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