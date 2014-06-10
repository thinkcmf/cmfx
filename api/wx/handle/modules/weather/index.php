<?php
/*
 * 摘	 要: 城市对应天气查询
 * 作         者: 刘海艇
 * 参         数：$sub，匹配主体
 * 			$subIsCity，匹配主体是城市名还是含城市名的字符串
 * 修改日期: 2013-06-27
 */
function getWeather($sub, $subIsCity=true){
	$cityCodeArray = include_once dirname(__FILE__).'/cityCode.php';
	if($subIsCity){
		$sub = rtrim($sub,'市');
		if(!isset($cityCodeArray[$sub])){
			return '您查询的地址不存在！';
		}
		$cityCode = $cityCodeArray[$sub]; //城市代号
	}else{
		foreach($cityCodeArray as $k=>$v){
			if(strpos($sub, $k)!==false){
				$cityCode = $cityCodeArray[$k];
				break;
			}
		}
	}
	
	//set_time_limit(0);   //防止抓取太慢而超时
	$url = "http://m.weather.com.cn/data/$cityCode.html";
	
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL, $url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl,CURLOPT_HEADER,0);
	if(curl_errno($curl)){
		$response = curl_error($curl);
	}else{
		$response = curl_exec($curl);
	}
	curl_close($curl);
	$weather_arr = json_decode($response, true);
	
	$result = $weather_arr['weatherinfo']['city']."最近三天的天气状况为：\n今天".
		$weather_arr['weatherinfo']['weather1'].','.$weather_arr['weatherinfo']['temp1'].','.$weather_arr['weatherinfo']['wind1']."；\n明天".
		$weather_arr['weatherinfo']['weather2'].','.$weather_arr['weatherinfo']['temp2'].','.$weather_arr['weatherinfo']['wind2']."；\n后天".
		$weather_arr['weatherinfo']['weather3'].','.$weather_arr['weatherinfo']['temp3'].','.$weather_arr['weatherinfo']['wind3']."。\n★★穿衣建议：".
		$weather_arr['weatherinfo']['index_d'];
	return $result;
}