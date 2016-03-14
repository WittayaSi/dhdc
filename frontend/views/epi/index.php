<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
?>



<div class="alert alert-success" align="center">
    <h3>หมวดรายงาน-ภูมิคุ้มกันโรค</h3>
</div>
<br>
<p>
    <?php
    echo Html::a('1) เด็กอายุ 1 ปีได้รับวัคซีน BCG', ['reportbcg']);
    ?>
</p>
<p>
    <?php
    echo Html::a('2) เด็กอายุ 1 ปีได้รับวัคซีน MMR', ['reportmmr']);
    ?>
</p><p>
    <?php
    echo Html::a('3) เด็กอายุ 5 ปีได้รับวัคซีน DTP5', ['report-dtp5']);
    ?>
</p>
<!--<p>
    <?php
    echo Html::a('4) ผลงานการรณรงค์ฉีดวัคซีน dTC (อายุ 20-50 ปี)', ['report2']);
    ?>
</p>-->


<div class="footerrow" style="padding-top: 60px">
    <div class="alert alert-success">
        หมายเหตุ : ระบบรายงานอยู่ระหว่างพัฒนาอย่างต่อเนื่อง
    </div>
</div>
