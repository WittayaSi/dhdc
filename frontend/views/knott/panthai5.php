<?php
$this->params['breadcrumbs'][] = ['label' => 'แพทย์แผนไทย', 'url' => ['knott/index']];
$this->params['breadcrumbs'][] = 'รายงานอันดับการจ่ายยาสมุน';
$this->title = 'DHDC-รายงานแพทย์แผนไทย';
?>
<div class='alert alert-success'>
    <form method="POST" align="center">
        สถานบริการ:
        <?php
        $list = yii\helpers\ArrayHelper::map(frontend\models\ChospitalAmp::find()->all(), 'hoscode', 'hosname');
        echo yii\helpers\Html::dropDownList('hospcode', $hospcode, $list, [
            'prompt' => 'ทุกสถานบริการ',
        ]);
        ?>

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
            ]
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
            'nullDisplay' => '-',
        ],
        'panel' => [
            'before' => '',
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
            //'after' => 'โดย ' . $dev
        ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'drug_did',
                'header' => 'รหัสยา 24 หลัก'
            ],
            [
                'attribute' => 'drug_name',
                'label' => 'ชื่อยาสมุนไพร'
            ],
            [
                'attribute' => 'all_visit',
                'header' => 'รับบริการทุกสิทธิ (ครั้ง)',
                'format' => ['decimal', 0],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'uc_visit',
                'header' => 'สิทธิ UC (ครั้ง)',
                'format' => ['decimal', 0],
                'hAlign' => 'center',
                'pageSummary' => true,
                'headerOptions' => ['class' => 'text-center'],
                'contentOptions' => ['class' => 'text-center'],
            ],
        ],
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



