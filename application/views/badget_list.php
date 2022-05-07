<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
定期予算一覧画面
------------------------------------------------------------------------->
<head>
<!-- 共通Link,Scriptの読み込み -->
<?php $this->load->view('parts/parts_include');?>
<link rel="stylesheet" href="<?php echo base_url();?>styles/badget_list.css?<?php echo date('YmdHHmmss');?>" type="text/css" />

<link rel="stylesheet" href="<?php echo base_url();?>styles/badget_list_0480.css?<?php echo date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<link rel="stylesheet" href="<?php echo base_url();?>styles/badget_list_0768.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<link rel="stylesheet" href="<?php echo base_url();?>styles/badget_list_1024.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

<script>
	$( function() {
		// 共通初期化処理を呼び出す
		actionTrigger_init('<?php echo $editDialogID; ?>','<?php validation_errors(); ?>');	
	} );
	
	/**
	* 定期予算削除
	*/
	function deleteBadget(badgetID,badgetTitle){
		if(!window.confirm(badgetTitle + '<?php echo $currencyUnit;?>の定期予算を削除してよろしいですか？')){
			return false;
		}
		$('#deleteBadgetID').val(badgetID);

		$('#delete_submit').click();
	}

	/**
	* 定期予算編集
	*/
	function editBadgetData(badgetID){
		$('#editBadgetBtn' + badgetID).click();
	}
</script>

</head>
<body>

<div id="container">
<div id="header">
    <?php $this->load->view('parts/parts_header');?>
</div>

<div id="main">
	<div class="badget_area">
		<?php
		echo form_open("Badget_list/findList");	//フォームを開く
		?>

		<?php 
		$data = array(
			'type'        => 'text',
			'id'          => 'findBadgetTitle',
			'name'        => 'findBadgetTitle',
			'class'       => 'form-control',
			'value'       => $findBadgetTitle,
			'required'    => 'required'
		);
		echo form_input($data);
		?>

		<?php
			echo form_close();	//フォームを閉じる
		?>
	</div>
	<div class="spend_area">
	<?php
		if($spendRecords){
		?>
		<table>
		<tr>
			<th>日付</th>
			<th>支出</th>
			<th>メモ</th>
			<th></th>
			<th></th>
		</tr>
	<?php
	$rowno=1;
	$dateValue = "";
	if($spendRecordCount > 0):
		foreach ($spendRecords as $row): ?>
		<tr>
			<?php if($dateValue != $row['spend_date_str']):?>
			<td class="spend_date" rowspan="<?php echo $row['spend_cnt'];?>">
				<?php echo $row['spend_date_str'];
					$dateValue = $row['spend_date_str'];
				?>
			</td>
			<?php endif; ?>
			<td class="spend_money"><?php echo $row['spend_money_str'] ?></td>
			<td class="memo">
				<?php echo $row['spend_title'] ?>
				<div><?php echo $row['memo'] ?></div>
			</td>
			<td class="edit">
				<a href="#" onclick="editSpendData(<?php echo $row['spend_data_id'] ?>);return false;">
					<i class="far fa-edit"></i>
				</a>
				<button id="editSpendDataBtn<?php echo $row['spend_data_id']?>" class='btn btn-info' 
					data-toggle="modal" data-target="#spendDataEditDialog" style="display:none"
					data-badget_data_id="<?php echo $badgetDataId ?>" 
					data-spend_data_id="<?php echo $row['spend_data_id'] ?>"
					data-spend_modal_day="<?php echo $row['spend_date'] ?>"
					data-spend_money="<?php echo $row['spend_money'] ?>" 
					data-memo="<?php echo $row['memo'] ?>" >編集</button>
			</td>
			<td class="delete">
				<a href="#" onclick="deleteSpendData(<?php echo $row['spend_data_id'] ?>,<?php echo $row['spend_money'] ?>);return false;">
					<i class="far fa-trash-alt"></i>
				</a>
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
		<h3>※定期予算はありません。</h3>
	<?php
		}
	?>
	</div>
</div>

<div class="hidden_area">
<?php
echo form_open("Badget_list/badgetDeleteValidation");	//フォームを開く

$data = array(
	'name' => 'badgetDataID',
	'type'  => 'hidden',
	'value'   => $badgetId
);
echo form_input($data);

$data = array(
	'name' => 'deleteBadgetID',
	'id'  => 'deleteBadgetID',
	'type'  => 'hidden',
	'value'   => ''
);
echo form_input($data); 

echo form_submit("delete_submit", "削除","class='btn btn-primary' id='delete_submit'");
echo form_error('delete_submit');
echo form_close();	//フォームを閉じる
?>
</div>

<div id="fotter">
	<?php $this->load->view('parts/parts_fotter');?>
</div>
</div>



</body>
</html>