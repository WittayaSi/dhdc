<?php
/* @var $this yii\web\View */
?>

<div class="alert alert-success" align='center'>
    <h3>หมวดรายงาน :: ทีมหมอครอบครัว (Family Care Team)</h3>
</div>
<br>


<div class="panel panel-success">
    <div class="panel-heading">
        ทีมหมอครอบครัว
    </div>
    <div class="list-group">

        <?php
        echo \yii\helpers\Html::a('1) สัดส่วนบุคลากรสาธารณสุขต่อประชากร  <span class="label label-success">update 04-04-58</span> ', ['kukks/report2'], ['class' => 'list-group-item']);
        ?>
        <?php
        echo \yii\helpers\Html::a('2) จำนวน อสม.ต่อหลังคาเรือน <span class="label label-success">update 04-04-58</span> ', ['kukks/report3'], ['class' => 'list-group-item']);
        ?>
    </div>

    <div class="panel-heading">
        เยี่ยมบ้าน
    </div>
    <div class="list-group">

        <?php
        echo \yii\helpers\Html::a('1) ผู้ป่วยโรคเรื้อรังได้รับการเยี่ยมบ้าน <span class="label label-success">update 04-04-58</span> ', ['kukks/report1'], ['class' => 'list-group-item']);
        ?>

        <?php
        echo \yii\helpers\Html::a('2) ผู้สูงอายุ60ปีขึ้นไปได้รับการเยี่ยมบ้าน <span class="label label-success">update 04-04-58</span> ', ['kukks/report4'], ['class' => 'list-group-item']);
        ?>

        <?php
        echo \yii\helpers\Html::a('3) หญิงตั้งครรภ์ที่ได้รับการเยี่ยมบ้าน <span class="label label-success">update 04-04-58</span> ', ['kukks/report5'], ['class' => 'list-group-item']);
        ?>
    </div>

</div>





<div class="footerrow" style="padding-top: 60px">
    <div class="alert alert-success">
        หมายเหตุ : ระบบรายงานอยู่ระหว่างพัฒนาอย่างต่อเนื่อง
    </div>
</div>
