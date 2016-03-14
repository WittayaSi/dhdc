<?php
$this->params['breadcrumbs'][] = ['label' => 'โรคไม่ติดต่อ', 'url' => ['ncd/index']];
$this->params['breadcrumbs'][] = 'ผู้ป่วยเบาหวานได้รับการตรวจ HbA1c และควบคุมน้ำตาลได้ดี';
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
                'header' => 'กลุ่มประชากรเป้าหมาย(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'result',
                'header' => 'จำนวนผู้ป่วย(คน)',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'control',
                'header' => 'ควบคุมได้(คน)',
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


