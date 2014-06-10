<?php

class SplitWord
{
	var $TagDic = Array();
	var $RankDic = Array();
	var $OneNameDic = Array();
	var $TwoNameDic = Array();
	var $SourceString = '';
	var $ResultString = '';
	var $SplitChar = ','; //分隔符
	var $SplitLen = 4; //保留词长度
	var $EspecialChar = "和|的|是";
	var $NewWordLimit = "在|的|与|或|就|你|我|他|她|有|了|是|其|能|对|地";
	
	//这里可以按需要加入常用的量词，
	//程序会检测词语第一个字是否为这些词和上一个词是否为数词，然后结合为单词
	var $CommonUnit = "年|月|日|时|分|秒|点|元|百|千|万|亿|位|辆";
	
	var $CnNumber = "％|＋|－|０|１|２|３|４|５|６|７|８|９|．";
	var $CnSgNum = "一|二|三|四|五|六|七|八|九|十|百|千|万|亿|数";
	var $MaxLen = 13; //词典最大 7 中文字，这里的数值为字节数组的最大索引
	var $MinLen = 3;  //最小 2 中文字，这里的数值为字节数组的最大索引
	var $CnTwoName = "端木 南宫 谯笪 轩辕 令狐 钟离 闾丘 长孙 鲜于 宇文 司徒 司空 上官 欧阳 公孙 西门 东门 左丘 东郭 呼延 慕容 司马 夏侯 诸葛 东方 赫连 皇甫 尉迟 申屠";
	var $CnOneName = "赵钱孙李周吴郑王冯陈褚卫蒋沈韩杨朱秦尤许何吕施张孔曹严华金魏陶姜戚谢邹喻柏水窦章云苏潘葛奚范彭郎鲁韦昌马苗凤花方俞任袁柳酆鲍史唐费廉岑薛雷贺倪汤滕殷罗毕郝邬安常乐于时傅皮卡齐康伍余元卜顾孟平黄穆萧尹姚邵堪汪祁毛禹狄米贝明臧计伏成戴谈宋茅庞熊纪舒屈项祝董粱杜阮蓝闵席季麻强贾路娄危江童颜郭梅盛林刁钟徐邱骆高夏蔡田樊胡凌霍虞万支柯咎管卢莫经房裘缪干解应宗宣丁贲邓郁单杭洪包诸左石崔吉钮龚程嵇邢滑裴陆荣翁荀羊於惠甄魏加封芮羿储靳汲邴糜松井段富巫乌焦巴弓牧隗谷车侯宓蓬全郗班仰秋仲伊宫宁仇栾暴甘钭厉戎祖武符刘姜詹束龙叶幸司韶郜黎蓟薄印宿白怀蒲台从鄂索咸籍赖卓蔺屠蒙池乔阴郁胥能苍双闻莘党翟谭贡劳逄姬申扶堵冉宰郦雍郤璩桑桂濮牛寿通边扈燕冀郏浦尚农温别庄晏柴翟阎充慕连茹习宦艾鱼容向古易慎戈廖庚终暨居衡步都耿满弘匡国文寇广禄阙东殴殳沃利蔚越夔隆师巩厍聂晁勾敖融冷訾辛阚那简饶空曾沙须丰巢关蒯相查后江游竺";
	
	var $charset = 'gbk';
	
	//------------------------------
	//php4构造函数
	//------------------------------
	function SplitWord()
	{
		$this->__construct();
	}
	//------------------------------
	//php5构造函数
	//------------------------------
	function __construct()
	{  	  	
		//载入姓氏词典
		for($i=0;$i<strlen($this->CnOneName);$i++)
		{
			$this->OneNameDic[$this->CnOneName[$i].$this->CnOneName[$i+1]] = 1;
			$i++;
		}
		$twoname = explode(" ",$this->CnTwoName);
		foreach($twoname as $n){ $this->TwoNameDic[$n] = 1; }
		unset($twoname);
		unset($this->CnOneName);
		unset($this->CnTwoName);
		
		//高级分词，预先载入词典以提分词高速度
		$dicfile = dirname(__FILE__)."/dededic.csv"; 
		$fp = fopen($dicfile,'r');
		while($line = fgets($fp,256))
		{
			  $ws = explode(' ',$line);
			  $this->TagDic[$ws[0]] = $ws[1];
			  $this->RankDic[strlen($ws[0])][$ws[0]] = $ws[2];
		}
		fclose($fp);
	}
  
	//--------------------------
	//析放资源
	//--------------------------
	function Clear()
	{
		@fclose($this->QuickDic);
	}
  
