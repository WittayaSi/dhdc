<?php

use kartik\grid\GridView;

$this->params['breadcrumbs'][] = ['label' => 'ประชากร', 'url' => ['pop/index']];
$this->params['breadcrumbs'][] = 'ตรวจสอบประเภทการอยู่อาศัยของประชากร';
?>

<a href="#" id="btn_sql">ชุดคำสั่ง</a>
<div id="sql" style="display: none"><?= $sql ?></div>

<?php
if (isset($dataProvider)) {
//echo yii\grid\GridView::widget([
    echo GridView::widget([
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
        //'after' => 'โดย ' . \yii\helpers\Html::a('คุณศรศักดิ์ สีหะวงษ์', 'https://fb.com/sosplk', ['target' => '_blank'])
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
                'attribute' => 'type1',
                'label' => 'Type1',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'type2',
                'label' => 'Type2',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'type3',
                'label' => 'Type3',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'type4',
                'label' => 'Type4',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'total',
                'label' => 'รวม',
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
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




