<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
if(!$CI){
	$CI = new CI_Controller();
}
$CI->load->helper('url');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>ActionTrigger</title>

	<!------------------------------------
	bootstrap 4.1.3 ,Jquery
	-------------------------------------->
	<link rel="stylesheet" href="<?= base_url();?>bootstrap/css/bootstrap.min.css" >

	<script src="<?= base_url();?>bootstrap/js/jquery-3.3.1.min.js" ></script>
	<script src="<?= base_url();?>bootstrap/js/bootstrap.min.js"></script>

	<!------------------------------------
	その他 
	-------------------------------------->
	<!-- メニューなどのアイコン -->
	<link rel="stylesheet" href="<?= base_url() ?>node_modules/@fortawesome/fontawesome-free/css/all.min.css"> 

	<!------------------------------------
	共通 
	-------------------------------------->
	<link rel="stylesheet" href="<?= base_url();?>styles/com_base.css?<?= date('YmdHHmmss');?>" type="text/css" />
	<link rel="stylesheet" href="<?= base_url();?>styles/com_base_0480.css?<?= date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<link rel="stylesheet" href="<?= base_url();?>styles/com_base_0768.css?<?= date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<link rel="stylesheet" href="<?= base_url();?>styles/com_base_1024.css?<?= date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<link rel="icon" href="<?=base_url()?>images/favicon.ico" type="image/x-icon"> <!-- 64×64px -->
	<link rel="apple-touch-icon" href="<?=base_url()?>images/apple-touch-icon-180x180.png" sizes="180x180"> <!-- 180x180px -->

</head>
<body>

<div id="container" >
<div id="header">
	<!-- ヘッダー -->
	<div id="header_area" >
		<div id="title_item">
			<h1 onClick="location.href='<?= base_url() . "Main/login" ?>';return false">
					Action Friends Page
			</h1>
		</div>
	</div>
</div>
<div id="menu">
</div>
<div id="main" class="error_main">
	<div class="error_info">
		<h4>An uncaught Exception was encountered</h4>

		<p>Type: <?= get_class($exception); ?></p>
		<p>Message: <?= $message; ?></p>
		<p>Filename: <?= $exception->getFile(); ?></p>
		<p>Line Number: <?= $exception->getLine(); ?></p>

		<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

			<p>Backtrace:</p>
			<?php foreach ($exception->getTrace() as $error): ?>

				<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

					<p style="margin-left:10px">
					File: <?= $error['file']; ?><br />
					Line: <?= $error['line']; ?><br />
					Function: <?= $error['function']; ?>
					</p>
				<?php endif ?>

			<?php endforeach ?>

		<?php endif ?>
	</div>

	<div class="error_button">
	<button class='btn btn-primary' onClick="location.href='<?= base_url() . "main/login" ?>';return false">ログイン画面へ</button>
	</div>
</div>

<div id="fotter">
	<div class="Copyright">
	Copyright © 2018-2018 SakaeFactory All Rights Reserved.
	</div>
</div>

</div>

</body>
</html>