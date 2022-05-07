<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクショントリガー編集画面
------------------------------------------------------------------------->
<head>
<!-- 共通Link,Scriptの読み込み -->
<?php $this->load->view('parts/parts_include');?>
<link rel="stylesheet" href="<?php echo base_url();?>styles/action_trigger_edit.css?<?php echo date('YmdHHmmss');?>" type="text/css" />

<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/action_edit_0480.css?<?php echo date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/action_edit_0768.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/action_edit_1024.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

<script>
	$( function() {
		<?php 
		if($editCompleteFlg){
			echo "feadMessage('アクショントリガー','更新しました。');";
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
echo form_open("ActionTrigger_edit/updActionTriggerValidation");	//フォームを開く

$data = array(
	'name' => 'actionTriggerId',
	'type'  => 'hidden',
	'value'   => $actionTriggerId
);
echo form_input($data);
?>
<div class="base_input_area">
	<div class="input_item">
		<span>アクション名</span>
		<?php 
		$data = array(
			'name' => 'actionTitle',
			'type'  => 'hidden',
			'value'   => $actionTitle
		);
		echo form_input($data);
		?>
		<div  class="form-control" readonly>
			<?php echo $actionTitle ?>
		</div>
	</div> <!-- input_item -->

	<div class="input_item">
		<span>開始日</span>
		<?php 
		$data = array(
			'name' => 'kigenFrom',
			'type'  => 'hidden',
			'value'   => $kigenFrom
		);
		echo form_input($data);
		?>
		<div  class="form-control" readonly>
			<?php echo $kigenFrom ?>
		</div>
	</div> <!-- input_item -->

	<div class="input_item">
		<span>終了日</span>

		<?php 
		$data = array(
			'type'        => 'date',
			'id'          => 'kigenTo',
			'name'        => 'kigenTo',
			'class'       => 'form-control',
			'step'        => 1, // 1秒単位
			'value'       => $kigenTo
			);
			echo form_input($data);
			echo form_error('kigenTo');
		?>
	</div> <!-- input_item -->
</div> <!-- base_input_area -->

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