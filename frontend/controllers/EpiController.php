<?php

namespace frontend\controllers;

use yii;

class EpiController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionReportDtp5() {//
        //$bdg_date = '2015-10-01';
        $bdate1 = "2015-10-01";
        $bdate2 = date('Y-m-d');
        $date1 = date('Y-m-d', strtotime("-5 year", strtotime($bdate1)));
        $date2 = date('Y-m-d', strtotime("-5 year", strtotime($bdate2)));
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "SELECT b.hospcode 
	,b.hospname
	,b.subdistcode
	,b.tambon_name
	,b.target
	,a.result
FROM (
	/* ตัวตั้ง */
	SELECT h.hoscode hospcode
        ,h.hosname hospname
				,t.subdistcode
				,t.tambon_name
	      ,a.result
	FROM chospital_amp h
	INNER JOIN (
		SELECT p.hospcode
			,COUNT(distinct e.pid) result
		FROM person p
		LEFT JOIN epi e on e.pid = p.pid and e.hospcode = p.hospcode             
		WHERE p.birth between '$date1' and '$date2'
			AND p.discharge = '9' 
			AND p.typearea in ('1','3')
			AND p.nation = '099'
			AND e.vaccinetype = '035'   
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t on t.subdistcode = h.subdistcode
) a

RIGHT JOIN (
	/* ตัวหาร */
	SELECT h.hoscode hospcode
				,h.hosname hospname
				,t.subdistcode
				,t.tambon_name
				,a.target
	FROM chospital_amp h
	INNER JOIN (
		SELECT p.hospcode
			,COUNT(DISTINCT p.pid) target
		FROM person p
		WHERE p.birth between '$date1' and '$date2' 
                    AND p.discharge = '9' 
                    AND p.typearea in ('1','3')
                    AND p.nation = '099'
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t on t.subdistcode = h.subdistcode
) b ON a.hospcode = b.hospcode;";



        $sql2 = "SELECT b.subdistcode 
	,b.tambon_name
	,b.target
	,a.result
FROM (
	/* ตัวตั้ง */
	SELECT t.subdistcode
				,t.tambon_name
				,a.result
	FROM ctambon t
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(distinct e.pid) result
		FROM person p
		LEFT JOIN epi e on e.pid = p.pid and e.hospcode = p.hospcode
		LEFT JOIN chospital_amp h on h.hoscode = p.HOSPCODE
		WHERE p.birth between '$date1' and '$date2'
			AND p.discharge = '9' 
			AND p.typearea in ('1','3')
			AND p.nation = '099'
			AND e.vaccinetype = '035'   
		GROUP BY h.subdistcode
	) a ON a.subdistcode = t.subdistcode
) a

RIGHT JOIN (
	/* ตัวหาร */
	SELECT t.subdistcode
		,t.tambon_name
		,a.target
	FROM ctambon t
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(DISTINCT p.pid) target
		FROM person p
		LEFT JOIN chospital_amp h on h.hoscode = p.HOSPCODE
		WHERE p.birth between '$date1' and '$date2'
                    AND p.discharge = '9' 
                    AND p.typearea in ('1','3')
                    AND p.nation = '099'
		GROUP BY h.subdistcode
	) a ON a.subdistcode=t.subdistcode
) b ON a.subdistcode = b.subdistcode;";



        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
            $rawData2 = \Yii::$app->db->createCommand($sql2)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        for ($i = 0; $i < 6; $i++) {
            $tambonName = $rawData2[$i]['tambon_name'];
            $tambonId = $rawData2[$i]['subdistcode'];
            if ($rawData2[$i]['target'] == null) {
                $percent = 0;
            } else {
                $percent = ($rawData2[$i]['result'] / $rawData2[$i]['target']) * 100;
            }
            $mainData[] = ['name' => $tambonName, 'y' => $percent, 'drilldown' => $tambonId];
        }

        for ($i = 0; $i < 6; $i++) {
            $j = $i + 1;
            $c = "0$j";
            $tambonName = "";
            $tambonId = "";
            $hospname = "";
            $data = [];
            for ($k = 0; $k < count($rawData); $k++) {
                if ($rawData[$k]['subdistcode'] == $c) {
                    $tambonId = $rawData[$k]['subdistcode'];
                    $tambonName = $rawData[$k]['tambon_name'];
                    $hospname = $rawData[$k]['hospname'];
                    if ($rawData[$k]['target'] == null) {
                        $percent = 0;
                    } else {
                        $percent = ($rawData[$k]['result'] / $rawData[$k]['target']) * 100;
                    }
                    $data[] = [$hospname,$percent];
                }
            }
            $subData[] = ['name' => $tambonName, 'id' => $tambonId, 'data' => $data];
        }

