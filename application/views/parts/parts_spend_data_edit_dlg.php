<?php
/*----------------------------
 支出データ編集ダイアログ
----------------------------*/
form_hidden('editDialogID','#spendDataEditDialog'); 
?>

<div class="modal" id="spendDataEditDialog" tabindex="-1" role="dialog" 
	aria-labelledby="spendDataEditDialog_title" aria-hidden="true" data-show="true" 
	data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="spendDataEditDialog_title">支出編集</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<?php 
				$data = array(
					'type'         => 'hidden',
					'id'          => 'spendDataEditBadgetDataId',
					'name'          => 'spendDataEditBadgetDataId',
					'value'          => ''
				);
				echo form_input($data);
				$data = array(
					'type'         => 'hidden',
					'id'          => 'spendDataEditSpendDataId',
					'name'          => 'spendDataEditSpendDataId',
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
								'id'          => 'spendDataEditSpendModalDay',
								'name'        => 'spendDataEditSpendModalDay',
								'value'       => '',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('spendDataEditSpendModalDay');
							?>
						</td>
					</tr>
					<tr>
						<th>支出</th>
						<td>
							<?php 
							$data = array(
								'type'        => 'number',
								'id'          => 'spendDataEditSpendMoney',
								'name'        => 'spendDataEditSpendMoney',
								'value'       => '0',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('spendDataEditSpendMoney');
							?><?php echo $currencyUnit;?>
						</td>
					</tr>
					<tr>
						<th>メモ</th>
						<td>
							<?php 
							$data = array(
								'id'          => 'spendDataEditMemo',
								'name'          => 'spendDataEditMemo',
								'rows'        => '5'
							);
							echo form_textarea($data);
							echo form_error('spendDataEditMemo');
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
