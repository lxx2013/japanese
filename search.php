<?php 
		if(function_exists('mysqli_connect')){
//			echo 'Mysqli ON!';
		}
		$host = '127.0.0.1';
		$user = 'root';
		$pass = '';
		$link=mysqli_connect($host,$user,$pass) or die('Could not connect: ' . mysql_error());
		mysqli_query($link,"set character set 'utf8'");
		//mysqli_query($link,"set names 'utf8'");
        mysqli_query($link,"use japanese;");
        
        $words=array();
        $i=0;
        
        foreach($_POST['inputs'] as $k=>$v){
            $sql = "select * from dict where word like '%".$v."%' order by classnum";
            $result=mysqli_query($link,$sql);
                if ($result->num_rows > 0) {
                // 输出每行数据
                while($row = $result->fetch_assoc()) {
                    $words[$i]=$row;
			//var_dump($row);
			//echo json($row);
			//echo PHP_EOL;
			//echo json_encode($row,JSON_UNESCAPED_UNICODE);
			//echo PHP_EOL;
                    $i++;
                }
		      } 
        }
        echo '[0,';
        echo json_encode($words,JSON_UNESCAPED_UNICODE).']';
	?>
