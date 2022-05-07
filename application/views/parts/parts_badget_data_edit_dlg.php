<?php
/*----------------------------
 予算データ編集ダイアログ
----------------------------*/
form_hidden('editDialogID','#badgetDataEditDialog'); 
?>

<div class="modal" id="badgetDataEditDialog" tabindex="-1" role="dialog" 
    aria-labelledby="badgetDataEditDialog_title" aria-hidden="true" data-show="true" 
	data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="badgetDataEditDialog_title">予算編集</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<?php 
				$data = array(
					'type'         => 'hidden',
					'id'          => 'badgetDataEditBadgetDataId',
					'name'          => 'badgetDataEditBadgetDataId',
					'value'          => ''
				);
				echo form_input($data);
				?>

				<table class="input_table">
					<tr>
						<th>期間</th>
						<td>
							<?php 
							$data = array(
								'type'         => 'date',
								'id'          => 'badgetDataEditBadgetFromDate',
								'name'          => 'badgetDataEditBadgetFromDate',
								'value'          => '',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('badgetDataEditBadgetFromDate');
							?>
							～
							<?php 
							$data = array(
								'type'         => 'date',
								'id'          => 'badgetDataEditBadgetToDate',
								'name'          => 'badgetDataEditBadgetToDate',
								'value'          => '',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('badgetDataEditBadgetToDate');
							?>
						</td>
					</tr>
					<tr>
						<th>予算額</th>
						<td>
							<?php 
							$data = array(
								'type'        => 'number',
								'id'          => 'badgetDataEditBadgetMoney',
								'name'        => 'badgetDataEditBadgetMoney',
								'value'       => '',
								'min'         => '0',
								'required'    => 'required'
							);
							echo form_input($data);
							echo form_error('badgetDataEditBadgetMoney');
							?><?php echo $currencyUnit;?>
						</td>
					</tr>
					<tr>
						<th>メモ</th>
						<td>
							<?php 
							$data = array(
								'id'          => 'badgetDataEditMemo',
								'name'        => 'badgetDataEditMemo',
								'rows'        => '5'
							);
							echo form_textarea($data);
							echo form_error('badgetDataEditMemo');
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