	//----------------------------
	//设置源字符串
	//----------------------------
	function SetSource($str)
	{
		$str = $this->safe_string($str);
		$this->SourceString = trim($this->ReviseString($str));
		$this->ResultString = "";
	}
  
	//-----------------------------
	//检查字符串是否不存在中文
	//-----------------------------
	function NotGBK($str)
	{
		if($str=="") return "";
		//因为粗分的时候已经处理,因此不必要检查所的字符
		if( ord($str[0])>0x80 ) return false;
		else return true;
	}
	//-----------------------------
	//RMM分词算法
	//-----------------------------
	function SplitRMM($str="")
	{
		if($str != "") $this->SetSource(trim($str));
		if($this->SourceString=="") 
		{
			return "";
		}
		//对文本进行粗分
		$this->SourceString = $this->ReviseString($this->SourceString);
		//对特定文本进行分离
		$spwords = explode(" ",$this->SourceString);
		$spLen = count($spwords);
		$spc = $this->SplitChar;
		for($i=($spLen-1);$i>=0;$i--)
		{
			if(trim($spwords[$i])=="")
			{
				continue;
			}
			if($this->NotGBK($spwords[$i]))
			{
				if(ereg("[^0-9\.\+\-]",$spwords[$i]))
				{ 
					$this->ResultString = $spwords[$i].$spc.$this->ResultString; 
				}
				else
				{
					$nextword = "";
					@$nextword = substr($this->ResultString,0,strpos($this->ResultString," "));
					if(ereg("^".$this->CommonUnit,$nextword))
					{
						$this->ResultString = $spwords[$i].$this->ResultString;
					}
					else
					{
						$this->ResultString = $spwords[$i].$spc.$this->ResultString;
					}
				}
			}
			else
			{
				$c = $spwords[$i][0].$spwords[$i][1];
				$n = hexdec(bin2hex($c));
				if($c=="《") //书名
				{ 
					$this->ResultString = $spwords[$i].$spc.$this->ResultString;
				}
				else if($n>0xA13F && $n < 0xAA40) //标点符号
				{ 
					$this->ResultString = $spwords[$i].$spc.$this->ResultString; 
				}
				else //正常短句
				{
					if(strlen($spwords[$i]) <= $this->SplitLen)
					{
						//如果结束符为特殊分割词，分离处理
						if(ereg($this->EspecialChar."$",$spwords[$i],$regs))
						{
							$spwords[$i] = ereg_replace($regs[0]."$","",$spwords[$i]).$spc.$regs[0];
						}
						//是否为常用单位
						if(!ereg("^".$this->CommonUnit,$spwords[$i]) || $i==0)
						{
							$this->ResultString = $spwords[$i].$spc.$this->ResultString;
						}
						else
						{
							$this->ResultString = $spwords[$i-1].$spwords[$i].$spc.$this->ResultString; 
							$i--;
						}
					}
					else
					{ 
						$this->ResultString = $this->RunRMM($spwords[$i]).$spc.$this->ResultString;
					}
				}
			}
		}
		return $this->ResultString;
	}
	
	//对全中文字符串进行逆向匹配方式分解
	function RunRMM($str)
	{
		$spc = $this->SplitChar;
		$spLen = strlen($str);
		$rsStr = "";
		$okWord = "";
		$tmpWord = "";
		$WordArray = Array();
		//逆向字典匹配
		for($i=($spLen-1);$i>=0;)
		{
			//当i达到最小可能词的时候
			if($i<=$this->MinLen)
			{
				if($i==1)
				{
					$WordArray[] = substr($str,0,2);
					//echo "Min 1： ".substr($str,0,2)."<br>";
				}
				else
				{
					$w = substr($str,0,$this->MinLen+1);
					if($this->IsWord($w))
					{
						$WordArray[] = $w;                                                                                  
					}
					else
					{
						$WordArray[] = substr($str,2,2);
						$WordArray[] = substr($str,0,2);
						//echo "Min 2-2： ".substr($str,0,2).substr($str,2,2)."<br>";
					}
				}
				$i = -1; break;
			}
			//分析在最小词以上时的情况
			if($i>=$this->MaxLen) 
			{
				$maxPos = $this->MaxLen;
			}
			else 
			{
				$maxPos = $i;
			}
			$isMatch = false;
			for($j=$maxPos;$j>=0;$j=$j-2)
			{
				$w = substr($str,$i-$j,$j+1);
				if($this->IsWord($w))
				{
					$WordArray[] = $w;
					//echo "EG： ".$w." $str $i $j<br>";
					$i = $i-$j-1;
					$isMatch = true;
					break;
				}
			}
			if(!$isMatch)
			{
				if($i>1) 
				{
					$WordArray[] = $str[$i-1].$str[$i];
					//echo "NOT EG： ".$w."<br>";
					$i = $i-2;
				}
			}
		}//End For
		$rsStr = $this->ParOther($WordArray);
		return ($this->charset == 'gbk') ? $rsStr : @iconv('gbk', $this->charset, $rsStr);
	}
	
