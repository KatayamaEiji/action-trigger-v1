<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
共有アクションリスト画面
------------------------------------------------------------------------->
<head>
<!-- 共通Link,Scriptの読み込み -->
<?php $this->load->view('parts/parts_include');?>
<link rel="stylesheet" href="<?php echo base_url();?>styles/actioncom_list.css?<?php echo date('YmdHHmmss');?>" type="text/css" />


<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/actioncom_list_0480.css?<?php echo date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/actioncom_list_0768.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/actioncom_list_1024.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

<script>
	$( function() {
		// 共通初期化処理を呼び出す
		actionTrigger_init('','<?php validation_errors(); ?>');	

		// アクションメニュー
		$('#actionMenuModal').on('show.bs.modal', actionMenuModal);
	} );
	
	/**
	 * 共有アクションを追加
	 */
	function addActionCom(actionId){
		if(comConfirm("アクションを追加してよろしいですか？")){

			$('#mainform').attr('action', '<?php echo base_url();?>actioncom_list/addActionComValidation');

			$('#addActionId').val(actionID);

			$('#find_submit').click();
		}
	}
</script>

</head>
<body>

<div id="container">
<div id="header">
    <?php $this->load->view('parts/parts_header');?>
</div>

<div id="menu">
<?php $this->load->view('parts/parts_menu',$partsMenu);?>
</div>

<div id="main">
<?php
	$attributes = array('id' => 'mainform');
	echo form_open("actioncom_list/findList",$attributes);	//フォームを開く
	$data = array(
		'type'        => 'hidden',
		'id'          => 'reDispId',
		'name'        => 'reDispId',
		'value'       => $reDispId
	);
	echo form_input($data);
?>
	<div class="find_area">
	検索条件：
	<div class="find_input input-group mb-3">
		<?php 
		$data = array(
			'type'        => 'text',
			'id'          => 'findActionTitle',
			'name'        => 'findActionTitle',
			'class'       => 'form-control',
			'value'       => $findActionInfo['actionTitle'],
			'placeholder' => 'ここに検索するアクション名を入力して、検索ボタンをクリックして下さい。'
			);
			echo form_input($data);
		?>
		<div class="input-group-append">
		<?php
			echo form_submit("find_submit", "検索","id='find_submit' class='btn btn-primary btn-outline-secondary'");
		?>
		</div>
	</div><!-- find_input -->
	<?php
	echo form_error('find_submit');

	// 削除用ActionId
	$data = array(
		'name' => 'addActionId',
		'id'  => 'addActionId',
		'type'  => 'hidden',
		'value'   => ''
	);
	echo form_input($data); 
			
	?>
	</div>

	<div class="result_area"><div class="result_data">


	<div class="list_area">

	<?php
		if($actionRecords){
		?>
		<table>
		<tr>
			<th colspan="2">アクション</th>
			<th>追加</th>
			<th></th>
		</tr>
	<?php
	$rowno=1;
	$dateValue = "";
	if($actionRecordCount > 0):
		foreach ($actionRecords as $row):
			$actionTypeZero = str_pad($row['action_type'], 2, 0, STR_PAD_LEFT);

			$actionTriggerId = $row['action_trigger_id'];
			if($actionTriggerId == ""){
				$actionTriggerId = "0";
			}
		?>
		<tr>
			<td>
				<img class="action_type" 
					src="<?php echo base_url() . "images/com_action_type_" . $actionTypeZero . "_on.png" ?>" />
			</td>
			<td >
				<span>
					<?php echo $row['action_title'] ?>
				</span>
				<?php if($row['add_action_flg']): ?>
					<span class="add_action">追加済み</span>
				<?php endif; ?>
			</td>
			<td>
			<?php if($row['add_action_flg']): ?>
				-
			<?php else: ?>
				<button class='btn btn-primary btn-sm' 
				onClick="addActionCom(<?php echo $row['action_id'] ?>);return false;">
				<i class="fas fa-plus"></i>
				</button>
			<?php endif ?>
			</td>

			<td class="menu">
				<span class="far fa-caret-square-down" data-toggle="modal" data-target="#actionMenuModal"
				data-action_id="<?php echo $row['action_id'] ?>"
				data-action_trigger_id="<?php echo $actionTriggerId ?>"
				data-action_title="<?php echo $row['action_title'] ?>"
				data-add_action_flg="<?php echo $row['add_action_flg'] ?>"
				data-create_flg="<?php echo $row['create_flg'] ?>"
				data-disp_id="<?php echo $dispId ?>" ></span>
			</td>

		</tr>
	<?php 
		$rowno++;
		endforeach;
	endif;
	?>
		</table>
	<?php
		}
		else{
	?>
		<h3>※アクションはありません。</h3>
	<?php
		}
	?>
	</div> <!-- list_area -->
	</div> <!-- result_data -->
	</div> <!-- result_area -->
<?php 
	echo form_close();	//フォームを閉じる
?>
</div> <!-- main -->


<!-- メニューダイアログ -->
<?php
$this->load->view('parts/parts_action_menu',$actionMenuItems);
?>

<div id="fotter">
	<?php $this->load->view('parts/parts_fotter');?>
</div>
</div>



</body>
</html>