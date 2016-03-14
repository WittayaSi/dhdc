<?php
$this->params['breadcrumbs'][] = ['label' => 'ทีมหมอครอบครัว', 'url' => ['kukks/index']];
$this->params['breadcrumbs'][] = 'สัดส่วนบุคลากรสาธารณสุขต่อประชากร';
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
                'attribute' => 'pop',
                'label' => 'ประชากร(คน)',
                'hAlign' => 'center',
                //'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'doctor',
                'label' => 'บุคลากรสาธารณสุข(คน)',
                'hAlign' => 'center',
                //'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'raio',
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