	//
	//进行名字识别和其它数词识别
	//
	function ParOther($WordArray)
	{
		$wlen = count($WordArray)-1;
		$rsStr = "";
		$spc = $this->SplitChar;
		for($i=$wlen;$i>=0;$i--)
		{
			//数量词
			if(ereg($this->CnSgNum,$WordArray[$i]))
			{
				$rsStr .= $spc.$WordArray[$i];
				if($i>0 && ereg("^".$this->CommonUnit,$WordArray[$i-1]))
				{ 
					$rsStr .= $WordArray[$i-1]; $i--; 
				}
				else
				{
					while($i>0 && ereg($this->CnSgNum,$WordArray[$i-1]))
					{ 
						$rsStr .= $WordArray[$i-1]; $i--; 
					}
				}
				continue;
			}
			//双字姓
			if(strlen($WordArray[$i])==4 && isset($this->TwoNameDic[$WordArray[$i]]))
			{
				$rsStr .= $spc.$WordArray[$i];
				if($i>0&&strlen($WordArray[$i-1])==2)
				{
					$rsStr .= $WordArray[$i-1];$i--;
					if($i>0&&strlen($WordArray[$i-1])==2)
					{ 
						$rsStr .= $WordArray[$i-1];$i--;
					}
				}
			}
			//单字姓
			else if(strlen($WordArray[$i])==2 && isset($this->OneNameDic[$WordArray[$i]]))
			{
				$rsStr .= $spc.$WordArray[$i];
				if($i>0&&strlen($WordArray[$i-1])==2)
				{
					 $rsStr .= $WordArray[$i-1];$i--;
					 if($i>0 && strlen($WordArray[$i-1])==2)
					 {
					 	$rsStr .= $WordArray[$i-1];$i--;
					 }
				}
			}
			//普通词汇
			else
			{
				$rsStr .= $spc.$WordArray[$i];
			}
		}
		//返回本段分词结果
		$rsStr = preg_replace("/^".$spc."/","",$rsStr);
		return $rsStr;
	}
	//---------------------------------
	//判断词典里是否存在某个词
	//---------------------------------
	function IsWord($okWord)
	{
		$slen = strlen($okWord);
		if($slen > $this->MaxLen) return false;
		else return isset($this->RankDic[$slen][$okWord]);
	}
	
	//------------------------------
	//整理字符串（对标点符号，中英文混排等初步处理）
	//------------------------------
	function ReviseString($str)
	{
		$spc = $this->SplitChar;
		$slen = strlen($str);
		if($slen==0) return '';
		$okstr = '';
		$prechar = 0; // 0-空白 1-英文 2-中文 3-符号
		for($i=0;$i<$slen;$i++)
		{
			if(ord($str[$i]) < 0x81)
			{
				//英文的空白符号
				if(ord($str[$i]) < 33)
				{
					if($prechar!=0&&$str[$i]!="\r"&&$str[$i]!="\n") $okstr .= $spc;
					$prechar=0;
					continue; 
				}
				else if(ereg("[^0-9a-zA-Z@\.%#:/\\&_-]",$str[$i]))
				{
					if($prechar==0)
					{
						$okstr .= $str[$i]; 
						$prechar=3;
					}
					else
					{ 
						$okstr .= $spc.$str[$i]; 
						$prechar=3;
					}
				}
				else
				{
					if($prechar==2||$prechar==3)
					{ 
						$okstr .= $spc.$str[$i]; 
						$prechar=1;
					}
					else
					{ 
						if(ereg("@#%:",$str[$i]))
						{ 
							$okstr .= $str[$i]; 
							$prechar=3; 
						}
						else 
						{ 
							$okstr .= $str[$i]; 
							$prechar=1; 
						}
					}
				}
			}
			else
			{
				//如果上一个字符为非中文和非空格，则加一个空格
				if($prechar!=0 && $prechar!=2) 
				{
					$okstr .= $spc;
				}
				//如果中文字符
				if(isset($str[$i+1]))
				{
					$c = $str[$i].$str[$i+1];
					
					if(ereg($this->CnNumber,$c))
					{ 
						$okstr .= $this->GetAlabNum($c); 
						$prechar = 2; 
						$i++; 
						continue; 
					}
					
					$n = hexdec(bin2hex($c));
					if($n>0xA13F && $n < 0xAA40)
					{
						if($c=="《")
						{
							if($prechar!=0) $okstr .= $spc." 《";
							else $okstr .= " 《";
							$prechar = 2;
						}
						else if($c=="》")
						{
							$okstr .= "》 ";
							$prechar = 3;
						}
						else
						{
							if($prechar!=0) $okstr .= $spc.$c;
							else $okstr .= $c;
							$prechar = 3; 
						}
					}
					else
					{
						$okstr .= $c;
						$prechar = 2;
					}
					$i++;
				}
			}//中文字符
		}//结束循环
		return $okstr;
	}
	
