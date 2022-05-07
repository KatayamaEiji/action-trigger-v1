
<!-- 設定メニューダイアログ -->
<div class="modal" id="settingMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">設定メニュー</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				
				<!-- 予算 --->
				<div class="menu_box">
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "badget_data_list/index" ?>';return false">
				予算リスト
				</button>
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "badget_list/index" ?>';return false">
				予算：定期設定の確認
				</button>
				</div>

				<!-- 支出 --->
				<div class="menu_box">
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "badget_data_list/index" ?>';return false">
				支出リスト
				</button>
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "badget_list/index" ?>';return false">
				支出：定期設定の確認
				</button>
				</div>

				<!-- 収入 --->
				<div class="menu_box">
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "badget_data_list/index" ?>';return false">
				収入リスト
				</button>
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "badget_list/index" ?>';return false">
				収入：定期設定の確認
				</button>
				</div>

				<!-- システム --->
				<div class="menu_box">
				<button class='btn btn-success' onClick="location.href='<?php echo base_url() . "system_edit/index" ?>';return false">
				システム設定
				</button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
