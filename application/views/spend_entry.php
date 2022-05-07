<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
支出登録画面
------------------------------------------------------------------------->
<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include');?>
	<link rel="stylesheet" href="<?php echo base_url();?>styles/top.css?<?php echo date('YmdHHmmss');?>" type="text/css" />
</head>
<body>

<div id="container">
<div id="header">
    <?php $this->load->view('parts/parts_header');?>
</div>

<div id="main">

</div>

<div id="fotter">
	<?php $this->load->view('parts/parts_fotter');?>
</div>
</div>

</body>
</html>