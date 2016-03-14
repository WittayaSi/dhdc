<?php
$this->params['breadcrumbs'][] = ['label' => 'ทีมหมอครอบครัว', 'url' => ['kukks/index']];
$this->params['breadcrumbs'][] = 'จำนวน อสม.ต่อหลังคาเรือน';
?>


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
        //'showPageSummary' => true,
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
            [
                'attribute' => 'hoscode',
                'label' => 'รหัสหน่วยงาน',
                'hAlign' => 'center',
                //'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'hosname',
                'label' => 'หน่วยงาน'
            ],
            [
                'attribute' => 'จำนวนหลังคาเรือน',
                'label' => 'จำนวนหลังคาเรือน',
                'hAlign' => 'center',
                //'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'จำนวน อสม.',
                'label' => 'จำนวน อสม',
                'hAlign' => 'center',
                //'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'จำนวน อสม.ต่อหลังคาเรือน',
                'label' => 'สัดส่วน',
                'hAlign' => 'center',
                //'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
        ]
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



