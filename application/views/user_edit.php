<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
ユーザー設定画面
------------------------------------------------------------------------->
<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include');?>

	<link rel="stylesheet" href="<?php echo base_url();?>styles/user_edit.css?<?php echo date('YmdHHmmss');?>" type="text/css" />

<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/action_edit_0480.css?<?php echo date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/action_edit_0768.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/action_edit_1024.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

<script>
	$( function() {
<?php 
		if($editCompleteFlg){
			echo "feadMessage('アクション','更新しました。');";
		} ?>
	});
</script>

</head>
<body>

<div id="container">
<div id="header">
	<?php $this->load->view('parts/parts_header');?>
</div>
<div id="menu">
<?php $this->load->view('parts/parts_menu',$partsMenu);?>
</div> <!-- #menu -->

<div id="main">
<div class="main_area">

<?php 

$attributes = array('autocomplete' => 'off');
echo form_open("user_edit/updUserValidation",$attributes);	//フォームを開く
?>
	<div class="base_input_area">
		<div class="input_item">
			<span>ログインＩＤ</span><span class="required" >●</span>
			<?php 
			$data = array(
				'type'        => 'input',
				'id'          => 'loginId',
				'name'        => 'loginId',
				'class'       => 'form-control',
				'value'       => $loginId
				);
				echo form_input($data);
				echo form_error('loginId');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>ユーザー名</span><span class="required" >●</span>
			<?php 
			$data = array(
				'type'        => 'input',
				'id'          => 'usName',
				'name'        => 'usName',
				'class'       => 'form-control',
				'value'       => $usName,
				'autocomplete' => 'off'
				);
				echo form_input($data);
				echo form_error('usName');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>メールアドレス</span>
			<?php 
			$data = array(
				'type'        => 'input',
				'id'          => 'mailAddress',
				'name'        => 'mailAddress',
				'class'       => 'form-control',
				'value'       => $mailAddress,
				'placeholder' => 'aaa@dddd.com'
				);
				echo form_input($data);
				echo form_error('mailAddress');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>パスワード</span><span class="required" >●</span>
			<?php 
			$data = array(
				'type'        => 'password',
				'id'          => 'password',
				'name'        => 'password',
				'class'       => 'form-control',
				'value'       => $password
				);
				echo form_input($data);
				echo form_error('password');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>パスワード（確認）</span>
			<?php 
			$data = array(
				'type'        => 'password',
				'id'          => 'passconf',
				'name'        => 'passconf',
				'class'       => 'form-control',
				'value'       => $passconf
				);
				echo form_input($data);
				echo form_error('passconf');
			?>
		</div> <!-- input_item -->

	</div> <!-- base_input_area -->

	<div class="sp_line">
		&nbsp;
	</div>

	<div class="base_input_area">
		<div class="input_item">
			<span>ミッション</span>
			<?php 
				$data = array(
				'type'        => 'text',
				'id'          => 'mission',
				'name'        => 'mission',
				'class'       => 'form-control',
				'value'       => $mission
				);
				echo form_input($data);
				echo form_error('mission');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>価値観１</span>
			<?php 
				$data = array(
				'type'        => 'text',
				'id'          => 'values01',
				'name'        => 'values01',
				'class'       => 'form-control',
				'value'       => $values01
				);
				echo form_input($data);
				echo form_error('values01');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>価値観２</span>
			<?php 
				$data = array(
				'type'        => 'text',
				'id'          => 'values02',
				'name'        => 'values02',
				'class'       => 'form-control',
				'value'       => $values02
				);
				echo form_input($data);
				echo form_error('values02');
			?>
		</div> <!-- input_item -->

		<div class="input_item">
			<span>価値観３</span>
			<?php 
				$data = array(
				'type'        => 'text',
				'id'          => 'values03',
				'name'        => 'values03',
				'class'       => 'form-control',
				'value'       => $values03
				);
				echo form_input($data);
				echo form_error('values03');
			?>
		</div> <!-- input_item -->
	</div>

	<div class="button_field_area">
		<div class="button_field ">
			<?php 
				echo form_submit("entry_submit", "更新","class='btn btn-primary'");
				echo form_error('entry_submit');
			?>
		</div> <!-- button_field -->
	</div> <!-- button_field_area -->
<?php
echo form_close();	//フォームを閉じる
?>

</div> <!-- main_area -->
</div> <!-- main -->

<div id="fotter">
	<?php $this->load->view('parts/parts_fotter');?>
</div> <!-- fotter -->
</div> <!-- container -->

</body>
</html>