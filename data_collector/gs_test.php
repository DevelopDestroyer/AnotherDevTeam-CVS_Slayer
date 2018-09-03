<html>
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  </head>
  <body>
<?php
  include "Snoopy.class.php";

  $today = date("Ymd");
  $isHaveItem = false;
//leeth99.dothome.co.kr
  $connect = mysql_connect('localhost','leeth99','djskej11');
  $db = mysql_select_db('leeth99', $connect);
  $sql = "set names utf8";
  $result = mysql_query($sql, $connect);

  $sql = "select * from cvs_update_check where cvs_name='gs25' and upload_date='".$today."'";
  $result = mysql_query($sql, $connect);

  while($row = mysql_fetch_row($result)){
    $isHaveItem = true;
/*
    echo "<b>편의점명</b> : ".$row[1]."<br>";
    echo "<b>상품명</b> : ".$row[2]."<br>";
    echo "<b>가격</b> : ".$row[4]."<br><hr>";
*/

  }



    //$vars = array();
    $snoopy = new Snoopy();
    //$snoopy->httpmethod = "POST";
    $snoopy->fetch("http://gs25.gsretail.com/gscvs/ko/main");

    //echo $snoopy->results;
    $snoopy->setcookies();
    $snoopy->accept = "text/html;charset=UTF-8";

    //1. 긁어온 문서에서 노드를 찾아내는데 명확성을 부여할 수 있는 HTML부분만 가져옵니다.
  //$parse_html = splitBetweenStr($snoopy->results,"<div class=\"list_area daily_img\">","</ul>");
  //2. 그 내용을 기반으로 하나의 노드를 구분짓습니다.
  $parse_node = splitBetweenStr($snoopy->results,"/gscvs/ko/gsapi/getApiInfo?CSRFToken=","\"");
  echo "*** 토큰값 : ".$parse_node[0]."<br>";
  //echo $snoopy->results;
  echo "------------------------------------------------------<br>";
  //////////////////////////////////////////////////////////////////
  $vars2 = array(//84
    "pageNum"=>"1",
    "pageSize"=>"1000",
    "parameterList"=>"TOTAL"
    );
  //$snoopy = new Snoopy();
  $snoopy->httpmethod = "POST";
  $snoopy->submit("http://gs25.gsretail.com/gscvs/ko/products/event-goods-search?CSRFToken=".$parse_node[0], $vars2);

  $res = $snoopy->results;
  //echo "show me the result : ".$res."<br><br>";
  $res_array = json_decode($res, true);
  print_r($res_array);
  //$res = iconv("EUC-KR","UTF-8",$res);

  //echo "show me the result : ".$res;


function splitBetweenStr($str, $startWord, $endWord){
    for ($i=0, $len=strlen($str); $i<$len; $i++){
        $target = substr($str,$i);
        $prevStartIdx = strpos($target, $startWord);
        $startIdx = $prevStartIdx + strlen($startWord);
        $endIdx = strpos(substr($target, $startIdx), $endWord);
        if ($prevStartIdx===false || $endIdx===false){
             break;
        } else {
             $betweenStrings[] = substr($target, $startIdx, $endIdx);
             $i += $startIdx + $endIdx + strlen($endWord) - 1;
        }
    }
    return $betweenStrings;
}

?>
  </body>

</html>
