<a href="#" id="btn_sql">ชุดคำสั่ง</a>
<div id="sql" style="display: none"><?= $sql ?></div>
<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label' => 'สร้างเสริมภูมิคุ้มกันโรค', 'url' => ['epi/index']];
$this->params['breadcrumbs'][] = ['label' => 'เด็กอายุ 1 ปีได้รับวัคซีน BCG', 'url' => ['epi/reportbcg']];
$this->params['breadcrumbs'][] = 'รายบุคคล';
$this->title = "DHDC";

if (!count($rawData) > 0) {
    throw new \yii\web\ConflictHttpException("ไม่มีข้อมูล");
}


function filter($col) {
    $filterresult = Yii::$app->request->getQueryParam('filterresult', '');
    if (strlen($filterresult) > 0) {
        if (strpos($col['result'], $filterresult) !== false) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

$filteredData = array_filter($rawData, 'filter');
$searchModel = ['result' => Yii::$app->request->getQueryParam('$filterresult', '')];

$dataProvider = new ArrayDataProvider([

    'allModels' => $filteredData,
    'pagination' => false,
    'sort' => [
        'attributes' => count($rawData[0]) > 0 ? array_keys($rawData[0]) : array()
        ]]);


echo \kartik\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'panel' => ['before' => ''],
    'floatHeader' => true,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'hospcode',
            'label' => 'สถานบริการ'
        ],
        [
            'attribute' => 'pid',
            'label' => 'รหัสบุคคล'
        ],
        [
            'attribute' => 'fullname',
            'label' => 'ชื่อ - นามสกุล'
        ],
        [
            'attribute' => 'age_y',
            'label' => 'อายุ (เดือน)'
        ],
        [
            'attribute' => 'sex',
            'label' => 'เพศ'
        ],
        [
            'attribute' => 'BIRTH',
            'label' => 'วันเกิด'
        ],
        [
            'attribute' => 'date_serv',
            'label' => 'วันที่รับบริการ'
        ],
        [
            'attribute' => 'result',
            'label' => 'ผลงาน',
            'value' => function($model) {
                if ($model['result'] === 'y') {
                    return Html::encode('ได้รับ');
                } elseif ($model['result'] === 'n') {
                    return Html::encode('ไม่ได้รับ');
                } else {
                    return Html::encode('NA');
                }
            },
            'filter' => Html::dropDownList('filterresult', isset($_GET['filterresult']) ? $_GET['filterresult'] : '', ['' => 'ทั้งหมด', 'y' => 'ได้รับ', 'n' => 'ไม่ได้รับ'], ['class' => 'form-control'])
        ]
    ]
]);
?>

<?php
$script = <<< JS
$('#btn_sql').on('click', function(e) {    
   $('#sql').toggle();
});
JS;
$this->registerJs($script);
?>



