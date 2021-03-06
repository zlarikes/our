<?php
require 'include/init.php';
//热销
$hotList = $db->queryArray("select * from f_foods where food_hot=1");

//分页
if(isset($_GET['type'])){
	$type = $_GET['type'];
	$rowCount = $db->queryNum("select * from f_foods where type=$type");
}
//记录总数
$rowCount = $db->queryNum("select * from f_foods");
//一个页面记录数
$pageSize = 8;
//总页数
$pageCount = ceil($rowCount/$pageSize);
//当前页
if(empty($_GET['page'])){
	$pageNow = 1;
}else{
	$pageNow = $_GET['page'];
}

$list = $db->queryArray("select * from f_foods limit ".($pageNow-1)*$pageSize.",$pageSize");
?> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title> </title>
	<link rel="stylesheet" type="text/css" href="css/base.css">
	<link rel="stylesheet" type="text/css" href="css/block.css">  
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="js/highcharts.js"></script>
	<script src="js/sales.js">  </script>
</head>
<body>
	<div class="menu">
		<ul>
			<li style="width: 200px;">
				<input style="float: left;width:150px" id="searchTxt" type="text" placeholder="请输入想查找的菜名">
				<input style="float: left;" type="button" id="search" value="搜索" onclick="Searchfood();">
			</li>
			<li id="1"><a href="javascript:void(0);">全 部</a></li>
			<li id="3"><a href="javascript:void(0);">甜 品</a></li>
			<li id="4"><a href="javascript:void(0);">饮 料</a></li>			
		</ul>
		<div class="clean"></div>
		<div class="main">
			<div id="div1"></div>
			<div class="page">
				<ul>
					<?php if($pageNow!=1)
					echo '<li><a href="menu.php?page=1">首页</a></li>';
					?>

					<?php if($pageNow<=1){}else{
						echo '<li><a href="menu.php?page='.($pageNow-1).'">上一页</a></li>';
					} ?>
					<?php if($pageNow>=$pageCount){}else{
						echo '<li><a href="menu.php?page='.($pageNow+1).'">下一页</a></li>';
					} ?>
					<?php if($pageNow!=$pageCount)
					echo "<li><a href='menu.php?page=$pageCount'>尾页</a></li>";
					?>
				</ul>
			</div>
		</div>
	</div>
	<script>
		$(function(){
		//初始化
		getHtml('0');

		$('ul>li').live('click',function(){

			if($(this).attr('id') == '1'){

				$('#div1').html('');
				getHtml('0');
			}

			if($(this).attr('id') == '2'){

				$('#div1').html('');

				getHtml('1');
			}
			if($(this).attr('id') == '3'){
				$('#div1').html('');
				getHtml('2');

			}
			if($(this).attr('id') == '4'){
				$('#div1').html('');
				getHtml('3');
			}
		});
		$('#bug').live('click',function(){
			var loginer = "<?php echo $_SESSION['userName']; ?>";
			//判断有没有登录，没有就不让购买。
			if(loginer == ''){
				alert('请登录~');
				location.href='login.php';
				exit();
			}
			$.ajax({
				type:'post',
				url:'book.php',
				data:{
					'id':$(this).attr('value')
				},
				success:function(){
					alert('加入购物车成功~');
				}
			});
		});

		//收藏
		$('#collect').live('click',function(){
			var loginer = "<?php echo $_SESSION['userName']; ?>";
			//判断有没有登录，没有就不让购买。
			if(loginer == ''){
				alert('请登录~');
				location.href='login.php';
				exit();
			}
			$.ajax({
				type:'post',
				url:'book.php?action=collect',
				data:{
					'id':$(this).attr('value')
				},
				success:function(res){
					alert('收藏成功~');
				}
			});
		});

		function getHtml(type){
			var html = '';
			$.ajax({
				type:"GET",
				url:'page.php',
				data:{'type':type,'page':<?php echo $pageNow ?>},
				cache:false,
				dataType:'json',
				success:function(res){
					$.each(res,function(k,v){
						html +='<dl>';
						html +="<dt><a href='detail_menu.php?img="+v['img']+
						"&food_name="+v['food_name']+ "&food_num="+v['food_num']+
						"&food_price="+v['food_price']+"'><img src='upload/"+v['img']+"'></a></dt>";
						html +="<dd style='width:80px'>"+v['food_name']+"</dd>";
						html +="<dd>"+v['food_price']+"元</dd>";
						html +="<dd><button id='bug' value='"+v['id']+"'>加入购物车</button></dd>";
						html +="<dd><button id='collect' value='"+v['id']+"'>收藏</button></dd>";
						html +='</dl>';
					});
					$('#div1').html(html);
				}
			});
		}
	});
</script>
<div class="rexiao">
	<p>热销榜</p>
	<ul border="1">
		<?php foreach($hotList as $k=>$v) { ?>
		<li>
			<a href="#"><img src="upload/<?php echo $v['img']; ?>"></a> 
		</li>
		<?php }
		?>
	</ul>
</div>
<!-- 销量图 -->
<!-- <div id="container" ></div> -->
</body>
</html>