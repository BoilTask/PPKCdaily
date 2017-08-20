<?php
require_once('include/db_info.inc.php');
if(!isset($_SESSION['administrator'])){
    exit(0);    
}
if(isset($_POST['title'])){
    if(!isset($_POST['year'])||!isset($_POST['month'])||!isset($_POST['day'])||!isset($_POST['title'])||!isset($_POST['oj'])||!isset($_POST['id'])||!isset($_POST['content'])||!isset($_POST['solution'])||!isset($_POST['code']))
        exit(0);
    $time=$_POST['year']."-".$_POST['month']."-".$_POST['day'];
    $title=$_POST['title'];
    $oj=$_POST['oj'];
    $id=$_POST['id'];
    $content=$_POST['content'];
    $solution=$_POST['solution'];
    $code=$_POST['code'];
    
    if (get_magic_quotes_gpc ()){
        $time=stripslashes($time);
        $title=stripslashes($title);
        $oj=stripslashes($oj);
        $id=stripslashes($id);
        $content=stripslashes($content);
        $solution=stripslashes($solution);
        $code=stripslashes($code);
    }
	$time=mysqli_real_escape_string($mysqli,$time);
	$title=mysqli_real_escape_string($mysqli,$title);
	$oj=mysqli_real_escape_string($mysqli,$oj);
	$id=mysqli_real_escape_string($mysqli,$id);
	$content=mysqli_real_escape_string($mysqli,$content);
	$solution=mysqli_real_escape_string($mysqli,$solution);
	$code=mysqli_real_escape_string($mysqli,$code);
	
	$sql="SELECT `time` FROM `daily` WHERE `time`='$time'";
	$result=mysqli_query($mysqli,$sql);
    if(mysqli_num_rows($result)>0)
        $sql="UPDATE `daily` set `oj`='$oj',`id`='$id',`title`='$title',`content`='$content',`solution`='$solution',`code`='$code' WHERE `time`='$time'";
    else
	    $sql="INSERT INTO `daily`(`time`, `oj`, `id`, `title`, `content`, `solution`, `code`) VALUES ('$time','$oj','$id','$title','$content','$solution','$code')";
	mysqli_query($mysqli,$sql) or die(mysqli_error());
	echo "success";
}else {
    $title="";
    $oj="";
    $id="";
    $content="";
    $solution="";
    $code="";
    $time="";
    if(isset($_GET['time'])){
        $time=$_GET['time'];
        $sql="select `time`,`title`,`oj`,`id`,`content`,`solution`,`code` from `daily` where `time`='$time'";
        $result=mysqli_query($mysqli,$sql);
        if(mysqli_num_rows($result)>0){
            $row=mysqli_fetch_object($result);
            $time=substr($row->time,0,10);
            $title=$row->title;
            $oj=$row->oj;
            $id=$row->id;
            $content=$row->content;
            $solution=$row->solution;
            $code=$row->code;
        }
    }
?>
<html lang="cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>每日一题 - 后台管理</title>

<link href="image/favicon.ico" rel="shortcut icon" />
 
<link rel="stylesheet" href="cssjs/bootstrap/css/bootstrap.min.css">

<style>
body {
	background-image:-webkit-linear-gradient(top,#7da2e8,#c3deee 30%,#f7fcff);
	background-image:-moz-linear-gradient(top,#7da2e8,#c3deee 30%,#f7fcff);
	background-image:-ms-linear-gradient(top,#7da2e8,#c3deee 30%,#f7fcff);
	background-image:linear-gradient(top,#7da2e8,#c3deee 30%,#f7fcff);
	background-attachment:fixed
}
/*
body{background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0.1,#cbfae1),color-stop(0.95,#fff));background-image:-moz-linear-gradient(center bottom,#cbfae1 10%,#fff 95%);background-attachment:fixed}.ui-widget{font-size:12px!important}

*/
</style>

</head>

<body>
<div class="container">
    <form method=POST action=admin.php>
        <h2 id='Basicinfo'>每日一题管理:</h2>
            <table class="table table-striped">
                <tr>
                    <td>Time:</td>
                    <td>
                        Year:<input type=text name=year value='<?php echo substr($time,0,4)?>'>
                        Month:<input type=text name=month value='<?php echo substr($time,5,2)?>'>
                        Day:<input type=text name=day value='<?php echo substr($time,8,2)?>'>
                    </td>
                </tr>
                <tr>
                	<td>Title:</td>
                	<td><input class=input-xxlarge  type=text name=title size=71 value='<?php echo $title?>'></td>
                </tr>
                
                </tr>
                <tr>
                	<td>OJ & ID:</td>
                	<td>
                	    OJ:<input class=input-mini  type=text name=oj value='<?php echo $oj?>'>
                        ID:<input class=input-mini  type=text name=id value='<?php echo $id?>'>
                	 </td>
            	</tr>
                <tr>
                <tr>
                    <td>Descriptions:</td>
                    <td>
            	        <textarea class='kindeditor' rows=13 name=content cols=80><?php echo htmlentities($content,ENT_QUOTES,"UTF-8")?></textarea>
                    </td>
            	</tr>
            	<tr>
            	    <td>Solution</td>
            	    <td>
            	        <textarea class='kindeditor' rows=13 name=solution cols=80><?php echo htmlentities($solution,ENT_QUOTES,"UTF-8")?></textarea>
            	        </td>
                </tr>
            	<tr>
            	    <td>Code:</td>
            	    <td>
            	        <textarea class='kindeditor' rows=13 name=code cols=80><?php echo htmlentities($code,ENT_QUOTES,"UTF-8")?></textarea>
            	        </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type=submit value=Submit name=submit></td>
                </tr>
        </table>
    </form>
</div>
  </body>
</html>

    
  <?php }?>