<?php

class Tools
{
	//text消息格式
	public static $Text = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>0</FuncFlag>
		</xml>";
	
	//图文消息格式
	public static $Tuwen = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>%s</ArticleCount>
		<Articles>
		%s
		</Articles>
		</xml>";
	//图文子消息格式
	public static $TuwenArticle = "<item>
		<Title><![CDATA[%s]]></Title> 
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
		</item>";
	
	//image消息格式
	public static $Image = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		 <PicUrl><![CDATA[%s]]></PicUrl>
		<FuncFlag>0</FuncFlag>
		</xml>";
	
	//location消息格式
	public static $Location = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Location_X>%s</Location_X>
		<Location_Y>%s</Location_Y>
		<Scale>%s</Scale>
		<Label><![CDATA[%s]]></Label>
		<FuncFlag>0</FuncFlag>
		</xml>";
	
	//回复消息
	public static function answer_text($from, $to, $text){
		$resultStr = sprintf(self::$Text, $to, $from, time(), 'text', $text);
		return $resultStr;
	}
	
	/*
	 * 作用：回复图文消息
	 * 参数：$data, 二维数组, array(array('title'=>xx,'description'=>xx,'img'=>xx,'linkurl'=>xx))
	 */
	public static function answer_tuwen($from, $to, $data){
		$articles = '';
		foreach($data as $k=>$v){
			$articles .= sprintf(self::$TuwenArticle, $v['title'], $v['description'], $v['img'], $v['linkurl']);
		}
		$resultStr = sprintf(self::$Tuwen, $to, $from, time(), count($data), $articles);
		return $resultStr;
	}
	
	//写入文件
	public static function writeFile( $p_fileBody , $p_filePath , $p_mode="w" )
	{
		$fRs = fopen( $p_filePath, $p_mode );
		$htmlFile = fwrite( $fRs, $p_fileBody );
		fclose( $fRs );
		return true;
	}
	
	//post方式获取页面
	public static function curl_post($url, $param){
		$oCurl = curl_init();
		if(stripos($url,"https://")!==FALSE){
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
		}
		if (is_string($param)) {
			$strPOST = $param;
		} else if(is_array($param)){
			$aPOST = array();
			foreach($param as $key=>$val){
				$aPOST[] = $key."=".urlencode($val);
			}
			$strPOST =  join("&", $aPOST);
		}
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	
		if(isset($strPOST)){
			curl_setopt($oCurl, CURLOPT_POST,true);
			curl_setopt($oCurl, CURLOPT_POSTFIELDS,$strPOST);
		}
		$sContent = curl_exec($oCurl);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if(intval($aStatus["http_code"])==200){
			return $sContent;
		}else{
			return false;
		}
	}
}
?>