<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
?>
<?php
$this->params['breadcrumbs'][] = ['label' => 'แม่และเด็ก', 'url' => ['mom/index']];
$this->params['breadcrumbs'][] = 'ทารกแรกเกิดน้ำหนักน้อยกว่า 2500 กรัม';


$this->registerJsFile('./js/drilldown_chart.js');
?>

<div class='alert alert-success'>
    <form method="POST" align="center">
        เกิดระหว่าง:
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
        <h3 class="panel-title"><i class="glyphicon glyphicon-signal"></i> ทารกแรกเกิดน้ำหนักน้อยกว่า 2500 กรัม</h3>
    </div>
    <div class="panel-body">
        <div style="display: none">
            <?=
            Highcharts::widget([
                'scripts' => [
                    'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                    //'modules/exporting', // adds Exporting button/menu to chart
                    //'themes/grid'        // applies global 'grid' theme to all charts
                    'modules/drilldown'
                ]
            ]);
            ?>
        </div>
        <?php
        $main = json_encode($mainData);
        $sub = json_encode($subData);
        //print_r($sub);
        //return;
        $this->registerJs("
                        var obj_div=$('#ch1');
                        drillDown(obj_div,$main,$sub);
                    ");
        ?>
        <div id="ch1"></div>
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
                'label' => 'รหัสสถานบริการ',
                //'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'hospname',
                'label' => 'สถานบริการ',
                'format' => 'raw',
                'value' => function($model) use($date1, $date2) {
                    return Html::a(Html::encode($model['hospname']), [
                                'mom/indiv-report4',
                                'hospcode' => $model['hospcode'],
                                'date1' => $date1,
                                'date2' => $date2
                    ]);
                }// end value
                    ],
                    [
                        'attribute' => 'target',
                        'header' => 'เป้าหมาย(คน)',
                        'pageSummary' => true,
                        'hAlign' => 'center',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center']
                    ],
                    [
                        'attribute' => 'result',
                        'header' => 'ผลงาน(คน)',
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



