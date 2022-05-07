<?php
/*----------------------------
 支出データ登録ダイアログ
----------------------------*/
form_hidden('editDialogID','#spendDataEntryDialog'); 
?>

<div class="modal" id="spendDataEntryDialog" tabindex="-1" role="dialog" 
	aria-labelledby="spendDataEntryDialog_title" aria-hidden="true" data-show="true" 
	data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="spendDataEntryDialog_title">支出登録</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<?php 
				$data = array(
					'type'         => 'hidden',
					'id'          => 'spendDataEntryBadgetDataId',
					'name'          => 'spendDataEntryBadgetDataId',
					'value'          => ''
				);
				echo form_input($data);
				?>

				<table class="input_table">
					<tr>
						<th>日付</th>
						<td>
							<?php 
							$data = array(
								'type'        => 'date',
								'id'          => 'spendDataEntrySpendModalDay',
								'name'        => 'spendDataEntrySpendModalDay',
								'value'       => '',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('spendDataEntrySpendModalDay');
							?>
						</td>
					</tr>
					<tr>
						<th>支出</th>
						<td>
							<?php 
							$data = array(
								'type'        => 'number',
								'id'          => 'spendDataEntrySpendMoney',
								'name'        => 'spendDataEntrySpendMoney',
								'value'       => '0',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('spendDataEntrySpendMoney');
							?><?php echo $currencyUnit;?>
						</td>
					</tr>
					<tr>
						<th>メモ</th>
						<td>
							<?php 
							$data = array(
								'id'          => 'spendDataEntryMemo',
								'name'          => 'spendDataEntryMemo',
								'rows'        => '5'
							);
							echo form_textarea($data);
							echo form_error('spendDataEntryMemo');
							?>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
				<?php 
				echo form_submit("entry_submit", "登録","class='btn btn-primary'");
				echo form_error('entry_submit');
				?>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
