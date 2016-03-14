<?php
/* @var $this yii\web\View */
?>

<div class="alert alert-success" align="center">
    <h3>หมวดรายงาน-ประชากร</h3>
</div>
<br>

<p>
    <?php
    echo \yii\helpers\Html::a('1) ปิรามิดประชากร(จำนวนประชากรแยกกลุ่มอายุ)', ['pop/pyramid']);
    
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('2) จำนวนประชากรแยกตามประเภทการอยู่อาศัย', ['pop/checktype']);
    
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('3) ตรวจสอบ 13 หลัก', ['pop/checkcid']);
    
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('4) สิทธิการรักษาของประชากรในเขตพื้นที่รับผิดชอบ', ['pop/sit']);
    
    ?>
</p>



<div class="footerrow" style="padding-top: 60px">
    <div class="alert alert-success">
        หมายเหตุ : ระบบรายงานอยู่ระหว่างพัฒนาอย่างต่อเนื่อง
    </div>
</div>
