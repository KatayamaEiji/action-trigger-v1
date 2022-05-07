<?php
/*----------------------------
 アクション追加メニューダイアログ
----------------------------*/

if($reDispId != ""){
	$tmpDispId = $reDispId . "-" . $dispId;
}
else{
	$tmpDispId = $dispId;
}

?>
<div class="modal" id="actionAddMenuModal" tabindex="-1" role="dialog" aria-labelledby="actionAddMenuModal_title" 
aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="actionAddMenuModal_title">アクションメニュー</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<h3 id="actionAddMenuModal_actionTitle"></h3>

				<div class="menu_box">
				<button class='btn btn-success' onClick="
					location.href='<?php echo base_url() . "action_add/addActionData/" . $tmpDispId  ?>';
					return false;
					">
					アクション新規作成
				</button>

				</div>

				<div class="menu_box">
				<button class='btn btn-success' onClick="
					location.href='<?php echo base_url() . "actioncom_list/index/" . $tmpDispId ?>';
					return false;
					">
					共有アクションから検索して追加
				</button>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->