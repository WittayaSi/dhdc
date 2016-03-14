<?php
/* @var $this yii\web\View */
?>

<div class="alert alert-success" align="center">
    <h3>หมวดรายงาน-แม่และเด็ก</h3>
</div>
<br>

<p>
    <?php
    echo \yii\helpers\Html::a('1) หญิงคลอดได้รับการฝากครรภ์ครบ 5 ครั้งตามเกณฑ์', ['mom/report1']);
    ?>
</p>
<p>
    <?php
    echo \yii\helpers\Html::a('2) หญิงคลอดได้รับการฝากครรภ์ครั้งแรก ก่อน 12 สัปดาห์', ['mom/report2']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('3) ทารกแรกเกิดน้ำหนักน้อยกว่า 2500 กรัม', ['mom/report4']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('4) ภาวะโภชนาการเด็ก 0-5 ปี เป็นปกติ', ['mom/report5']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('5) การดูแลหญิงหลังคลอดครบ 3 ครั้งตามเกณฑ์', ['mom/report6']);
    ?>
</p>


<div class="footerrow" style="padding-top: 60px">
    <div class="alert alert-success">
        หมายเหตุ : ระบบรายงานอยู่ระหว่างพัฒนาอย่างต่อเนื่อง
    </div>
</div>
