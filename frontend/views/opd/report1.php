<?php

use yii\helpers\Html;
?>
<?php
$this->params['breadcrumbs'][] = ['label' => 'บริการผู้ป่วยนอก', 'url' => ['opd/index']];
$this->params['breadcrumbs'][] = 'การมารับบริการผู้ป่วยนอก';
?>

<div class='alert alert-success'>
    <form method="POST" align="center">

        <div class='row'>
            <div class="col-sm-5"></div>
            <div class='col-sm-2'>
                <center>ปีงบประมาณ</center><br>
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
<div id="sql" style="display: none"><?= '' ?></div>

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
            'nullDisplay' => '-'
        ],
        'panel' => [
            'before' => '',
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
            //'after' => 'โดย ' . $dev
        ],
        'columns' => [
            [
                'attribute' => 'hoscode',
                'label' => '',
                //'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'hosname',
                'label' => 'สถานบริการ',
                'noWrap' => true,
            ],
            [
                'attribute' => 'oct',
                'label' => "ตค" . $py . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'oct1',
                'label' => "ตค" . $py . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'nov',
                'label' => "พย" . $py . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'nov1',
                'label' => "พย" . $py . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'dec',
                'label' => "ธค" . $py . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'dec1',
                'label' => "ธค" . $py . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'jan',
                'label' => "มค" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'jan1',
                'label' => "มค" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'feb',
                'label' => "กพ" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'feb1',
                'label' => "กพ" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'mar',
                'label' => "มีค" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'mar1',
                'label' => "มีค" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'apr',
                'label' => "เมย" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'apr1',
                'label' => "เมย" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'may',
                'label' => "พค" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'may1',
                'label' => "พค" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'jun',
                'label' => "มิย" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'jun1',
                'label' => "มิย" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'jul',
                'label' => "กค" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'jul1',
                'label' => "กค" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'aug',
                'label' => "สค" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'aug1',
                'label' => "สค" . $y . "(ครั้ง)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'sep',
                'label' => "กย" . $y . "(คน)",
                'pageSummary' => true,
                'hAlign' => 'center',
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center']
            ],
            [
                'attribute' => 'sep1',
                'label' => "กย" . $y . "(ครั้ง)",
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



