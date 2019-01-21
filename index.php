<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8_decode">
	<title>Задание 3</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
 <h2 style="text-align:center">Задание 3</h2><hr>
<div style="width:80%; margin:0 auto;">
<?php

		include ('zipf.php');
	$dir  = 'files';
	$rubric = array_diff(scandir($dir), array('..', '.'));	
	$catalog = array();	
	$directory = array();
	
	foreach($rubric as $r) {
		$subdirectory = array();				
		if(is_dir($dir . '/' . $r)){		
			$files = array_diff(scandir($dir . '/' . $r), array('..', '.'));
			$directory[$r] = $files	;
			foreach($files as $nameFiles) {
				
				$data =   file_get_contents($dir. '/'  . $r. '/' . $nameFiles);		
				$keyWord1 = keyWords($data);				
					
				for($i=0; $i<count($keyWord1); $i++){
					$subdirectory[] = $keyWord1[$i];
				}				
			}  
			$catalog[$r] = array_unique($subdirectory);
			
		}
	}
	//print_r($directory);	
	 	 	
	foreach($rubric as $r) {
		if(!is_dir($dir . '/' . $r)){			
			$data =   file_get_contents($dir. '/'  . $r);
			$keyWord = keyWords($data); 
			//echo '<h1>' . mb_convert_encoding ($r, "UTF-8", "cp1251") . '</h1><br>';
			//echo '<b>Ключевые слова:</b> '.implode(', ',$keyWord) .'</br>';
			
			$countWord = array();
			foreach ($catalog as $key => $value){
				$commonWords = array_intersect($value ,$keyWord);				
				$countWord[$key] = count($commonWords);				
				//echo mb_convert_encoding ($key, "UTF-8", "cp1251") .  ' _____ '. count($commonWords) . '<br>';
				//print_r($commonWords); 				
				//echo '<br>';		
			}				
			arsort($countWord);
			//print_r($countWord);
			//echo '<h4>Ответ:</h4>';
			//echo 'Файл <b>'  . mb_convert_encoding ($r, "UTF-8", "cp1251") . '</b> относиться к рубрике <b>'. mb_convert_encoding (array_search(current($countWord), $countWord), "UTF-8", "cp1251") . '</b><br>';
			$directory[array_search(current($countWord), $countWord)][] =  $r;
		}		
	}
	echo '<h1><b>Выходные данные:</b></h1><br>';
	foreach($directory as $key=>$value){
		echo '<h1 style="text-indent: 1em;"><b>'. mb_convert_encoding($key, "UTF-8", "cp1251").'</b></h1><br>';
		sort($value);
		foreach($value as $k=>$val){
			echo '<p style="text-indent: 5em; margin: 0px; margin-bottom: 0px;">'. mb_convert_encoding($val, "UTF-8", "cp1251").'</p><br>';
		}
	}

function keyWords($data1){	
	
	$data1 = Zipf::full_trim($data1); // обрезание лишних пробелов	
	// разбиение слов на массивы
	$in = mb_strtolower($data1, 'UTF-8');
	$in = preg_replace("'ё'u", "е", $in);
	//$arrWorks = preg_match_all("'[a-zа-яё]+'u", $in, $m) ? $m[0] : array();
	$arrWorks = explode(' ',$in);
	
	foreach($arrWorks as $key => $value){
		if($value=="") unset($arrWorks[$key]);
	}
	$data_count = count($arrWorks); // количество слов в тексте
	$collection = Zipf::createObjParap($arrWorks); // нахождение частоты
	$collection = Zipf::p($collection,$data_count); // нахождение вероятности
	$collection = Zipf::sorting($collection); // сортировка по количеству входжений слов в тексте
	$collection = Zipf::rank($collection); // находим (расчитывыем) ранг
	$collection = Zipf::c($collection,$data_count); // нахождение c 		
	$collection = Zipf::StopWorks($collection);
	$collection = Zipf::q($collection[0]);	
	return Zipf::getKeys($collection);		
}
	
?>
<div><p style="text-align:center "> &copy 2018, Равкович С.В. гр.581074</p></div> 
</body>
</html>