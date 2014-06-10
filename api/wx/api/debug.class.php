<?php
class DEBUG{
	
	public static function text(){
		$xml = '<xml><ToUserName><![CDATA[gh_2aa68e03f59c]]></ToUserName>
				<FromUserName><![CDATA[oXljVjl4hQDqcnitvFLAai2KOU3E]]></FromUserName>
				<CreateTime>1372054505</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[你好]]></Content>
				<MsgId>5892929227304468576</MsgId>
				</xml>';
		return $xml;
	}
	
	public static function image(){
		$xml = '<xml><ToUserName><![CDATA[gh_ef34a6c9f774]]></ToUserName>
				<FromUserName><![CDATA[oI2ScjkuXBmPGW9RNWNL43WcwQz4]]></FromUserName>
				<CreateTime>1372054816</CreateTime>
				<MsgType><![CDATA[image]]></MsgType>
				<PicUrl><![CDATA[http://mmsns.qpic.cn/mmsns/kDzOjmlumOhsFpIk8GWU66vclOyBtaEewWDO2icxhhkwBCiaKiaiahXxHA/0]]></PicUrl>
				<MsgId>5892930563039297635</MsgId>
				<MediaId><![CDATA[xPikxeNOt_1LvAbrk8FEMvITrpti6Ymsqo7fKAMqWjulgJzTJyOrFzbgGQaw5_xL]]></MediaId>
				</xml>';
		return $xml;
	}
	
	public static function location(){
		$xml = '<xml><ToUserName><![CDATA[gh_ef34a6c9f774]]></ToUserName>
				<FromUserName><![CDATA[oI2ScjkuXBmPGW9RNWNL43WcwQz4]]></FromUserName>
				<CreateTime>1372054604</CreateTime>
				<MsgType><![CDATA[location]]></MsgType>
				<Location_X>31.165558</Location_X>
				<Location_Y>121.437630</Location_Y>
				<Scale>20</Scale>
				<Label><![CDATA[]]></Label>
				<MsgId>5892929652506230881</MsgId>
				</xml>';
		return $xml;
	}
	
	public static function event1(){
		$xml = '<xml><ToUserName><![CDATA[gh_ef34a6c9f774]]></ToUserName>
				<FromUserName><![CDATA[oI2Scjutw01npwMYiQUrx6kNbjgE]]></FromUserName>
				<CreateTime>1372054763</CreateTime>
				<MsgType><![CDATA[event]]></MsgType>
				<Event><![CDATA[subscribe]]></Event>
				<EventKey><![CDATA[]]></EventKey>
				</xml>';
		return $xml;
	}
	
	public static function event2(){
		$xml = '<xml><ToUserName><![CDATA[gh_ef34a6c9f774]]></ToUserName>
				<FromUserName><![CDATA[oI2ScjkuXBmPGW9RNWNL43WcwQz4]]></FromUserName>
				<CreateTime>1372054763</CreateTime>
				<MsgType><![CDATA[event]]></MsgType>
				<Event><![CDATA[subscribe]]></Event>
				<EventKey><![CDATA[]]></EventKey>
				</xml>';
		return $xml;
	}
	
	public static function voice(){
		$xml = '<xml>
				<ToUserName><![CDATA[gh_186ba3d24d8f]]></ToUserName>
				<FromUserName><![CDATA[oGm7kt-_sjtBfswWybhRQbUYZn0I]]></FromUserName>
				<CreateTime>1357290913</CreateTime>
				<MsgType><![CDATA[voice]]></MsgType>
				<MediaId><![CDATA[kWBujI6khT8xctI1IlEW]]></MediaId>
				<Format><![CDATA[amr]]></Format>
				<Recognition><![CDATA[你好啊]]></Recognition>
				<MsgId>1234567890123456</MsgId>
				</xml>';
		return $xml;
	}
}