<?php

use miloschuman\highcharts\Highcharts;

$this->title = "District HDC";
?>
<div style='display: none'>
    <?=
    Highcharts::widget([
        'scripts' => [
            'highcharts-more',
            //'themes/grid',
            //'modules/drillDown'
            //'modules/exporting',
            'modules/solid-gauge',
        ]
    ]);
    ?>
</div>
<?php
$this->registerJsFile('./js/solidGuage.js');
?>
<div class="container">
    <div class="alert alert-success" align="center">
        <h3> ภาพรวมอำเภอ </h3>
        <?php
        $model = frontend\models\SysEventLog::find()->orderBy('id DESC')->one();
        $last_process = '';
        if ($model->end_at != 'wait')
            $last_process = date_format(date_create($model->end_at), 'Y-m-d H:i:s');
        ?>
        <p>ประมวลผล <?= $last_process ?></p>
    </div>

    <div class="row">
        <div class="col-lg-4" style="text-align: center;">
            <?php
            $command = Yii::$app->db->createCommand("SELECT * FROM sys_chart_dial_7");

            $raw = $command->queryAll();
            //print_r($raw);
            //return;
            $title = "ประชาชนอายุ 35 ปีขึ้นไปได้รับการ<br>คัดกรองความดันโลหิต";
            $result = $raw[0]['result'];

            $this->registerJs("
                        var obj_div=$('#ch1');
                        solidGuage(obj_div,'$title',$result);
                    ");
            ?>
            <div id="ch1"></div>
        </div>

        <div class="col-lg-4" style="text-align: center;">
            <?php
            $command = Yii::$app->db->createCommand("SELECT sum(target) FROM sys_chart_dial_2");
            $target = $command->queryScalar();

            $command = Yii::$app->db->createCommand("SELECT sum(result) FROM sys_chart_dial_2");
            $result = $command->queryScalar();

            $a = 0.00;
            if ($target > 0) {
                $a = $result / $target * 100;
                $a = number_format($a, 2);
            }
            //$base = 90;
            $title = "ผู้ป่วยเบาหวานได้รับการตรวจ HbA1c<br>.";
            $this->registerJs("
                        var obj_div=$('#ch2');
                        solidGuage(obj_div,'$title',$a);
                    ");
            ?>
            <div id="ch2"></div>
        </div>

        <div class="col-lg-4" style="text-align: center;">
            <?php
            $command = Yii::$app->db->createCommand("SELECT sum(target) FROM sys_chart_dial_3");
            $target = $command->queryScalar();

            $command = Yii::$app->db->createCommand("SELECT sum(result) FROM sys_chart_dial_3");
            $result = $command->queryScalar();

            $a = 0.00;
            if ($target > 0) {
                $a = $result / $target * 100;
                $a = number_format($a, 2);
            }
            //$base = 90;
            $title = "ผู้ป่วยความดันโลหิตสูงควบคุม<br>ความดันโลหิตได้ดี";
            $this->registerJs("
                        var obj_div=$('#ch3');
                        solidGuage(obj_div,'$title',$a);
                    ");
            ?>
            <div id="ch3"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4" style="text-align: center;">
            <?php
            $command = Yii::$app->db->createCommand("SELECT sum(target) FROM sys_chart_dial_4");
            $target = $command->queryScalar();

            $command = Yii::$app->db->createCommand("SELECT sum(result) FROM sys_chart_dial_4");
            $result = $command->queryScalar();

            $a = 0.00;
            if ($target > 0) {
                $a = $result / $target * 100;
                $a = number_format($a, 2);
            }
            $base = 90;
            $title = "หญิงคลอดได้รับการฝากครรภ์ครบ <br>5 ครั้งตามเกณฑ์ ";
            $this->registerJs("
                        var obj_div=$('#ch4');
                        solidGuage(obj_div,'$title',$a);
                    ");
            ?>
            <div id="ch4"></div>
        </div>

        <div class="col-lg-4" style="text-align: center;">
            <?php
            $command = Yii::$app->db->createCommand("SELECT * FROM sys_chart_dial_9");

            $raw = $command->queryAll();
//print_r($raw);
//return;

            $base = $raw[0]['base'];
            $result = $raw[0]['result'];
            $title = "หญิงคลอดได้รับการฝากครรภ์ครั้งแรก<br>ก่อน 12 สัปดาห์";
            $this->registerJs("
                        var obj_div=$('#ch5');
                        solidGuage(obj_div,'$title',$result);
                    ");
            ?>
            <div id="ch5"></div>
        </div>

        <div class="col-lg-4" style="text-align: center;">
            <?php
            $command = Yii::$app->db->createCommand("SELECT sum(target) FROM sys_chart_dial_6");
            $target = $command->queryScalar();

            $command = Yii::$app->db->createCommand("SELECT sum(result) FROM sys_chart_dial_6");
            $result = $command->queryScalar();

            $a = 0.00;
            if ($target > 0) {
                $a = $result / $target * 100;
                $a = number_format($a, 2);
            }
            //$base = 85;
            $title = "เด็กอายุ 5 ปีได้รับวัคซีน DTP5<br/>.";
            $this->registerJs("
                        var obj_div=$('#ch6');
                        solidGuage(obj_div,'$title',$a);
                    ");
            ?>
            <div id="ch6"></div>
        </div>
    </div>


</div>

