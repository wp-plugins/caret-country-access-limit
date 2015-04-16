<?php

class BaseClass
{
	function strToArray($str = null, $delimiter = ',')
	{
		return @explode($delimiter, $str);
	}

	function arrayToStr($data = array(), $delimiter = ' ')
	{
		return @implode($delimiter, $data);
	}

	function isExists($file)
	{
		return @file_exists($file);
	}

	function makeMethodStr($str)
	{
		return $this->arrayToStr($this->strToArray($str));
	}

	function fileCopy($from = null, $to = null)
	{
		return @copy($from, $to);
	}

	function fileRename($from = null, $to = null)
	{
		return @rename($from, $to);
	}

	function setupSreplace($str)
	{
		$strAfter = trim(preg_replace("/\s+/", ",", strtoupper($str)), ",");
		return $strAfter;
	}

	function setupReplace($str, $before = array(), $after = array())
	{
		return @str_replace($before, $after, $str);
	}

	function isInt($str)
	{
		return @is_numeric($str);
	}

	function isNotNull($str)
	{
		$str = trim($str);
		return (@strlen($str) > 0 ? true : false);
	}

	function makeCidrList($data = array(), $type = 0, $method_list = null, $extra_list = null)
	{
		$line  = '#COUNTRY_LIMIT_____#'.PHP_EOL;
		$line .= '<Limit '.$this->makeMethodStr($method_list).'>'.PHP_EOL;

		if ($type == 0) {
			$line .= "\tOrder Deny,Allow".PHP_EOL;
			$line .= "\tDeny from all".PHP_EOL;
			$line .= "\tAllow from 127.0.0.1".PHP_EOL;
			$line .= "\tAllow from 10.0.0.0/8".PHP_EOL;
			$line .= "\tAllow from 192.168.0.0/16".PHP_EOL;
			$line .= "\tAllow from 172.16.0.0/12".PHP_EOL;
			$limit = "\tAllow from ";
		} else {
			$line .= "\tOrder Allow,Deny".PHP_EOL;
			$line .= "\tAllow from all".PHP_EOL;
			$limit = "\tDeny from ";
		}

		if ($this->isNotNull($extra_list)) {
			foreach ((array)$this->strToArray($extra_list) as $val) {
				if (!$this->isNotNull($val)) continue;
				$line .= $limit.$val.PHP_EOL;
			}
		}

		foreach ((array)$data as $val) {
			$line .= $limit.$val.PHP_EOL;
		}

		$line .= '</Limit>'.PHP_EOL;
		$line .= '#_____COUNTRY_LIMIT#'.PHP_EOL;

		return $line;
	}
}

?>