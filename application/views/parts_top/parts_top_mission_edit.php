<?php
/*----------------------------
 ミッション編集ダイアログ
----------------------------*/
?>
<script type="text/javascript">
	function initPartsTopMissionEdit() {
		// Ajax button click
		$('#mission_edit_btnUpdate').on('click', function() {
			$.ajax({
					url: '<?= base_url(); ?>user_edit/updMission/',
					type: 'POST',
					data: {
						'mission': $('#mission_edit_txtMission').val()
					}
				})
				// Ajaxリクエストが成功した時発動
				.done((data) => {
					var editID = $('#mission_edit_txtEditId').val();
					
					// spanを更新する。
					$(editID).text(data);

					// ミッション編集ダイアログを閉じる。
					$('#missionEditModal').modal('hide');
				})
				// Ajaxリクエストが失敗した時発動
				.fail((data) => {
					console.log(data);
				})
				// Ajaxリクエストが成功・失敗どちらでも発動
				.always((data) => {

				});
		});
	}
</script>

<div class="modal" id="missionEditModal" tabindex="-1" role="dialog" aria-labelledby="missionEditModal_title" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">

	<?php
	$data = array(
		'id' => 'mission_edit_txtEditId',
		'name' => 'mission_edit_txtEditId',
		'type'  => 'hidden',
		'value'   => ''
	);
	echo form_input($data);
	?>

	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="missionEditModal_title">ミッション編集</h4>
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&#215;</span><span class="sr-only">閉じる</span>
				</button>
			</div><!-- /modal-header -->
			<div class="modal-body">
				<div class="input_item">
					<span>ミッション</span><span class="required">●</span>
					<?php
					$data = array(
						'rows'        => '5',
						'id'          => 'mission_edit_txtMission',
						'name'        => 'mission_edit_txtMission',
						'class'       => 'form-control',
						'value'       => "",
						'placeholder' => '例：私のミッションは〇〇である'
					);
					echo form_textarea($data);
					echo form_error('actionTitle');
					?>
				</div> <!-- input_item -->


			</div>
			<div class="modal-footer">
				<button id="mission_edit_btnUpdate" class='btn btn-success'>
					更新
				</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->