<?php

namespace frontend\controllers;

use yii;

class MomController extends \yii\web\Controller {

    public $enableCsrfValidation = false;

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionReport1() {

        $date1 = "2014-04-01";
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "SELECT h.hoscode hospcode
	,h.hosname hospname
        ,h.subdistcode
        ,t.tambon_name
	,a.target
	,a.result
FROM chospital_amp h
INNER JOIN (
	SELECT l.hospcode
		,COUNT(DISTINCT l.pid) target
		,COUNT(DISTINCT IF(a1.ga<=12 
				AND a2.ga >= 16  AND a2.ga <= 20
				AND a3.ga >= 24  AND a3.ga <= 28
				AND a4.ga >= 30  AND a4.ga <= 34
				AND a5.ga >= 36  AND a5.ga <= 42,l.pid,NULL)) result
	FROM labor l
	LEFT JOIN person p ON p.pid=l.pid AND p.hospcode=l.hospcode
	LEFT JOIN anc a1 ON a1.pid=l.pid AND a1.hospcode=l.hospcode AND a1.ga <=12 AND a1.gravida = l.gravida
	LEFT JOIN anc a2 ON a2.pid=l.pid AND a2.hospcode=l.hospcode AND a2.ga >= 16 AND a2.ga <= 20 AND a2.gravida = l.gravida
	LEFT JOIN anc a3 ON a3.pid=l.pid AND a3.hospcode=l.hospcode AND a3.ga >= 24 AND a3.ga <= 28 AND a3.gravida = l.gravida
	LEFT JOIN anc a4 ON a4.pid=l.pid AND a4.hospcode=l.hospcode AND a4.ga >= 30 AND a4.ga <= 34 AND a4.gravida = l.gravida
	LEFT JOIN anc a5 ON a5.pid=l.pid AND a5.hospcode=l.hospcode AND a5.ga >= 36 AND a5.ga <= 42 AND a5.gravida = l.gravida
	WHERE l.bdate BETWEEN '$date1' AND '$date2'
				AND p.typearea IN ('1','3')
				AND p.nation = '099'
				AND l.btype <> '6'
				AND p.DISCHARGE = '9'
	GROUP BY l.hospcode
) a ON a.hospcode=h.hoscode
LEFT JOIN ctambon t on t.subdistcode = h.subdistcode;";


        $sql2 = "SELECT t.subdistcode
	,t.tambon_name
	,a.target
	,a.result
FROM ctambon t
INNER JOIN (
	SELECT h.subdistcode
		,COUNT(DISTINCT l.pid) target
		,COUNT(DISTINCT IF(a1.ga<=12 
				AND a2.ga >= 16  AND a2.ga <= 20
				AND a3.ga >= 24  AND a3.ga <= 28
				AND a4.ga >= 30  AND a4.ga <= 34
				AND a5.ga >= 36  AND a5.ga <= 42,l.pid,NULL)) result
	FROM labor l
	LEFT JOIN person p ON p.pid=l.pid AND p.hospcode=l.hospcode
	LEFT JOIN anc a1 ON a1.pid=l.pid AND a1.hospcode=l.hospcode AND a1.ga <=12 AND a1.gravida = l.gravida
	LEFT JOIN anc a2 ON a2.pid=l.pid AND a2.hospcode=l.hospcode AND a2.ga >= 16 AND a2.ga <= 20 AND a2.gravida = l.gravida
	LEFT JOIN anc a3 ON a3.pid=l.pid AND a3.hospcode=l.hospcode AND a3.ga >= 24 AND a3.ga <= 28 AND a3.gravida = l.gravida
	LEFT JOIN anc a4 ON a4.pid=l.pid AND a4.hospcode=l.hospcode AND a4.ga >= 30 AND a4.ga <= 34 AND a4.gravida = l.gravida
	LEFT JOIN anc a5 ON a5.pid=l.pid AND a5.hospcode=l.hospcode AND a5.ga >= 36 AND a5.ga <= 42 AND a5.gravida = l.gravida
	LEFT JOIN chospital_amp h ON l.hospcode=h.hoscode 
	WHERE l.bdate BETWEEN '$date1' AND '$date2'
				AND p.typearea IN ('1','3')
				AND p.nation = '099'
				AND l.btype <> '6'
				AND p.DISCHARGE = '9'
	GROUP BY h.subdistcode
) a ON a.subdistcode=t.subdistcode;";



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

        // main data

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

        return $this->render('report1', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2,
                    'mainData' => $mainData,
                    'subData' => $subData
        ]);
    }

    // 5times
    public function actionIndivReport1($hospcode = null, $date1 = null, $date2 = null) {

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select labor.hospcode,anc1.pid,concat(person.name,'  ',person.lname) as fullname,labor.bdate,total1,total2,total3,total4,total5,if((total1+total2+total3+total4+total5)=5,'y','n') as result
from labor 
left join 
(select anc1.hospcode,anc1.pid,anc1.gravida,count(distinct anc1.pid) as total1 
from anc anc1
where anc1.ga <= 12  
group by anc1.hospcode,anc1.pid  ) as anc1
on labor.hospcode = anc1.hospcode and labor.pid = anc1.pid
left join 
(select anc2.hospcode,anc2.pid,anc2.gravida,count(distinct anc2.pid) as total2 
from anc anc2
where anc2.ga between 16 and 20 
group by anc2.hospcode,anc2.pid  ) as anc2
on anc1.hospcode = anc2.hospcode and anc1.pid = anc2.pid and anc1.gravida = anc2.gravida
left join 
(select anc3.hospcode,anc3.pid,anc3.gravida,count(distinct anc3.pid) as total3 
from anc anc3
where anc3.ga between 24 and 28
group by anc3.hospcode,anc3.pid  ) as anc3
on anc1.hospcode = anc3.hospcode and anc1.pid = anc3.pid and anc1.gravida = anc3.gravida
left join 
(select anc4.hospcode,anc4.pid,anc4.gravida,count(distinct anc4.pid) as total4 
from anc anc4
where anc4.ga between 30 and 34
group by anc4.hospcode,anc4.pid  ) as anc4
on anc1.hospcode = anc4.hospcode and anc1.pid = anc4.pid and anc1.gravida = anc4.gravida
left join 
(select anc5.hospcode,anc5.pid,anc5.gravida,count(distinct anc5.pid) as total5 
from anc anc5
where anc5.ga between 36 and 40 
group by anc5.hospcode,anc5.pid  ) as anc5
on anc1.hospcode = anc5.hospcode and anc1.pid = anc5.pid and anc1.gravida = anc5.gravida

inner join person on person.hospcode = labor.hospcode and person.pid = labor.pid 
where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099' and person.sex = '2' 
             and labor.bdate between  '$date1' and '$date2' 
             and labor.hospcode = $hospcode    
";
        // echo $sql;
        //$rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        //print_r($rawData);
        //return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indiv_report1', [
                    'rawData' => $rawData,
                    'sql' => $sql,
        ]);
    }

