<?php
	switch ($_GET['se']) {
		case 'SHSE':
			$ctt = mb_convert_encoding(file_get_contents('https://biz.sse.com.cn/sseportal/webapp/datapresent/SSEQueryStockInfoInitAct?reportName=BizCompStockInfoRpt&PRODUCTID=&PRODUCTJP=&PRODUCTNAME=&keyword=&CURSOR='.$_GET['cursor']), 'utf-8', 'gb2312');
			
			preg_match('/([\s\S]+)(\<\/head\>[\s\S]+)/', $ctt, $matches);
			
			echo $matches[1].'<script src="/shared/jss/common.js" type="text/javascript"></script><script src="/se/siphon/tkr/jss/cs.js" type="text/javascript"></script>'.$matches[2];
			
			break;
		case 'SZSE':
			echo file_get_contents('http://www.szse.cn/main/marketdata/jypz/colist/');
			
			break;
		default;
			die;
	}
?>