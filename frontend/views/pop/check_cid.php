<?php
$this->params['breadcrumbs'][] = ['label' => 'ประชากร', 'url' => ['pop/index']];
$this->params['breadcrumbs'][] = 'ตรวจสอบ 13 หลัก';
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
        'showPageSummary' => true,
        'formatter' => [
            'class' => 'yii\i18n\formatter',
            'nullDisplay' => '-'
        ],
        'panel' => [
            'before' => '',
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
        //'after' => 'โดย '. \yii\helpers\Html::a('คุณศรศักดิ์ สีหะวงษ์', 'https://fb.com/sosplk',['target'=>'_blank'])
        ],
        'columns' => [
            [
                'attribute' => 'hospcode',
                'label' => 'Hospcode',
                //'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'hospname',
                'label' => 'Hospname',
            ],
            [
                'attribute' => 'CIDเป็นค่าว่าง',
                'label' => 'CIDเป็นค่าว่าง',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'CIDไม่เท่ากับ13หลัก',
                'label' => 'CIDไม่เท่ากับ13หลัก',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'สัญชาติไม่ใช่ไทย',
                'label' => 'สัญชาติไม่ใช่ไทย',
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
$('#btn_sql').on('click', function(e) {
    
   $('#sql').toggle();
});
JS;
$this->registerJs($script);
?>