// indiv5times

    public function actionReport2() {

        $date1 = "2014-04-01";
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "SELECT h.hoscode hospcode
	,h.hosname hospname
	,t.subdistcode
	,t.tambon_name
	,a.target
  ,a.result
FROM chospital_amp h
LEFT JOIN (
	SELECT l.hospcode
		,COUNT(DISTINCT l.pid) target
		,COUNT(DISTINCT IF(a.ga<=12,a.pid,NULL)) result
	FROM labor l
	LEFT JOIN person p ON (p.pid = l.pid AND l.hospcode = p.hospcode)
	LEFT JOIN anc a ON (a.pid = l.pid AND a.hospcode = l.hospcode) 
				AND a.gravida = l.gravida
				AND a.ancno=1
	WHERE l.bdate BETWEEN '$date1' AND '$date2'
			AND p.DISCHARGE = '9'
			AND p.typearea IN ('1','3') 
			AND p.NATION = '099'
	GROUP BY l.hospcode
) a ON a.hospcode=h.hoscode
LEFT JOIN ctambon t ON t.subdistcode = h.subdistcode;";
        
        $sql2 = "SELECT t.subdistcode
	,t.tambon_name
	,a.target
  ,a.result
FROM ctambon t
LEFT JOIN (
	SELECT h.subdistcode
		,COUNT(DISTINCT l.pid) target
		,COUNT(DISTINCT IF(a.ga<=12,a.pid,NULL)) result
	FROM labor l
	LEFT JOIN person p ON (p.pid = l.pid AND l.hospcode = p.hospcode)
	LEFT JOIN anc a ON (a.pid = l.pid AND a.hospcode = l.hospcode) 
				AND a.gravida = l.gravida
				AND a.ancno=1
	LEFT JOIN chospital_amp h ON h.hoscode = l.hospcode
	WHERE l.bdate BETWEEN '$date1' AND '$date2'
			AND p.DISCHARGE = '9'
			AND p.typearea IN ('1','3') 
			AND p.NATION = '099'
	GROUP BY h.subdistcode
) a ON a.subdistcode=t.subdistcode;";
        

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

        return $this->render('report2', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2,
                    'mainData' => $mainData,
                    'subData' => $subData
        ]);
    }

    // 12wks

    public function actionIndivReport2($hospcode = null, $date1 = null, $date2 = null) {

        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select distinct person.hospcode,(select c.hosname from chospital_amp c where c.hoscode = person.hospcode) as hospname,ga,
person.pid,concat(person.name,'  ',person.lname) as fullname,labor.bdate,if(anc.ga<=12,'y','n') as result 
from labor 
INNER JOIN person ON person.hospcode = labor.hospcode AND person.pid = labor.pid 
LEFT JOIN anc on labor.hospcode = anc.hospcode and labor.pid = anc.pid and labor.gravida = anc.gravida and anc.ga < 13
WHERE person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099' and person.sex = '2' 
      and labor.btype<>'6'   and labor.bdate BETWEEN  '$date1' AND '$date2'
      and person.hospcode = $hospcode
order by labor.hospcode,labor.bdate 
";
        // echo $sql;
        //$rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        //print_r($rawData);
        //return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indiv_report2', [
                    'rawData' => $rawData,
                    'sql' => $sql,
        ]);
    }

