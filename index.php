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
	    if(in_array($id,$ac_problem['acRecords'][$oj]))
	        return 1;
	    $sql="SELECT count(*) FROM `vhoj`.`t_submission` WHERE `C_ORIGIN_OJ`='$oj' AND `C_ORIGIN_PROB`='$id' AND `C_STATUS_CANONICAL`='AC' AND `C_USERNAME`='".$_SESSION['user_id']."'";
    	$result=mysqli_query($mysqli,$sql);
    	$row=mysqli_fetch_array($result);
    	$ac=intval($row[0]);
    	mysqli_free_result($result);
    	if ($ac>0)
    	    return 1;
    	if(in_array($id,$ac_problem['failRecords'][$oj]))
    	    return -1;
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
$title="没有查询到记录！";
$oj="";
$id="";
$flag=0;
$content="";
$solution="";
$code="";
$time=date("Y-m-d");
if(isset($_GET['time']) && (strtotime($_GET['time'])<strtotime(date("Y-m-d")) || isset($_SESSION['administrator'])))
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
    $flag=problem_ac($oj,$id);
}

if(isset($_SESSION['administrator'])&&isset($_GET['show']))
    $time="1999-12-31";

?>
<html lang="cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php echo $title." - 每日一题 - ".$time;?></title>

<link href="image/favicon.ico" rel="shortcut icon" />

<link rel="stylesheet" href="cssjs/pace.css">

<link rel="stylesheet" href="cssjs/buttons.css">
 
<link rel="stylesheet" href="cssjs/bootstrap/css/bootstrap.min.css">

<style
>body{
    background:url(image/gbg.png);
}
</style>

</head>

<body>
<div class="container">
    <div class="container-fluid">
        <div class="row-fluid">
    		<div class="span12">
        		<ol class="breadcrumb">
        		  <li><a href="/">Home</a></li>
        		  <li><a href="?time=<?php echo $time; ?>">Daily - <?php echo $time; ?></a></li>
        		  <li class="active"><?php echo $title; ?></li>
        		</ol>
        		<?php if($time<date("Y-m-d")){ ?>
        		<div class="progress" >
                    <div id="runningbar" class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                        <a href='./' style='color:white'>点击回到本日题目</a>
                    </div>
                </div>
        		<?php }else{?>
        		<div class="progress" >
                    <div id="runningbar" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
                <?php }?>
        		<div class="panel panel-<?php
        		if($flag==1)
                    echo "success";
                else if($flag==-1)
                    echo "warning";
                else
                    echo "primary";
        		?>">
        		    <div class="panel-heading">
    					<h1><?php echo $title ?> <small><?php echo $oj.$id; ?></small></h1>
    				</div>
        			<div class="panel-body">
    				<?php
    				    if($title=="没有查询到记录！"){
    				        echo "<a href='./' class='btn btn-danger'>请点击返回今日题目</a>";
    				    }else{
    				        echo $content;
    				    }
    				
    				?>
    				</div>
    			</div>
    			<?php if($title!="没有查询到记录！"){ ?>
    			<div class="panel panel-warning">
    			    <div class="panel-heading">
    					<h1>Solution</h1>
    				</div>
        			<div class="panel-body">
    				<?php
    				if($time<date("Y-m-d") || date("H")>17)
                        echo $solution;
                    else{?>
                      <div id="solutioncountdown"></div>
    				<?php }?>
    				</div>
    			</div>
    			<?php }?>
    			<?php if($title!="没有查询到记录！"){ ?>
    			<div class="panel panel-success">
    			    <div class="panel-heading">
    					<h1>Code</h1>
    				</div>
        			<div class="panel-body">
        			    <?php if($time<date("Y-m-d")){?>
        			    <pre class="prettyprint linenums"><?php echo htmlspecialchars($code);?></pre>
                        <?php }else{?>
                        <div id="codecountdown"></div>
                        <?php }?>
    				</div>
    			</div>
    			<?php }?>
    		</div>
    	</div>
    </div>
    <hr>
    <div class="container">
    	<p style="float: left;" align="left">
    		<span id='nowdate'><?php echo date("Y-m-d H:i:s"); ?></span><br/><input type='hidden' id='time' name='time' value='<?php echo $time; ?>'>
    		<a class='btn' onclick="$('div.modal-body').html('<p>每日一题计划由河南理工大学ACM协会维护</p><ul><li>每日0点更新</li><li>下午6点提供题解</li><li>次日提供AC代码</li></ul><p>通过 ?time=(日期) 可以访问过往题目</p><p>如：<a href=\'?time=<?php echo date("Y-m-d")?>\'>http://acm.hpu.edu.cn/daily?time=<?php echo date("Y-m-d") ?></a></a></p>');$('div.modal-footer').html('<button type=\'button\' class=\'btn btn-default\' data-dismiss=\'modal\'>Close</button>');$('#Modal').modal('show');">About</a>   | <a class='btn' onclick="ajax_past();$('#Modal').modal('show');">Past</a>
    	</p>
    	<p style="float: right; margin-right: 15px;" align="right">
    		基于 <a href='https://github.com/BoilTask/PPKCdaily/' target="_blank">PPKCdaily</a><br/>
    		Copyright &copy; <a href='http://acm.hpu.edu.cn' target="_blank">HPUACM</a>
    	</p>
    </div>
