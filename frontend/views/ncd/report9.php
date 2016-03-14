<?php

use miloschuman\highcharts\Highcharts;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'โรคไม่ติดต่อ', 'url' => ['ncd/index']];
$this->params['breadcrumbs'][] = 'ผู้ป่วยความดันที่มารับบริการ';
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



<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-signal"></i> ผู้ป่วยความดันที่เป็นเบาหวาน</h3>
    </div>
    <div class="panel-body">
        <?php
//print_r($persent1);
        echo Highcharts::widget([
            'options' => [
                'chart' => [
                    'type' => 'column'
                ],
                'credits' => [
                    'enabled' => false
                ],
                'title' => ['text' => 'ผู้ป่วยความดันที่เป็นเบาหวาน'],
                'xAxis' => [
                    'categories' => $hospname
                ],
                'yAxis' => [
                    'title' => ['text' => 'จำนวน(ร้อยละ)'],
                    'max' => 100
                ],
                'series' => [
                    [
                        'name' => 'ร้อยละ',
                        'data' => $persent1,
                    ],
                ]
            ],
        ]);
        ?>
    </div>
</div>


<?php
if (isset($dataProvider)) {


//echo yii\grid\GridView::widget([
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
        //'after' => 'โดย ' . $dev
        ],
        'columns' => [
            [
                'attribute' => 'hospcode',
                'label' => '',
                //'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'hospname',
                'label' => 'สถานบริการ',
                'noWrap' => true,
            ],
            [
                'attribute' => 'target',
                'header' => 'ผู้ป่วยความดัน(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'result',
                'header' => 'ผู้ป่วยความดันที่มารับบริการ(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => 'ร้อยละ',
                //'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model, $key, $index, $widget) {
            $p = compact('model', 'key', 'index');
            // เขียนสูตร
            $target = $widget->col(2, $p);
            if ($target > 0) {
                $persent = $widget->col(3, $p) / $target * 100;
                $persent = number_format($persent, 2);
                return $persent;
            }
        }
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