// indiv12wks

    public function actionReport3() {

        $date1 = "2014-10-01";
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "select  h.hoscode as hospcode ,h.hosname as hospname,
(SELECT hos_target from
 (select person.hospcode , count(distinct person.pid) as hos_target from person  
           where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099' 
           and (person.birth BETWEEN DATE_ADD('$date1',INTERVAL -71 month) and DATE_ADD('$date2',INTERVAL -71 month)) group by person.hospcode ) as t
where  t.hospcode = h.hoscode
) as target ,
(SELECT hos_doit from
          (select person.hospcode,count(distinct(person.pid)) as hos_doit from epi  inner join person on epi.hospcode = person.hospcode and epi.pid = person.pid 
           where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099'  
           and (person.birth BETWEEN DATE_ADD('$date1',INTERVAL -71 month) and DATE_ADD('$date2',INTERVAL -71 month))  and epi.VACCINETYPE = '035'  group by person.hospcode) as r
where r.hospcode = h.hoscode
) as result 

from chospital_amp h
order by distcode,hoscode asc";

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

        return $this->render('report3', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

    public function actionReport4() {
        $date1 = "2014-04-01";
        $date2 = date('Y-m-d');
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
		SELECT p.hospcode hospcode
			,COUNT(DISTINCT p.pid) result
		 FROM newborn n 
		 left join person p on n.pid = p.pid AND n.hospcode = p.hospcode
		 WHERE  p.typearea in ('1','3')
			AND p.nation = '099'
			AND n.bweight < 2500
			AND n.bdate between '$date1' and '$date2'
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t ON t.subdistcode = h.subdistcode
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
		SELECT p.hospcode hospcode
			,COUNT(DISTINCT p.pid) target
		 FROM newborn n 
		 left join person p on n.pid = p.pid AND n.hospcode = p.hospcode
		 WHERE  p.typearea in ('1','3')
			AND p.nation = '099'
			AND n.bdate between '$date1' and '$date2'
		GROUP BY p.hospcode
	) a ON a.hospcode=h.hoscode
	LEFT JOIN ctambon t ON t.subdistcode = h.subdistcode
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
			,COUNT(DISTINCT p.pid) result
		 FROM newborn n 
		 left join person p on n.pid = p.pid AND n.hospcode = p.hospcode
		 LEFT JOIN chospital_amp h ON h.hoscode = n.hospcode
		 WHERE  p.typearea in ('1','3')
			AND p.nation = '099'
			AND n.bweight < 2500
			AND n.bdate between '$date1' and '$date2'
		GROUP BY h.subdistcode
	) a ON a.subdistcode = t.subdistcode
) a
RIGHT JOIN (
	/* ตัวหาร */
	SELECT t1.subdistcode
		,t1.tambon_name
		,a.target
	FROM ctambon t1
	INNER JOIN (
		SELECT h.subdistcode
			,COUNT(DISTINCT p.pid) target
		 FROM newborn n 
		 left join person p on n.pid = p.pid AND n.hospcode = p.hospcode
		 LEFT JOIN chospital_amp h ON h.hoscode = n.hospcode
		 WHERE  p.typearea in ('1','3')
			AND p.nation = '099'
			AND n.bdate between '$date1' and '$date2'
		GROUP BY h.subdistcode
	) a ON a.subdistcode = t1.subdistcode
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

        return $this->render('report4', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2,
                    'mainData' => $mainData,
                    'subData' => $subData
        ]);
    }