</div>
<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">今天日期：<?php echo date("Y-m-d")?></h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                
            </div>
            </div>
    </div>
</div>
    <script>
    var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
    //alert(diff);
    function clock()
    {
    var x,h,m,s,n,xingqi,y,mon,d;
    var x = new Date(new Date().getTime()+diff);
    y = x.getYear()+1900;
    if (y>3000) y-=1900;
    mon = x.getMonth()+1;
    d = x.getDate();
    xingqi = x.getDay();
    h=x.getHours();
    m=x.getMinutes();
    s=x.getSeconds();
    n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
    //alert(n);
    document.getElementById('nowdate').innerHTML=n;
    setTimeout("clock()",1000);
    }
    clock();
    </script>
    <?php if($time==date("Y-m-d")){ ?>
    <script>  
    	var changebar = setInterval(function(){
    		var bar=document.getElementById("runningbar");
    		var s = new Date("<?php echo date("Y-m-d");?> 00:00:00");
    		var e = new Date("<?php echo date("Y-m-d");?> 24:00:00");
    		var n = new Date(new Date().getTime());
    		if (n>e)
    			bar.style.width = "100%";
    		else if (n<s)
    			bar.style.width = "0%";
    		else
    			bar.style.width =  (n.getTime()-s.getTime()) / (e.getTime()-s.getTime()) * 100 + "%";
    		var runningbarwidth=bar.style.width;
    			document.getElementById('runningbar').innerHTML=runningbarwidth;
    		}, 100);; 
    	window.onload = function(){ changebar;} 
    	
    </script>
    <?php } ?>

<script src="cssjs/jquery.min.js"></script>

<script src="cssjs/pace.js"></script>

<script src="cssjs/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="cssjs/shutdown/font.css" />
<link rel="stylesheet" href="cssjs/shutdown/jquery.countdown.css" />
<script src="cssjs/shutdown/jquery.countdown.js"></script>
<script>
$(function(){
    <?php if(date("H")<18){?>
	var st = new Date("<?php echo date("Y-m-d");?> 18:00:00"); 
	$('#solutioncountdown').countdown({
		timestamp : st,
		callback : function(days, hours, minutes, seconds){
		    if(days==0&&hours==0&&minutes==0&&seconds==0)
		      //  location.reload();
		      location.href='http://acm.hpu.edu.cn/daily/';
		}
	});
	<?php }?>
	var ct = new Date("<?php echo date_format(date_add(date_create(date("Y-m-d H:i:s")),date_interval_create_from_date_string("1 days")),"Y-m-d");?> 00:00:00"); 
	$('#codecountdown').countdown({
		timestamp : ct,
		callback : function(days, hours, minutes, seconds){
		    if(days==0&&hours==0&&minutes==0&&seconds==0)
		      //  location.reload();
		      location.href='http://acm.hpu.edu.cn/daily/';
		}
	});
});
function ajax_past(op=0){
    var newDate = new Date($('#time').val());
    var newTime = newDate.getTime()+op*24*60*60*1000;
    var nowTime = new Date().getTime();
    $('div.modal-footer').html('');
    if(newTime <= 1502496000000)
        newTime=1502496000000;
    else
         $('div.modal-footer').html($('div.modal-footer').html()+'<button type=\'button\' class=\'btn btn-default\' onclick=\'ajax_past(-15);\'>Prev</button>');
    if(newTime >= nowTime)
        newTime=nowTime;
    else
        $('div.modal-footer').html($('div.modal-footer').html()+'<button type=\'button\' class=\'btn btn-default\' onclick=\'ajax_past(15);\'>Next</button>');
    $('div.modal-footer').html($('div.modal-footer').html()+'<button type=\'button\' class=\'btn btn-default\' data-dismiss=\'modal\'>Close</button>');
    newDate.setTime(newTime);
    $('#time').val(newDate.getFullYear() + '-' + (newDate.getMonth()+1) + '-' + newDate.getDate());
    $.post('past.php', {
        time: $('#time').val()
    },
    function(show_data) {
        $('div.modal-body').html(show_data)
    })
}
</script>
<link href="cssjs/prettify.css" rel="stylesheet">
<script src="cssjs/prettify.js"></script>
<script>prettyPrint();</script>
<script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
<script type="text/x-mathjax-config">
MathJax.Hub.Config({
  tex2jax: {
    inlineMath: [['$','$']],
    displayMath: [['$$','$$']],
    processEscapes: true,
    processEnvironments: true,
    skipTags: ['script', 'noscript', 'style', 'textarea', 'pre','code'],
    TeX: { equationNumbers: { autoNumber: "AMS" },
         extensions: ["AMSmath.js", "AMSsymbols.js"] }
  }
});
</script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Queue(function() {
    var all = MathJax.Hub.getAllJax(), i;
    for(i = 0; i < all.length; i += 1) {
        all[i].SourceElement().parentNode.className += ' has-jax';
    }
});
</script>
  </body>
</html>
