<?php

	error_reporting(E_ERROR | E_PARSE);
  header('content-type: text/html; charset=utf-8'); 
  // 데이터베이스 접속 문자열. (db위치, 유저 이름, 비밀번호, db선택)
  $con=mysqli_connect( "localhost", "root", "tjrgml","studyroom") or die( "SQL server에 연결할 수 없습니다.");
 
  mysqli_set_charset($con,"utf8");
  
 
  // 세션 시작
  session_start();
  
  
  $id = isset($_POST['u_id']) ? $_POST['u_id']: '';
  //$pw = isset($_POST['u_pw']) ? $_POST['u_pw']: '';
  
  $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");


if ($id != "" ){ 

    $sql="select STU_NUM, ROOM_NUM, R_DATE, START_TIME, END_TIME, DELEGATOR, AT_CHECK
	from reservation
	where stu_num='$id' ORDER BY R_DATE DESC";
    //$stmt = $con->prepare($sql);
    //$stmt->execute();
	$result = mysqli_query($con,$sql);
	 // result of sql query	
	  if($result)
	  {		
		if($result->num_rows == 0) // sql결과가 없는, row가 0일때 -> 아이디가 없어 cmp자체가 안됨
		{
		  echo "10";//"Can not find ID";
		}
		else
		{
		  $data = array();	  
		  
		  while($row=mysqli_fetch_array($result)){

        	extract($row);

            array_push($data, 
                array('stu_num'=>$row[STU_NUM],
				'room_num'=>$row[ROOM_NUM],
				'r_date'=>$row[R_DATE],
				'start_time'=>$row[START_TIME],
				'end_time'=>$row[END_TIME],
				'delegator'=>$row[DELEGATOR],
				'at_check'=>$row[AT_CHECK]
            ));
			}
			
			if (!$android) {
            echo "<pre>"; 
            print_r($data); 
            echo '</pre>';
			}else
			{
            header('Content-Type: application/json; charset=utf8');
            $json = json_encode(array("webnautes"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
            echo $json;
			}
		}
	  }
	  else
	  {
	   echo mysqli_errno($con);
	  }	
}
else {
    //echo "검색할 학번을 입력하세요 ";
}

?>



<?php

$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

if (!$android){
?>

<html>
   <body>
   
      <form action="<?php $_PHP_SELF ?>" method="POST">
         학번: <input type = "text" name = "u_id" />         
         <input type = "submit" />
      </form>
   
   </body>
</html>
<?php
}

   
?> 
  