<?php

namespace frontend\controllers;

use yii;

class ScreenController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionReport1() {
        $model = \backend\models\Sysconfigmain::find()->one();
        $bdg = $model->note2;
        $date1 = "2014-10-01";
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "SELECT h.hoscode AS hospcode 
			,h.hosname AS hospname
			,h.subdistcode
			,t.tambon_name,
(
SELECT hos_target 
FROM
 (
	SELECT p.hospcode 
				,count(distinct p.pid) as hos_target 
	FROM person p 
  WHERE p.discharge = '9' AND p.typearea IN ('1', '3') AND p.nation ='099' 
		AND (TIMESTAMPDIFF(YEAR,p.birth,'$date1') >= 35 ) 
		GROUP BY p.hospcode 
) AS t
WHERE  t.hospcode = h.hoscode
) AS target ,
(
SELECT  hos_chronic 
FROM 
	(
	SELECT p.hospcode
				,count(DISTINCT(p.pid)) AS hos_chronic 
	FROM chronic  c
  INNER JOIN person p ON c.hospcode = p.hospcode AND c.pid = p.pid 
  WHERE p.discharge = '9' 
		AND p.typearea IN ('1', '3') 
		AND p.nation ='099' 
		AND	((c.chronic BETWEEN 'E10' AND 'E149') OR (c.chronic BETWEEN 'I10' AND 'I159')) 
    AND (TIMESTAMPDIFF(YEAR,p.birth,'$date1') >= 35 )  
	GROUP BY p.hospcode
	) AS c
	WHERE c.hospcode  = h.hoscode
) AS chronic,
(
SELECT hos_doit 
FROM
	(
	SELECT p.hospcode
				,count(DISTINCT(p.pid)) AS hos_doit 
	FROM ncdscreen n  
  INNER JOIN person p ON n.hospcode = p.hospcode AND n.pid = p.pid 
  WHERE p.discharge = '9' 
		AND p.typearea IN ('1', '3') 
		AND p.nation ='099' 
		AND n.date_serv BETWEEN '$date1' AND '$date2'
    AND (TIMESTAMPDIFF(YEAR,p.birth,n.date_serv) >= 35 )  
	GROUP BY p.hospcode
	) AS r
	WHERE r.hospcode = h.hoscode
) as result 
FROM chospital_amp h
LEFT JOIN ctambon t ON t.subdistcode = h.subdistcode
ORDER BY hoscode ASC";


        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);

        return $this->render('report1', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

    public function actionReport2() {

        $selyear = date('Y');

        if (!empty($_POST['selyear'])) {
            $selyear = $_POST['selyear'];
        }

        $sql = "select * from rpt_cervical_cancer_screening r
where r.rep_year=$selyear";




        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);


        $sql = "select  $selyear rep_year,h.hoscode as hospcode,h.hosname as hospname,
ROUND((pt_all/(SELECT hos_target from
 (select person.hospcode , count(distinct person.pid) as hos_target from person  
           where person.typearea in ('1', '3') and person.nation ='099' 
           and (TIMESTAMPDIFF(YEAR,person.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) and sex = '2' group by person.hospcode ) as t
where  t.hospcode = h.hoscode
)*100),2) as percentage,
(SELECT hos_target from
 (select person.hospcode , count(distinct person.pid) as hos_target from person  
           where person.typearea in ('1', '3') and person.nation ='099' 
           and (TIMESTAMPDIFF(YEAR,person.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) and sex = '2' group by person.hospcode ) as t
where  t.hospcode = h.hoscode
) as target,
pt_all,pt_m10,pt_m11,pt_m12,pt_m01,pt_m02,pt_m03,
pt_m04,pt_m05,pt_m06,pt_m07,pt_m08,pt_m09
from chospital_amp h
left join 
	(select person.hospcode,service.DATE_SERV,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=10,person.PID,NULL)) pt_m10,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=11,person.PID,NULL)) pt_m11,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=12,person.PID,NULL)) pt_m12,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=01,person.PID,NULL)) pt_m01,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=02,person.PID,NULL)) pt_m02,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=03,person.PID,NULL)) pt_m03,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=04,person.PID,NULL)) pt_m04,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=05,person.PID,NULL)) pt_m05,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=06,person.PID,NULL)) pt_m06,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=07,person.PID,NULL)) pt_m07,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=08,person.PID,NULL)) pt_m08,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=09,person.PID,NULL)) pt_m09,
	COUNT(DISTINCT person.PID) pt_all
	from service 
    inner join diagnosis_opd d on service.hospcode = d.hospcode and service.pid = d.pid  and service.SEQ = d.SEQ
    inner join person on service.hospcode = person.hospcode and service.pid = person.pid 
    where person.typearea in ('1', '3') and person.nation ='099'  and sex = '2'
    and (TIMESTAMPDIFF(YEAR,person.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60)  and d.diagcode in ('Z014','Z124') 
    and (DATE(service.DATE_SERV) BETWEEN CONCAT(($selyear-1),'-10-01') AND CONCAT($selyear,'-09-30'))  group by person.hospcode) as r