// 2500g

    public function actionIndivReport4($hospcode = null, $date1 = null, $date2 = null) {
        //ทารกแรกเกิดน้ำหนักน้อยกว่า 2500 กรัม
        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;
        if ($role == 99) {
            throw new \yii\web\ConflictHttpException('ไม่อนุญาต');
        }

        $sql = "select person.hospcode,person.pid,concat(person.name,'  ',person.lname) as fullname,if(person.sex=1,'ชาย','หญิง') as sex,
TIMESTAMPDIFF(MONTH,person.birth,CURDATE()) as age_y,newborn.bdate ,if(BWEIGHT < 2500,'y','n') as result
from newborn  
inner join person on newborn.hospcode = person.hospcode and newborn.pid = person.pid 
where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099'  
           and (newborn.BDATE BETWEEN '$date1' and '$date2')
and person.hospcode = $hospcode
";
        // echo $sql;
        // $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        //print_r($rawData);
        //return;

        try {
            $rawData = \Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $e) {
            throw new \yii\web\ConflictHttpException('sql error');
        }

        return $this->render('indiv_report4', [
                    'rawData' => $rawData,
                    'sql' => $sql,
        ]);
    }

    public function actionReport5() {

        $date1 = "2014-10-01";
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "select  h.hoscode as hospcode ,h.hosname as hospname,child.total as target,dev.total as result 
from chospital_amp h
left join
         (select person.hospcode,count(distinct person.pid) as total
           from person
           where  person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099' 
           and (person.birth between date_add('$date1',interval -71 month) and date_add('$date2',interval -0 month))
           group by person.hospcode ) child
on h.hoscode = child.hospcode
left join
         (select n.hospcode,count(distinct person.pid) as total
           from nutrition n inner join person on n.hospcode=person.hospcode and n.pid=person.pid  
           where person.discharge = '9' and person.typearea in ('1', '3') and person.nation ='099' 
           and n.childdevelop = '1' and n.date_serv between '2014-10-01' and '$date2'
           and (person.birth between date_add('$date1',interval -71 month) and date_add('2015-09-30',interval -0 month))
           group by n.hospcode
          ) dev
on h.hoscode = dev.hospcode

group by hoscode";

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

        return $this->render('report5', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

    public function actionReport6() {

        $date1 = "2014-10-01";
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "select h.hoscode as hospcode ,h.hosname as hospname,
(select count(distinct cid) as num 
from 
(select
p.hospcode,p.cid,p.pid,p.prename,p.name,p.lname,
la.lmp,la.bdate,la.btype,
(select po_date 
from 
(select
l.hospcode,
l.pid,

datediff(po.ppcare,l.bdate) as po_date
from
labor as l,
postnatal as po 
where  (l.hospcode = po.hospcode 
and l.pid = po.pid)) as a2
where a2.po_date between '1' and '7' and a2.pid=p.pid and a2.hospcode=p.hospcode
group by a2.pid
) as ppc_no1,
(select po_date 
from 
(select
l.hospcode,l.pid,
datediff(po.ppcare,l.bdate) as po_date
from
labor as l,
postnatal as po 
where  (l.hospcode = po.hospcode and l.pid = po.pid)) as a2
where a2.po_date between '8' and '15'   and a2.pid=p.pid and a2.hospcode=p.hospcode
group by a2.pid
) as ppc_no2,
(select po_date 
from 
(select l.hospcode,l.pid,
datediff(po.ppcare,l.bdate) as po_date
from
labor as l,
postnatal as po 
where  (l.hospcode = po.hospcode and l.pid = po.pid)) as a2
where a2.po_date between '16' and '42'    and a2.pid=p.pid and a2.hospcode=p.hospcode
group by a2.pid
) as ppc_no3
from
labor as la
,person as p 
where  la.pid = p.pid and p.hospcode = la.hospcode
and la.bdate between '2014-10-01' and '2015-09-30'
and p.nation='099' and p.discharge='9' and p.typearea in ('1','3') and la.btype<>'6'
order by p.hospcode 
) as ppc3t where ppc3t.hospcode=h.hoscode
) as target,
(
select count(distinct cid) as num 
from 
(select
p.hospcode,p.cid,p.pid,p.prename,p.name,p.lname,
la.lmp,la.bdate,la.btype,
(select po_date 
from 
(select
l.hospcode,
l.pid,
datediff(po.ppcare,l.bdate) as po_date
from
labor as l,
postnatal as po 
where (l.hospcode = po.hospcode and l.pid = po.pid)) as a2
where a2.po_date between '1' and '7' and a2.pid=p.pid and a2.hospcode=p.hospcode
group by a2.pid
) as ppc_no1,
(select po_date 
from 
(select
l.hospcode,l.pid,
datediff(po.ppcare,l.bdate) as po_date
from
labor as l,
postnatal as po 
where  (l.hospcode = po.hospcode and l.pid = po.pid)) as a2
where a2.po_date between '8' and '15'   and a2.pid=p.pid and a2.hospcode=p.hospcode
group by a2.pid
) as ppc_no2,
(select po_date 
from 
(select 
l.hospcode,
l.pid,
datediff(po.ppcare,l.bdate) as po_date
from
labor as l,
postnatal as po 
where (l.hospcode = po.hospcode and l.pid = po.pid)) as a2
where a2.po_date between '16' and '42'    and a2.pid=p.pid and a2.hospcode=p.hospcode
group by a2.pid
) as ppc_no3
from
labor as la
,person as p 
where  (la.pid = p.pid  and p.hospcode = la.hospcode)
and la.bdate between '$date1' and '$date2'
and p.nation='099' and p.discharge='9' and p.typearea in ('1','3') and la.btype<>'6'
order by p.hospcode 
) as ppc3 where ppc3.hospcode=h.hoscode
and ppc_no1 is not null and ppc_no2 is not null and ppc_no3 is not null 
) as result
from chospital_amp h";

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

        return $this->render('report6', [

                    'dataProvider' => $dataProvider,
                    'sql' => $sql,
                    'date1' => $date1,
                    'date2' => $date2
        ]);
    }

// indiv2500g
}
