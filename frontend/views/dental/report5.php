<?php

use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'Dental', 'url' => ['dental/index']];
$this->params['breadcrumbs'][] = 'จำนวนหญิงตั้งครรภ์ที่ได้รับบริการส่งเสริมป้องกันและรักษา';
?>

<div class='alert alert-success'>
    <form method="POST" align="center">
        ระหว่าง:
        <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date1',
            'value' => $date1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ],
        ]);
        ?>
        ถึง:
        <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date2',
            'value' => $date2,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
        <button class='btn btn-danger'>ประมวลผล</button>
    </form>
</div>

<a href="#" id="btn_sql">ชุดคำสั่ง</a>
<div id="sql" style="display: none"><?= $sql ?></div>

<?php
if (isset($dataProvider)) {


//echo yii\grid\GridView::widget([//
    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => TRUE,
        'hover' => true,
        'floatHeader' => true,
        'showPageSummary' => true,
        'formatter' => [
            'class' => 'yii\i18n\formatter',
            'nullDisplay' => '-'
        ],
        'panel' => [
            'before' => '',
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
        //'after' => 'โดย ' . $dev .' :: update 07/04/2558'
        ],
        'columns' => [
            [
                'attribute' => 'hoscode',
                'header' => 'รหัสหน่วยบริการ',
                //'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'hosname',
                'header' => 'ชื่อหน่วยบริการ'
            ],
            [
                'attribute' => 'target',
                'header' => 'จำนวนหญิงตั้งครรภ์(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'EXTRACT',
                'header' => 'ถอน(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            /* [
              'class' => '\kartik\grid\FormulaColumn',
              'header' => 'ร้อยละ',
              'value' => function ($model, $key, $index, $widget) {
              $p = compact('model', 'key', 'index');
              // เขียนสูตร
              if ($widget->col(2, $p) > 0) {
              $persent = $widget->col(3, $p) / $widget->col(2, $p) * 100;
              $persent = number_format($persent, 2);
              return $persent;
              }
              }
              ], */
            [
                'attribute' => 'FILLING',
                'header' => 'อุด(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'SCALING',
                'header' => 'ขูดหินปูน(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'PRR',
                'header' => 'PRR(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'brush',
                'header' => 'สอนแปรงฟัน(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ]
        ]
    ]);
}
?>

<?php
$script = <<< JS
$(function(){
    $("label[title='Show all data']").hide();
});
        
$('#btn_sql').on('click', function(e) {
    
   $('#sql').toggle();
});
JS;
$this->registerJs($script);
?>



