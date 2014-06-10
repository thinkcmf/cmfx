<?php
	/*
	 * 功       能：实例化类
	 * 参       数：$class, 类名
	 * 作       者：刘海艇
	 * 修改日期：2014-03-07
	 */
	function O($class){
		$obj = class_exists($class) ? new $class : false;
		return $obj;
	}
	
	/**
	 * 功       能：获取远程html页面的内容。
	 * 参       数：url,字符串,完整的页面地址（如http://xxx/a.php?id=1）
	 * 作       者：刘海艇
	 * 修改日期：2013-6-22
	 */
	function htmlContent($url)
	{
		//set_time_limit(0);   //防止抓取太慢而超时
		$url_arr = parse_url($url);
		$host = $url_arr['host'];
		//提取path
		if(empty($url_arr['query'])){
			$path = $url_arr['path'];
		}else{
			$path = $url_arr['path'].'?'.$url_arr['query'];
		}
		//采集
		$fp = fsockopen($host, 80, $errno, $errstr, 10);
		if (!$fp){
			return "$errstr ($errno)<br />\n";
		}else{
			//获取页面内容
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			$str = '';
			while (!feof($fp)){
				$str .= fgets($fp, 128);
			}
			fclose($fp);
			return $str;
		}
	}
	
	
	/**
	 * 功       能：获取公众平台access_token,返回Array类型。
	 * 参       数：appid, string, 申请服务账号可取得
	 * 			appsecret, string, 申请服务账号可取得
	 * 作       者：刘海艇
	 * 修改日期：2013-6-22
	 */
	function getToken($appid, $appsecret){
		$baseUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential";
		$query = "appid=$appid&secret=$appsecret";
		$url = $baseUrl. '&' .$query;
		$rst = htmlContent($url);
		return json_decode($rst, true);
	}
	
	
	/**
	 * 功       能：向页面发送xml数据。本地调试用
	 * 参       数：$url, string, 页面地址
	 * 			$xml, string, xml的字符串
	 * 作       者：刘海艇
	 * 修改日期：2013-6-24
	 */
	function pushXml($url, $xml){
		if(!is_string($url) || !is_string($xml)) return;
		$header = array('Accept: */*','Content-type: text/xml','Connection: close');        //定义content-type为xml,注意是数组
		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		$response = curl_exec($ch);
		if(curl_errno($ch)){
			$response = curl_error($ch);
		}
		curl_close($ch);
		return $response;
	}
	
	/*
	 * 功          能：获取用户基本信息（需access_token）
	 * 参          数：$openid, 用户openid唯一标识
	 *           $access_token, 服务号access_token
	 *           $attr, 指定是subscribe\nickname\sex\language\city\province\country\headimgurl\subscribe_time 此时返回字符串
	 * 返          回：Array or String
	 * 作          者：刘海艇
	 * 修改日期：2014-03-07
	 */
	function userInfo($openid, $access_token, $attr=null){
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN";
		$real_url = sprintf($url, $access_token, $openid);
		$rst = curl_get($real_url);
		$rstArr = json_decode($rst, true);
		return isset($attr) ? $rstArr[$attr] : $rstArr;
	}
	
	/* 
	 * 功          能：获取页面内容，支持http\https
	 * 作          者：刘海艇
	 * 修改日期：2014-03-07
	 */
	function curl_get($url){
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL, $url);
		if(substr($url,0,5)=='https'){
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl,CURLOPT_HEADER,0);
		if(curl_errno($curl)){
			$response = curl_error($curl);
		}else{
			$response = curl_exec($curl);
		}
		curl_close($curl);
		return $response;
	}