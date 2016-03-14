<?php

use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
?>
<?php
$this->params['breadcrumbs'][] = ['label' => 'สร้างเสริมภูมิคุ้มกันโรค', 'url' => ['epi/index']];
$this->params['breadcrumbs'][] = 'เด็กอายุ 1 ปีได้รับวัคซีน BCG';

$this->registerJsFile('./js/drilldown_chart.js');
?>

<div class='alert alert-success'>
    <form method="POST" align="center">
        เกิดระหว่าง:
        <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date1',
            'value' => $bdate1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
        ถึง:
        <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date2',
            'value' => $bdate2,
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
        <h3 class="panel-title"><i class="glyphicon glyphicon-signal"></i> เด็กอายุ 1 ปีได้รับวัคซีน BCG</h3>
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
                'value' => function($model) use($bdate1, $bdate2) {
                    return Html::a(Html::encode($model['hospname']), [
                                'epi/indiv-report-bcg',
                                'hospcode' => $model['hospcode'],
                                'date1' => $bdate1,
                                'date2' => $bdate2
                    ]);
                }// end value
                    ],
                    [
                        'attribute' => 'target',
                        'label' => 'เป้าหมาย(คน)',
                        'pageSummary' => true,
                        'hAlign' => 'center',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center']
                    ],
                    [
                        'attribute' => 'result',
                        'label' => 'ผลงาน(คน)',
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
                    if ($widget->col(2, $p) > 0) {
                        $persent = $widget->col(3, $p) / $widget->col(2, $p) * 100;
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