on r.hospcode=h.hoscode
order by hoscode asc;
";

        return $this->render('report2', [
                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'selyear' => $selyear
        ]);
    }

    public function actionIndivreport2($hospcode = null, $selyear = '2015') {

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select p.hospcode,p.pid,concat(cp.prename,p.name,' ',p.lname) as fullname,
if(p.sex=1,'ชาย','หญิง') as sex,
TIMESTAMPDIFF(YEAR,p.birth,CONCAT(($selyear-1),'-09-30')) as age_y,
if(p.pid in (select p2.PID
from person p2
LEFT JOIN diagnosis_opd d2 on d2.PID = p2.PID and d2.HOSPCODE = p2.HOSPCODE 
where p2.typearea in ('1', '3') and p2.nation ='099' 
and (TIMESTAMPDIFF(YEAR,p2.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) 
and p2.sex = '2' and p2.HOSPCODE=$hospcode
and d2.DIAGCODE  in ('Z014','Z124')),'y','n') as result
from person p
LEFT JOIN cprename cp on cp.id_prename=p.prename  
where p.typearea in ('1', '3') and p.nation ='099' 
and (TIMESTAMPDIFF(YEAR,p.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) 
and p.sex = '2' and p.HOSPCODE=$hospcode";

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indivreport2', [
                    'rawData' => $rawData,
                    'sql' => $sql,
        ]);
    }

    public function actionReport3() {

        $selyear = date('Y');

        if (!empty($_POST['selyear'])) {
            $selyear = $_POST['selyear'];
        }

        $sql = "select * from rpt_breast_cancer_screening r
where r.rep_year=$selyear";




        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);


        $sql = "select  $selyear rep_year,h.hoscode as hospcode,h.hosname as hospname,
round(((pt_all/
(SELECT hos_target from
 (select person.hospcode , count(distinct person.pid) as hos_target from person  
           where person.typearea in ('1', '3') and person.nation ='099' 
           and (TIMESTAMPDIFF(YEAR,person.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) and sex = '2' group by person.hospcode ) as t
where  t.hospcode = h.hoscode
))*100),2) as percentage,
(SELECT hos_target from
 (select person.hospcode , count(distinct person.pid) as hos_target from person  
           where person.typearea in ('1', '3') and person.nation ='099' 
           and (TIMESTAMPDIFF(YEAR,person.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) and sex = '2' group by person.hospcode ) as t
where  t.hospcode = h.hoscode
) as target ,
pt_all,pt_m10,pt_m11,pt_m12,pt_m01,pt_m02,pt_m03,
pt_m04,pt_m05,pt_m06,pt_m07,pt_m08,pt_m09

from chospital_amp h
left join 
	(select person.hospcode,service.DATE_SERV,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=10,person.PID,NULL)) pt_m10,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=11,person.PID,NULL)) pt_m11,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=12,person.PID,NULL)) pt_m12,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=01,person.PID,NULL)) pt_m01,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=02,person.PID,NULL)) pt_m02,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=03,person.PID,NULL)) pt_m03,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=04,person.PID,NULL)) pt_m04,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=05,person.PID,NULL)) pt_m05,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=06,person.PID,NULL)) pt_m06,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=07,person.PID,NULL)) pt_m07,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=08,person.PID,NULL)) pt_m08,
	COUNT(DISTINCT IF(MONTH(service.DATE_SERV)=09,person.PID,NULL)) pt_m09,
	COUNT(DISTINCT person.PID) pt_all
	from service 
           inner join diagnosis_opd d on service.hospcode = d.hospcode and service.pid = d.pid  and service.SEQ = d.SEQ
           inner join person on service.hospcode = person.hospcode and service.pid = person.pid 
           where person.typearea in ('1', '3') and person.nation ='099'  and sex = '2'
           and (TIMESTAMPDIFF(YEAR,person.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60)  and d.diagcode = 'Z123' 
           and (DATE(service.DATE_SERV) BETWEEN CONCAT(($selyear-1),'-10-01') AND CONCAT($selyear,'-09-30'))  group by person.hospcode) as r
on r.hospcode=h.hoscode
order by distcode,hoscode asc;
";

        return $this->render('report3', [
                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'selyear' => $selyear
        ]);
    }

    public function actionIndivreport3($hospcode = null, $selyear = '2015') {

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select p.hospcode,p.pid,concat(cp.prename,p.name,' ',p.lname) as fullname,
if(p.sex=1,'ชาย','หญิง') as sex,
TIMESTAMPDIFF(YEAR,p.birth,CONCAT(($selyear-1),'-09-30')) as age_y,
if(p.pid in (select p2.PID
from person p2
LEFT JOIN diagnosis_opd d2 on d2.PID = p2.PID and d2.HOSPCODE = p2.HOSPCODE 
where p2.typearea in ('1', '3') and p2.nation ='099' 
and (TIMESTAMPDIFF(YEAR,p2.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) 
and p2.sex = '2' and p2.HOSPCODE=$hospcode
and d2.DIAGCODE='Z123'),'y','n') as result
from person p
LEFT JOIN cprename cp on cp.id_prename=p.prename  
where p.typearea in ('1', '3') and p.nation ='099' 
and (TIMESTAMPDIFF(YEAR,p.birth,CONCAT(($selyear-1),'-09-30')) between 30 and 60) 
and p.sex = '2' and p.HOSPCODE=$hospcode
";

        #$rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        #return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indivreport3', [
                    'rawData' => $rawData,
                    'sql' => $sql,
        ]);
    }

}
