<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
支出履歴画面
------------------------------------------------------------------------->
<head>
<!-- 共通Link,Scriptの読み込み -->
<?php $this->load->view('parts/parts_include');?>
<link rel="stylesheet" href="<?php echo base_url();?>styles/spend_data_list.css?<?php echo date('YmdHHmmss');?>" type="text/css" />

<link rel="stylesheet" href="<?php echo base_url();?>styles/spend_data_list_0480.css?<?php echo date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<link rel="stylesheet" href="<?php echo base_url();?>styles/spend_data_list_0768.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<link rel="stylesheet" href="<?php echo base_url();?>styles/spend_data_list_1024.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

<script>
	$( function() {
		// 共通初期化処理を呼び出す
		actionTrigger_init('<?php echo $editDialogID; ?>','<?php validation_errors(); ?>');	
	} );
	
	/**
		* 支出データ削除
		*/
	function deleteSpendData(spendDataID,spendMoney){
		if(!window.confirm(spendMoney + '<?php echo $currencyUnit;?>の支出を削除してよろしいですか？')){
			return false;
		}
		$('#deleteSpendDataID').val(spendDataID);

		$('#delete_submit').click();
	}

	/**
		* 支出データ編集
		*/
	function editSpendData(spendDataID){
		$('#editSpendDataBtn' + spendDataID).click();
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
		<div class="title_block">
			<?php echo $badgetTitle ?>
		</div>
		<div class="badget_block">
			<div class="badget_info_area">
				<table>
				<tr>
					<th>予算期間：</th>
					<td>
						<?php if($prevBadgetDataId != 0): ?>

						<a class="far fa-arrow-alt-circle-left" onClick="
							location.href='<?php echo base_url() . "Spend_data_list/index/" . $prevBadgetDataId ?>';
							return false;
							">
						</a>
						<?php endif; ?>

						<?php echo $badgetFromTo ?>

						<?php if($nextBadgetDataId != 0): ?>
						<a class="far fa-arrow-alt-circle-right" onClick="
							location.href='<?php echo base_url() . "Spend_data_list/index/" . $nextBadgetDataId ?>';
							return false;
							">
						</a>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th>予算（<?php echo $badgetLabel ?>）：</th><td><?php echo $badgetMoney ?>&nbsp;<?php echo $currencyUnit;?></td>
				</tr>
				<tr>
					<th>支出：</th><td><?php echo $spendSumMoney ?>&nbsp;<?php echo $currencyUnit;?></td>
				</tr>
				<tr>
					<th>予算（残）：</th><td><?php echo $badgetBalanceMoney ?>&nbsp;<?php echo $currencyUnit;?></td>
				</tr>
				</table>
			</div>

			<div class="operator_area">
				<button class='btn btn-info' data-toggle="modal" data-target="#spendDataEntryDialog" 
					data-badget_data_id="<?php echo $badgetDataId ?>"
					data-badget_now_str="<?php echo $nowDate ?>">支出登録</button>

				<button class='btn btn-info' data-toggle="modal" 
					data-target="#badgetDataEditDialog" 
					data-badget_data_id="<?php echo $badgetDataId ?>"
					data-badget_from_date="<?php echo $badgetFromDate ?>"
					data-badget_to_date="<?php echo $badgetToDate ?>"
					data-badget_money="<?php echo $badgetMoney ?>"
					data-memo="<?php echo $badgetMemo ?>">予算編集</button>
			</div>
		</div>
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
					data-memo="<?php echo $row['memo'] ?>" >支出編集</button>
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
		<h3>※支出はありません。</h3>
	<?php
		}
	?>
	</div>
</div>

<div class="hidden_area">
<?php
echo form_open("Spend_data_list/spendDataDeleteValidation");	//フォームを開く

$data = array(
	'name' => 'badgetDataID',
	'type'  => 'hidden',
	'value'   => $badgetDataId
);
echo form_input($data);

$data = array(
	'name' => 'deleteSpendDataID',
	'id'  => 'deleteSpendDataID',
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

<!-- Modal -->
<!-- 支出データ編集ダイアログ -->
<?php
echo form_open("Spend_data_list/spendDataEditValidation");	//フォームを開く

$dataBagetDataId = array(
	'name' => 'badgetDataID',
	'type'  => 'hidden',
	'value'   => $badgetDataId
);
echo form_input($dataBagetDataId);

$this->load->view('parts/parts_spend_data_edit_dlg',$spend_data_edit_items);
echo form_close();	//フォームを閉じる
?>

<!-- 支出データ登録ダイアログ -->
<?php
echo form_open("Spend_data_list/spendDataEntryValidation");	//フォームを開く
echo form_input($dataBagetDataId);

$this->load->view('parts/parts_spend_data_entry_dlg',$spend_data_entry_items);
echo form_close();	//フォームを閉じる
?>

<!-- 予算データ編集ダイアログ -->
<?php
echo form_open("Spend_data_list/badgetDataEditValidation");	//フォームを開く
echo form_input($dataBagetDataId);
$this->load->view('parts/parts_badget_data_edit_dlg',$badget_data_edit_items);
echo form_close();	//フォームを閉じる
?>


</body>
</html>