	//-----------------------------------------
	//尝试识别新词，字符串参数为已经分词处理的串
	//----------------------------------------
	function FindNewWord($spwords,$maxlen=6)
	{
		$okstr = '';
		$ws = explode(' ',$spwords);
		$newword = '';
		$nws = '';
		foreach($ws as $w)
		{
			$w = trim($w);
			if(strlen($w)==2 && !preg_match("/[0-9a-zA-Z]/",$w) && !preg_match("/".$this->NewWordLimit."/",$w) )
			{ 
				$newword .= " ".$w;
			}
			else
			{
				if($newword!="")
				{
					$nw = str_replace(' ','',$newword);
					if(strlen($nw)>2)
					{
						if(strlen($nw) <= $maxlen)
						{ 
							$okstr .= ' '.$nw; $nws[$nw] = 0; 
						}
						else 
						{
							$okstr .= ' '.$newword;
						}
					}
					else
					{ 
						$okstr .= ' '.$newword; 
					}
					$newword = '';
				}
				$okstr .= ' '.$w;
			}
		}
		
		if($newword!="") $okstr .= $newword;
		$okstr = preg_replace("/ {1,}/"," ",$okstr);
		if(is_array($nws))
		{
			$this->m_nws = $nws;
			foreach($nws as $k=>$w)
			{
				$w = "";
				for($i=0;$i<strlen($k);$i++)
				{
					if( ord($k[$i]) > 0x80 )
					{
						$w .= " ".$k[$i];
						if(isset($k[$i+1]))
						{ 
							$w .= $k[$i+1]; $i++;
						}
					}
					else
					{
						$w .= " ".$k[$i];
					}
					$w .= " ";
				}
				$w = preg_replace("/ {1,}/"," ",$w);
				$okstr = str_replace($w," ".$k." ",$okstr);
				$okstr = str_replace($k." "," ".$k." ",$okstr);
				$okstr = str_replace(" ".$k," ".$k." ",$okstr);
			}
		}
		return $okstr;
	}
	
	//----------------------------------------------
	//除去字串中的重复词，生成索引字符串，字符串参数为已经分词处理的串
	//--------------------------------------------------
	function GetIndexText($okstr, $ilen=-1)
	{
		if($okstr=="") return "";
		$ws = explode(" ",$okstr);
		$okstr = "";
		$wks = "";
		foreach($ws as $w)
		{
			$w = trim($w);
			//排除小于2的字符
			if(strlen($w)<2) continue;
			//排除数字或日期
			if(!ereg("[^0-9:-]",$w)) continue;
			if(strlen($w)==2&&ord($w[0])>0x80) continue;
			if(isset($wks[$w])) $wks[$w]++;
			else $wks[$w] = 1;
		}
		if(is_array($wks))
		{
			arsort($wks);
			if($ilen==-1)
			{ foreach($wks as $w=>$v) $okstr .= $w." "; }
			else
			{
				foreach($wks as $w=>$v)
				{
					if((strlen($okstr)+strlen($w)+1)<$ilen) 
					{
						$okstr .= $w." ";
					}
					else 
					{
						break;
					}
				}
			}
		}
		return trim($okstr);
	}
	
	//
	//把全角数字转为半角数字
	//
	function GetAlabNum($fnum)
	{
		$nums = array("０","１","２","３","４","５","６","７","８","９","＋","－","％","．");
		$fnums = "0123456789+-%.";
		for($i=0;$i<count($nums);$i++)
		{
			if($nums[$i]==$fnum) return $fnums[$i];
		}
		return $fnum;
	}
	
	function safe_string($string)
	{
		$s1 = iconv('utf-8', 'gbk', $string);
		$s2 = iconv('gbk', 'utf-8', $s1);
		if($s2 == $string)
		{
			$this->charset = 'utf-8';
			return $s1;
		}
		$this->charset = 'gbk';
		return $string;
	}
}
