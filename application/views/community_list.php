<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
コミュニティ一覧画面
------------------------------------------------------------------------->
<head>
<!-- 共通Link,Scriptの読み込み -->
<?php $this->load->view('parts/parts_include');?>
<link rel="stylesheet" href="<?php echo base_url();?>styles/actionlog_list.css?<?php echo date('YmdHHmmss');?>" type="text/css" />

<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/actionlog_list_0480.css?<?php echo date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/actionlog_list_0768.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<!--<link rel="stylesheet" href="<?php echo base_url();?>styles/actionlog_list_1024.css?<?php echo date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

<script>
	$( function() {
		// 共通初期化処理を呼び出す
		actionTrigger_init('<?php echo $editDialogID; ?>','<?php validation_errors(); ?>');	

		<?php 
		if($deleteActionLogFlg){
			echo "feadMessage('アクションログ','削除しました。');";
		} ?>
	} );
	
	/**
	* アクションログ削除
	*/
	function deleteActionLog(actionLogId,actionTitle){
		if(!comConfirm(actionTitle + 'のアクションログを削除してよろしいですか？')){
			return false;
		}

		$('#mainform').attr('action', '<?php echo base_url();?>actionlog_list/actionLogDeleteValidation');

		$('#deleteActionLogId').val(actionLogId);

		$('#find_submit').click();
	}

	/**
	* アクションログ編集
	*/
	function editActionLogData(actionLogID){
		$('#editActionLogBtn' + actionLogID).click();
	}

	/**
	 * 月を移動する
	 */
	function moveMonthClick(stepMonth){
		var findYearMonth = $('#findYearMonth').val();

		var dt = new Date(findYearMonth + "/01");

		//1ヶ月前
		dt.setMonth(dt.getMonth() + stepMonth);

		var year = dt.getFullYear();
		var month = dt.getMonth()+1;

		month = ('0' + month).slice(-2);

		$('#findYearMonth').val(String(year) + '/' + month);

		$('#find_submit').click();
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

	<div class="result_area">
	<div class="result_data">

	<div class="list_area">
	<?php
	$rowno=1;
	$dateValue = "";
	if($communityRecordCount > 0):
		foreach ($communityRecords as $row): ?>
		<div>
			<?php echo $row["message"] ?>
		</div>
	<?php 
		$rowno++;
		endforeach;
	endif;
	?>
	</div>

	</div> <!-- result_data -->
	</div> <!-- result_area -->
</div>

<div id="fotter">
	<?php $this->load->view('parts/parts_fotter');?>
</div>
</div>



</body>
</html>