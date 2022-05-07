<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
未ログイン画面
------------------------------------------------------------------------->
<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include');?>
	<link rel="stylesheet" href="<?php echo base_url();?>styles/login.css?<?php echo date('YmdHHmmss');?>" type="text/css" />
</head>
<body>

<div id="container" >
<div id="header">
	<?php $this->load->view('parts/parts_header_');?>
</div>

<div id="menu">
</div> <!-- #menu -->

<div id="main">
	<div class="error_info">
	<h1>ログインされていません。</h1>
	
	</div>


	<div class="error_button">
	<button class='btn btn-primary' onClick="location.href='<?php echo base_url() . "main/login" ?>';return false">ログイン画面に移動</button>
	</div>
</div>

<div id="fotter">
<?php $this->load->view('parts/parts_fotter');?>
</div>
</div>

</body>
</html>