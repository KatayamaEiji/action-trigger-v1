<?php
/*----------------------------
 予算メニューダイアログ
----------------------------*/
$data = array(
	'type'         => 'hidden',
	'id'          => 'badgetMenuModal_badgetDataId',
	'name'          => 'badgetMenuModal_badgetDataId',
	'value'          => ''
);
echo form_input($data);
$data = array(
	'type'         => 'hidden',
	'id'          => 'badgetMenuModal_badgetId',
	'name'          => 'badgetMenuModal_badgetId',
	'value'          => ''
);
echo form_input($data);
?>

<div class="modal" id="badgetMenuModal" tabindex="-1" role="dialog" aria-labelledby="badgetMenuModal_title" 
aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="badgetMenuModal_title">予算メニュー</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<h3 id="badgetMenuModal_badgetTitle"></h3>

				<div class="menu_box">
				<button class='btn btn-success' onClick="
					var id = $('#badgetMenuModal_badgetDataId').val();
					location.href='<?php echo base_url() . "Spend_data_list/index/" ?>' + id;
					return false;
					">
				支出履歴
				</button>

				<button class='btn btn-success' onClick="
					var id = $('#badgetMenuModal_badgetDataId').val();
					location.href='<?php echo base_url() . "Spend_list/index/" ?>' + id;
					return false;
					">
				支出：定期設定の確認
				</button>
				</div>

				<div class="menu_box">
				<button class='btn btn-success' onClick="
					var id = $('#badgetMenuModal_badgetId').val();
					location.href='<?php echo base_url() . "Badget_edit/edit/" ?>' + id;
					return false;
					">
				予算：定期設定の編集
				</button>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->