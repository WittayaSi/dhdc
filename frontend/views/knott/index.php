<?php
/* @var $this yii\web\View */
$this->title = 'DHDC-รายงานแพทย์แผนไทย';
?>


<div class="alert alert-success" align="center">
    <h3>หมวดรายงาน-แพทย์แผนไทย</h3>
</div>
<br>

<p>
    <?php
    echo \yii\helpers\Html::a('1) รายงาน 10 อันดับการให้รหัสโรคแพทย์แผนไทย', ['knott/panthai1']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('2) รายงาน 10 อันดับการให้รหัสหัตถการแพทย์แผนไทย', ['knott/panthai2']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('3) มูลค่าการจ่ายยาสมุนไพรผู้ป่วยนอก', ['knott/panthai3']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('4) รายงานสรุปสัดส่วนการให้บริการแพทย์แผนไทย', ['knott/panthai4']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('5) รายงานอันดับการจ่ายยาสมุนไพร', ['knott/panthai5']);
    ?>
</p>

<p>
    <?php
    echo \yii\helpers\Html::a('6) รายงานการให้บริการ นวด อบ ประคบ', ['knott/panthai6']);
    ?>
</p>

<div class="footerrow" style="padding-top: 60px">
    <div class="alert alert-success">
        หมายเหตุ : ระบบรายงานอยู่ระหว่างพัฒนาอย่างต่อเนื่อง
    </div>
</div>
