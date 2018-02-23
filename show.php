<?php 
		if(function_exists('mysqli_connect')){
//			echo 'Mysqli ON!';
		}
		$host = '127.0.0.1';
		$user = 'root';
		$pass = '';
		$link=mysqli_connect($host,$user,$pass) or die('Could not connect: ' . mysql_error());
		mysqli_query($link,"set names 'utf8'");
        mysqli_query($link,"use japanese;");
        
        $words=array();
        $i=0;
        
        foreach($_POST['ends'] as $k=>$v){
            $sql = "select * from dict where end in('".$v."')";
            $result=mysqli_query($link,$sql);
                if ($result->num_rows > 0) {
                // 输出每行数据
                while($row = $result->fetch_assoc()) {
                    $words[$i]=$row;
                    $i++;
                }
		      } 
        }
        echo '['.$_POST['index'].',';
        echo json_encode($words,JSON_UNESCAPED_UNICODE).']';
	?>
    <?php
     /**
    * 不转义中文字符的 json 编码
    * @param array $arr 待编码数组
    * @return string
    */
    function json($arr) {
        $str = json_encode($arr);
        $search = "#\\\u([0-9a-f]+)#ie";
        $replace = "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))";
        return preg_replace($search, $replace, $str);
    }
?>
