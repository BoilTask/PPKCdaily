<?php
require_once('include/db_info.inc.php');
function problem_ac($oj,$id){
	if(!isset($_SESSION['user_id']))
	    return 0;
	$mysqli=$GLOBALS['mysqli'];
	if($oj=="LOCAL"){
    	$sql="SELECT count(*) FROM `solution` WHERE `problem_id`='$id' AND `result`='4' AND `user_id`='".$_SESSION['user_id']."'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	$ac=intval($row[0]);
    	mysqli_free_result($result);
    	if ($ac>0) return 1;
    	$sql="SELECT count(*) FROM `solution` WHERE `problem_id`='$id' AND `user_id`='".$_SESSION['user_id']."'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	$sub=intval($row[0]);
    	mysqli_free_result($result);
    	if ($sub>0) return -1;
    	else return 0;
	}else{

	    $sql="SELECT count(*) FROM `vhoj`.`t_submission` WHERE `C_ORIGIN_OJ`='$oj' AND `C_ORIGIN_PROB`='$id' AND `C_STATUS_CANONICAL`='AC' AND `C_USERNAME`='".$_SESSION['user_id']."'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	$ac=intval($row[0]);
    	mysqli_free_result($result);
    	if ($ac>0)
    	    return 1;
    	$sql="SELECT count(*) FROM `vhoj`.`t_submission` WHERE `C_ORIGIN_OJ`='$oj' AND `C_ORIGIN_PROB`='$id' AND `C_USERNAME`='".$_SESSION['user_id']."'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	$sub=intval($row[0]);
    	mysqli_free_result($result);
    	if($sub>0)
    	    return -1;
        else
            return 0;
	    
	}
}
function problem_ac_num($oj,$id){
	$mysqli=$GLOBALS['mysqli'];
	if($oj=="LOCAL"){
    	$sql="SELECT count(DISTINCT `user_id`) FROM `solution` WHERE `problem_id`='$id' AND `result`='4'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	mysqli_free_result($result);
        return $row[0];
	}else{
	    $sql="SELECT count(DISTINCT `C_USERNAME`) FROM `vhoj`.`t_submission` WHERE `C_ORIGIN_OJ`='$oj' AND `C_ORIGIN_PROB`='$id' AND `C_STATUS_CANONICAL`='AC'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	mysqli_free_result($result);
        return $row[0];
	}
}

$time=date("Y-m-d");
if(isset($_POST['time']) && (strtotime($_POST['time'])<strtotime(date("Y-m-d")) || isset($_SESSION['administrator'])))
    $time=$_POST['time'];
?>
<div class="list-group">
<?php
    $sql="SELECT `oj`,`id`,`title`,`time` from `daily` WHERE `time`<='".$time."' ORDER BY `time` DESC LIMIT 15";
    $first=true;
	$result=mysqli_query($mysqli,$sql);
	while($row=mysqli_fetch_object($result)){
	    $pro_time=substr($row->time,0,10);
	    $pro_oj=$row->oj;
        $pro_id=$row->id;
        $pro_des=$row->title;
        $flag=problem_ac($pro_oj,$pro_id);
        $cnt=problem_ac_num($pro_oj,$pro_id);
	    if($pro_time==date("Y-m-d")){
	        echo "<a href='./daily' class='list-group-item active";
	       $first=false;
	    }else{
	        echo "<a href='./daily?time=".$pro_time."' class='list-group-item";
	    }
	    if($flag==1)
        echo " list-group-item-success";
        else if($flag==-1)
            echo " list-group-item-danger";
	    echo "'>";
	    echo "<span class='hidden-xs'>$pro_time&nbsp;&nbsp;> > >&nbsp;&nbsp;</span>";
	    echo $pro_des;
	    echo "<span class='badge'>$cnt</span></a>";
    }
?>
</div>