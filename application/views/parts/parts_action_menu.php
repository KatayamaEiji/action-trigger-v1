<?php
/*----------------------------
 アクションメニューダイアログ
----------------------------*/
$data = array(
	'type'         => 'hidden',
	'id'          => 'actionMenuModal_actionId',
	'name'          => 'actionMenuModal_actionId',
	'value'          => ''
);
echo form_input($data);
$data = array(
	'type'         => 'hidden',
	'id'          => 'actionMenuModal_actionTriggerId',
	'name'          => 'actionMenuModal_actionTriggerId',
	'value'          => ''
);
echo form_input($data);

$data = array(
	'type'         => 'hidden',
	'id'          => 'actionMenuModal_dispId',
	'name'          => 'actionMenuModal_dispId',
	'value'          => ''
);
echo form_input($data);

if($reDispId != ""){
	$tmpDispId = $reDispId . "-" . $dispId;
}
else{
	$tmpDispId = $dispId;
}

?>

<script>
	/*
	 * アクション実行
	 */
	function actionMenuRun() {
		var actionId = $('#actionMenuModal_actionId').val();
		var actionTriggerId = $('#actionMenuModal_actionTriggerId').val();
		var dispId = '<?= $tmpDispId ?>';

		if (actionTriggerId == "") {
			actionTriggerId = 0;
		}

		window.location.href = '<?php echo base_url(); ?>action_run/index/' + dispId + '/' + actionId + '/' + actionTriggerId + '/1';
	}

	/*
	 * アクション確認
	 */
	function actionMenuActionConf() {
		var actionId = $('#actionMenuModal_actionId').val();
		var actionTriggerId = $('#actionMenuModal_actionTriggerId').val();
		var dispId = '<?= $tmpDispId ?>';

		if (actionTriggerId == "") {
			actionTriggerId = 0;
		}

		window.location.href = '<?php echo base_url(); ?>action_run/index/' + dispId + '/' + actionId + '/' + actionTriggerId + '/0';
	}

	/*
	 * アクション実行履歴
	 */
	function actionMenuActionLog() {
		var actionId = $('#actionMenuModal_actionId').val();
		var dispId = '<?= $tmpDispId ?>';

		window.location.href = '<?php echo base_url(); ?>actionlog_list/actionIdFilter/' + dispId + '/' + actionId;
	}

	/**
	 * アクショントリガー：今日のアクションを削除
	 */
	function delNowActionTrigger() {
		var actionTriggerId = $('#actionMenuModal_actionTriggerId').val();
		var dispId = '<?= $tmpDispId ?>';

		location.href = '<?php echo base_url() . "action_list/delNowActionTrigger/" ?>' + dispId + '/' + actionTriggerId;
	}

	/**
	 * アクショントリガー：今日のアクションに追加
	 */
	function addNowActionTrigger() {
		var actionId = $('#actionMenuModal_actionId').val();

		location.href = '<?php echo base_url() . "action_list/addNowActionTrigger/" ?>' + actionId;
	}
</script>

<div class="modal" id="actionMenuModal" tabindex="-1" role="dialog" aria-labelledby="actionMenuModal_title" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="actionMenuModal_title">アクションメニュー</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<h3 id="actionMenuModal_actionTitle"></h3>

				<div class="menu_box" id="actionMenu_actionConf">
					<button class='btn btn-primary' onClick="actionMenuActionConf();return false;">
						アクション確認
					</button>
				</div>

				<div class="menu_box" id="actionMenu_actionLog">
					<button class='btn btn-info' onClick="
					actionMenuActionLog();
					return false;
					">
						アクション履歴
					</button>
				</div>

				<div class="menu_box" id="actionMenu_actionEdit">
					<button class='btn btn-info' onClick="
						var id = $('#actionMenuModal_actionId').val();
						var triggerId = $('#actionMenuModal_actionTriggerId').val();
						location.href='<?php echo base_url() . "action_edit/editActionData/" . $tmpDispId . "/" ?>' + id + '/' + triggerId;
						return false;
						">
						アクション編集
					</button>
				</div>

				<div class="menu_box" id="actionMenu_actionTriggerEdit">
					<button class='btn btn-info' onClick="
						var triggerId = $('#actionMenuModal_actionTriggerId').val();
						location.href='<?php echo base_url() . "actiontrigger_edit/index/" . $tmpDispId . "/" ?>' + triggerId;
						return false;
						">
						アクショントリガー編集
					</button>
				</div>

				<div class="menu_box" id="actionMenu_delNowAction">
					<button class='btn btn-primary' onClick="delNowActionTrigger();return false;">
						今日のアクションから削除
					</button>
				</div>

				<div class="menu_box" id="actionMenu_addNowAction">
					<button class='btn btn-success' onClick="addNowActionTrigger();return false;">
						今日のアクションに追加
					</button>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->