        return $this->render('reportdtp5', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2,
                    'mainData' => $mainData,
                    'subData' => $subData
        ]);
    }

// จบ report1 (dtp5)

    public function actionIndivReportDtp5($hospcode = null, $date1 = null, $date2 = null) {

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select distinct person.hospcode
            ,person.pid
            ,concat(person.name,'  ',person.lname) as fullname
            ,if(person.sex=1,'ชาย','หญิง') as sex
            ,person.birth
            ,ifnull(TIMESTAMPDIFF(MONTH,person.birth,epi.date_serv),0) as age_y,epi.date_serv
            ,if((select count(*) from epi e where e.vaccinetype='035' and concat(e.pid,e.hospcode)=concat(person.pid,person.hospcode))>0,'y','n') as result from person  
          left join epi on epi.hospcode = person.hospcode and epi.pid = person.pid  
           where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099'  
           and (person.birth BETWEEN '$date1' and '$date2')  
 and person.hospcode = '$hospcode' 
group by person.hospcode,person.pid
order by person.pid
";
        //echo $sql;
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        //print_r($rawData);
        //return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indivreportdtp5', [
                    'rawData' => $rawData,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

// indivdtp5

    public function actionReportbcg() {//
        $bdg_date = '2014-09-30';
        $bdate1 = "2014-10-01";
        $bdate2 = "2015-09-30";
        $sdate1 = date('Y-m-d', strtotime("+1 year", strtotime($bdate1)));
        $sdate2 = date('Y-m-d', strtotime("+1 year", strtotime($bdate2)));
//        print_r($bdate1);
//        echo "<br>";
//        print_r($bdate2);
//        echo "<br>";
//        print_r($sdate1);
//        echo "<br>";
//        print_r($sdate2);
//        return;
        if (Yii::$app->request->isPost) {
            $bdate1 = $_POST['date1'];
            $bdate2 = $_POST['date2'];
            $sdate1 = date('Y-m-d', strtotime("+1 year", strtotime($bdate1)));
            $sdate2 = date('Y-m-d', strtotime("+1 year", strtotime($bdate2)));
        }

        $sql = "SELECT b.hospcode 
	,b.hospname
	,b.subdistcode
	,b.tambon_name
	,b.target
	,a.result
FROM (
	/* ตัวตั้ง */
	SELECT h.hoscode hospcode
        ,h.hosname hospname
				,t.subdistcode
				,t.tambon_name
	      ,a.result
	FROM chospital_amp h
	INNER JOIN (
		SELECT p.hospcode
			,COUNT(distinct e.pid) result
		FROM person p
		LEFT JOIN epi e on e.pid = p.pid and e.hospcode = p.hospcode             
		WHERE p.birth between '$bdate1' and '$bdate2'
			AND p.discharge = '9' 
			AND p.typearea in ('1','3')
			AND p.nation = '099'
			AND e.date_serv >= '$bdate1' and e.date_serv <= '$bdate2'
			AND e.vaccinetype = '010'   
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t on t.subdistcode = h.subdistcode
) a

RIGHT JOIN (
	/* ตัวหาร */
	SELECT h.hoscode hospcode
				,h.hosname hospname
				,t.subdistcode
				,t.tambon_name
				,a.target
	FROM chospital_amp h
	INNER JOIN (
		SELECT p.hospcode
			,COUNT(DISTINCT p.pid) target
		FROM person p
		WHERE p.birth between '$bdate1' and '$bdate2' 
                    AND p.discharge = '9' 
                    AND p.typearea in ('1','3')
                    AND p.nation = '099'
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t on t.subdistcode = h.subdistcode
) b ON a.hospcode = b.hospcode;";
        
        
        
        $sql2 = "SELECT b.subdistcode 
	,b.tambon_name
	,b.target
	,a.result
FROM (
	/* ตัวตั้ง */
	SELECT t.subdistcode
				,t.tambon_name
				,a.result
	FROM ctambon t
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(distinct e.pid) result
		FROM person p
		LEFT JOIN epi e on e.pid = p.pid and e.hospcode = p.hospcode
		LEFT JOIN chospital_amp h on h.hoscode = p.HOSPCODE
		WHERE p.birth between '$bdate1' and '$bdate2'
			AND p.discharge = '9' 
			AND p.typearea in ('1','3')
			AND p.nation = '099'
			AND e.date_serv >= '$bdate1' and e.date_serv <= '$bdate2'
			AND e.vaccinetype = '010'   
		GROUP BY h.subdistcode
	) a ON a.subdistcode = t.subdistcode
) a

RIGHT JOIN (
	/* ตัวหาร */
	SELECT t.subdistcode
		,t.tambon_name
		,a.target
	FROM ctambon t
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(DISTINCT p.pid) target
		FROM person p
		LEFT JOIN chospital_amp h on h.hoscode = p.HOSPCODE
		WHERE p.birth between '$bdate1' and '$bdate2'
                    AND p.discharge = '9' 
                    AND p.typearea in ('1','3')
                    AND p.nation = '099'
		GROUP BY h.subdistcode
	) a ON a.subdistcode=t.subdistcode
) b ON a.subdistcode = b.subdistcode;";



        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
            $rawData2 = \Yii::$app->db->createCommand($sql2)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        for ($i = 0; $i < 6; $i++) {
            $tambonName = $rawData2[$i]['tambon_name'];
            $tambonId = $rawData2[$i]['subdistcode'];
            if ($rawData2[$i]['target'] == null) {
                $percent = 0;
            } else {
                $percent = ($rawData2[$i]['result'] / $rawData2[$i]['target']) * 100;
            }
            $mainData[] = ['name' => $tambonName, 'y' => $percent, 'drilldown' => $tambonId];
        }

        for ($i = 0; $i < 6; $i++) {
            $j = $i + 1;
            $c = "0$j";
            $tambonName = "";
            $tambonId = "";
            $hospname = "";
            $data = [];
            for ($k = 0; $k < count($rawData); $k++) {
                if ($rawData[$k]['subdistcode'] == $c) {
                    $tambonId = $rawData[$k]['subdistcode'];
                    $tambonName = $rawData[$k]['tambon_name'];
                    $hospname = $rawData[$k]['hospname'];
                    if ($rawData[$k]['target'] == null) {
                        $percent = 0;
                    } else {
                        $percent = ($rawData[$k]['result'] / $rawData[$k]['target']) * 100;
                    }
                    $data[] = [$hospname,$percent];
                }
            }
            $subData[] = ['name' => $tambonName, 'id' => $tambonId, 'data' => $data];
        }

        return $this->render('reportbcg', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'bdate1' => $bdate1,
                    'bdate2' => $bdate2,
                    'mainData' => $mainData,
                    'subData' => $subData
        ]);
    }

