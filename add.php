
	<?php 
		if(function_exists('mysqli_connect')){
//			echo 'Mysqli ON!';
		}
        if($_POST['password']!='4444'){
            echo json_encode(["1",'添加操作不正确'],JSON_UNESCAPED_UNICODE);

            // 広がります　ひろがります　蔓延，拓宽 44 4444
            return;
        }
		$host = '127.0.0.1';
		$user = 'root';
		$pass = '';
		$link=mysqli_connect($host,$user,$pass) or die('Could not connect: ' . mysqli_error());
		mysqli_query($link,"set names 'utf8'");
        mysqli_query($link,"use japanese;");
        
        $sql = "insert into dict(word,pron,mean,classnum) value('".$_POST['word']."','".$_POST['pron']."','".$_POST['mean'].
"',".$_POST['classnum'].")";
//        echo $sql;
		$result=mysqli_query($link,$sql);
        
        if(!$result){
            echo json_encode(["1",mysqli_error($link)]);;
        }
        else{
            echo json_encode(["0"]);
        }
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