// จบ reportbcg

    public function actionIndivReportBcg($hospcode = null, $date1 = null, $date2 = null) {

        $sdate1 = date('Y-m-d', strtotime("+1 year", strtotime($date1)));
        $sdate2 = date('Y-m-d', strtotime("+1 year", strtotime($date2)));
        
//        print_r($sdate1);
//        echo "<br>";
//        print_r($sdate2);
//        return;

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select distinct person.hospcode
		,person.pid,concat(person.name,'  ',person.lname) as fullname
		,if(person.sex=1,'ชาย','หญิง') as sex
		,person.BIRTH
		,ifnull(TIMESTAMPDIFF(MONTH,person.birth,epi.date_serv),0) as age_y,epi.date_serv
		,if((select count(*) from epi e where e.vaccinetype='010' 
                and concat(e.pid,e.hospcode)=concat(person.pid,person.hospcode) 
                AND e.date_serv >= '$date1' and e.date_serv <= '$date2')>0,'y','n') as result 
from person  
left join epi on epi.hospcode = person.hospcode and epi.pid = person.pid  
where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099'  
	and (person.birth BETWEEN '$date1' and '$date2')
	and person.hospcode = '$hospcode' 
group by person.hospcode,person.pid
order by person.pid";


        //echo $sql;
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        //print_r($rawData);
        //return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indivreportbcg', [
                    'rawData' => $rawData,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

// indivbcg

    public function actionReportmmr() {//
        $bdg_date = '2014-09-30';
        $bdate1 = "2014-10-01";
        $bdate2 = "2015-09-30";
        $sdate1 = date('Y-m-d', strtotime("+1 year", strtotime($bdate1)));
        $sdate2 = date('Y-m-d', strtotime("+1 year", strtotime($bdate2)));
        if (Yii::$app->request->isPost) {
            $bdate1 = $_POST['date1'];
            $bdate2 = $_POST['date2'];
            $sdate1 = date('Y-m-d', strtotime("+1 year", strtotime($bdate1)));
            $sdate2 = date('Y-m-d', strtotime("+1 year", strtotime($bdate2)));
        }

        $sql = "SELECT b.hospcode 
	,b.hospname
	,b.subdistcode
	,b.tambon_name
	,b.target
	,a.result
FROM (
	/* ตัวตั้ง */
	SELECT h.hoscode hospcode
        ,h.hosname hospname
				,t.subdistcode
				,t.tambon_name
	      ,a.result
	FROM chospital_amp h
	INNER JOIN (
		SELECT p.hospcode
			,COUNT(distinct e.pid) result
		FROM person p
		LEFT JOIN epi e on e.pid = p.pid and e.hospcode = p.hospcode             
		WHERE p.birth between '$bdate1' and '$bdate2'
			AND p.discharge = '9' 
			AND p.typearea in ('1','3')
			AND p.nation = '099'
			AND e.date_serv >= '$bdate1' and e.date_serv <= '$bdate2'
			AND e.vaccinetype = '061'   
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t on t.subdistcode = h.subdistcode
) a

RIGHT JOIN (
	/* ตัวหาร */
	SELECT h.hoscode hospcode
				,h.hosname hospname
				,t.subdistcode
				,t.tambon_name
				,a.target
	FROM chospital_amp h
	INNER JOIN (
		SELECT p.hospcode
			,COUNT(DISTINCT p.pid) target
		FROM person p
		WHERE p.birth between '$bdate1' and '$bdate2' 
                    AND p.discharge = '9' 
                    AND p.typearea in ('1','3')
                    AND p.nation = '099'
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t on t.subdistcode = h.subdistcode
) b ON a.hospcode = b.hospcode;";

$sql2 = "SELECT b.subdistcode 
	,b.tambon_name
	,b.target
	,a.result
FROM (
	/* ตัวตั้ง */
	SELECT t.subdistcode
				,t.tambon_name
				,a.result
	FROM ctambon t
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(distinct e.pid) result
		FROM person p
		LEFT JOIN epi e on e.pid = p.pid and e.hospcode = p.hospcode
		LEFT JOIN chospital_amp h on h.hoscode = p.HOSPCODE
		WHERE p.birth between '$bdate1' and '$bdate2'
			AND p.discharge = '9' 
			AND p.typearea in ('1','3')
			AND p.nation = '099'
			AND e.date_serv >= '$bdate1' and e.date_serv <= '$bdate2'
			AND e.vaccinetype = '061'   
		GROUP BY h.subdistcode
	) a ON a.subdistcode = t.subdistcode
) a

RIGHT JOIN (
	/* ตัวหาร */
	SELECT t.subdistcode
		,t.tambon_name
		,a.target
	FROM ctambon t
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(DISTINCT p.pid) target
		FROM person p
		LEFT JOIN chospital_amp h on h.hoscode = p.HOSPCODE
		WHERE p.birth between '$bdate1' and '$bdate2'
                    AND p.discharge = '9' 
                    AND p.typearea in ('1','3')
                    AND p.nation = '099'
		GROUP BY h.subdistcode
	) a ON a.subdistcode=t.subdistcode
) b ON a.subdistcode = b.subdistcode;";



        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
            $rawData2 = \Yii::$app->db->createCommand($sql2)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }
        $dataProvider = new \yii\data\ArrayDataProvider([
            //'key' => 'hoscode',
            'allModels' => $rawData,
            'pagination' => FALSE,
        ]);
        
        for ($i = 0; $i < 6; $i++) {
            $tambonName = $rawData2[$i]['tambon_name'];
            $tambonId = $rawData2[$i]['subdistcode'];
            if ($rawData2[$i]['target'] == null) {
                $percent = 0;
            } else {
                $percent = ($rawData2[$i]['result'] / $rawData2[$i]['target']) * 100;
            }
            $mainData[] = ['name' => $tambonName, 'y' => $percent, 'drilldown' => $tambonId];
        }

        for ($i = 0; $i < 6; $i++) {
            $j = $i + 1;
            $c = "0$j";
            $tambonName = "";
            $tambonId = "";
            $hospname = "";
            $data = [];
            for ($k = 0; $k < count($rawData); $k++) {
                if ($rawData[$k]['subdistcode'] == $c) {
                    $tambonId = $rawData[$k]['subdistcode'];
                    $tambonName = $rawData[$k]['tambon_name'];
                    $hospname = $rawData[$k]['hospname'];
                    if ($rawData[$k]['target'] == null) {
                        $percent = 0;
                    } else {
                        $percent = ($rawData[$k]['result'] / $rawData[$k]['target']) * 100;
                    }
                    $data[] = [$hospname,$percent];
                }
            }
            $subData[] = ['name' => $tambonName, 'id' => $tambonId, 'data' => $data];
        }

        return $this->render('reportmmr', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'bdate1' => $bdate1,
                    'bdate2' => $bdate2,
                    'mainData' => $mainData,
                    'subData' => $subData
        ]);
    }

// จบ reportmmr

    public function actionIndivReportMmr($hospcode = null, $date1 = null, $date2 = null) {

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select distinct person.hospcode
                ,person.pid
                ,concat(person.name,'  ',person.lname) as fullname
                ,person.birth
                ,if(person.sex=1,'ชาย','หญิง') as sex,
                TIMESTAMPDIFF(YEAR,person.birth,epi.date_serv) as age_y,epi.date_serv
                ,if((select count(*) from epi e where e.vaccinetype='061' 
                and concat(e.pid,e.hospcode)=concat(person.pid,person.hospcode) 
                AND e.date_serv >= '$date1' and e.date_serv <= '$date2')>0,'y','n') as result from person  
          left join epi on epi.hospcode = person.hospcode and epi.pid = person.pid  
           where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099'  
           and (person.birth BETWEEN '$date1' and '$date2')  
 and person.hospcode = $hospcode 
group by person.hospcode,person.pid
order by person.pid
";
        // echo $sql;
        $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        //print_r($rawData);
        //return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indivreportmmr', [
                    'rawData' => $rawData,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

// indivmmr

    public function actionReport2() {//
        $sql = "SELECT p.HOSPCODE as hospcode,c.hosname as hospname,
count(DISTINCT p.pid,p.HOSPCODE) as 'dtc_all' ,
sum(CASE WHEN p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' and epi.HOSPCODE=epi.VACCINEPLACE THEN '1' ELSE '0' END) AS 'intarget_inhos',
sum(CASE WHEN p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' and epi.HOSPCODE=epi.VACCINEPLACE THEN '1' ELSE '0' END) AS 'outtarget_inhos',
sum(CASE WHEN p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' and epi.HOSPCODE!=epi.VACCINEPLACE THEN '1' ELSE '0' END) AS 'intarget_outhos',
sum(CASE WHEN p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' and epi.HOSPCODE!=epi.VACCINEPLACE THEN '1' ELSE '0' END) AS 'outtarget_outhos',
sum(CASE WHEN p.NATION='099' AND p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' THEN '1' ELSE '0' END) AS 'intarget_thai',
sum(CASE WHEN p.NATION='099' AND p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' THEN '1' ELSE '0' END) AS 'outtarget_thai',
sum(CASE WHEN p.NATION!='099' AND p.BIRTH BETWEEN '1965-01-01' and '1995-12-31'THEN '1' ELSE '0' END) AS 'intarget_foreign',
sum(CASE WHEN p.NATION!='099' AND p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31'THEN '1' ELSE '0' END) AS 'outtarget_foreign',
sum(CASE WHEN p.NATION='099' AND p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' and p.typearea in (1,3) THEN '1' ELSE '0' END) AS 'intarget_inarea',
sum(CASE WHEN p.NATION='099' AND p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' and p.typearea in (1,3) THEN '1' ELSE '0' END) AS 'outtarget_inarea',
sum(CASE WHEN p.NATION='099' AND p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' and p.typearea not in (1,3) THEN '1' ELSE '0' END) AS 'intarget_outarea',
sum(CASE WHEN p.NATION='099' AND p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' and p.typearea not in (1,3) THEN '1' ELSE '0' END) AS 'outtarget_outarea',
sum(CASE WHEN p.NATION!='099' AND p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' and p.typearea in (1,3) THEN '1' ELSE '0' END) AS 'intarget_foreign_inarea',
sum(CASE WHEN p.NATION!='099' AND p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' and p.typearea in (1,3) THEN '1' ELSE '0' END) AS 'outtarget_foreign_inarea',
sum(CASE WHEN p.NATION!='099' AND p.BIRTH BETWEEN '1965-01-01' and '1995-12-31' and p.typearea not in (1,3) THEN '1' ELSE '0' END) AS 'intarget_foreign_outarea',
sum(CASE WHEN p.NATION!='099' AND p.BIRTH not BETWEEN '1965-01-01' and '1995-12-31' and p.typearea not in (1,3) THEN '1' ELSE '0' END) AS 'outtarget_foreign_outarea'
FROM
person p
LEFT JOIN chospital_amp c ON p.HOSPCODE = c.hoscode
INNER JOIN epi ON p.HOSPCODE=epi.HOSPCODE and p.pid=epi.PID
where epi.DATE_SERV between '2014-10-01' and '2015-09-30' and epi.VACCINETYPE='901'
AND p.DISCHARGE = '9' 
group by p.HOSPCODE;";



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

        return $this->render('report2', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
        ]);
    }

// จบ report 2
}
