<?php
/*----------------------------
 価値観編集ダイアログ
----------------------------*/
?>
<script type="text/javascript">
	function initPartsTopValuesEdit() {
		// Ajax button click
		$('#values_edit_btnUpdate').on('click', function() {
			$.ajax({
					url: '<?= base_url(); ?>user_edit/updValues/',
					type: 'POST',
					data: {
						'values01': $('#values_edit_txtValues01').val(),
						'values02': $('#values_edit_txtValues02').val(),
						'values03': $('#values_edit_txtValues03').val()
					}
				})
				// Ajaxリクエストが成功した時発動
				.done((data) => {
					var values01Id = $('#values_edit_txtValues01Id').val();
					var values02Id = $('#values_edit_txtValues02Id').val();
					var values03Id = $('#values_edit_txtValues03Id').val();

					// spanを更新する。
					$(values01Id).text(data.values01);
					$(values02Id).text(data.values02);
					$(values03Id).text(data.values03);

					console.log(data);

					// ミッション編集ダイアログを閉じる。
					$('#valuesEditModal').modal('hide');
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

<div class="modal" id="valuesEditModal" tabindex="-1" role="dialog" aria-labelledby="valuesEditModal_title" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">

	<?php
	$data = array(
		'id' => 'values_edit_txtValues01Id',
		'name' => 'values_edit_txtValues01Id',
		'type'  => 'hidden'
	);
	echo form_input($data);
	$data = array(
		'id' => 'values_edit_txtValues02Id',
		'name' => 'values_edit_txtValues02Id',
		'type'  => 'hidden'
	);
	echo form_input($data);
	$data = array(
		'id' => 'values_edit_txtValues03Id',
		'name' => 'values_edit_txtValues03Id',
		'type'  => 'hidden'
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
					<span>価値観１</span>
					<?php
					$data = array(
						'rows'        => '5',
						'id'          => 'values_edit_txtValues01',
						'name'        => 'values_edit_txtValues01',
						'class'       => 'form-control',
						'value'       => "",
						'placeholder' => '例：家族'
					);
					echo form_textarea($data);
					?>
				</div> <!-- input_item -->
				<div class="input_item">
					<span>価値観２</span>
					<?php
					$data = array(
						'rows'        => '5',
						'id'          => 'values_edit_txtValues02',
						'name'        => 'values_edit_txtValues02',
						'class'       => 'form-control',
						'value'       => "",
						'placeholder' => '例：教育'
					);
					echo form_textarea($data);
					?>
				</div> <!-- input_item -->
				<div class="input_item">
					<span>価値観３</span>
					<?php
					$data = array(
						'rows'        => '5',
						'id'          => 'values_edit_txtValues03',
						'name'        => 'values_edit_txtValues03',
						'class'       => 'form-control',
						'value'       => "",
						'placeholder' => '例：お金'
					);
					echo form_textarea($data);
					?>
				</div> <!-- input_item -->

			</div>
			<div class="modal-footer">
				<button id="values_edit_btnUpdate" class='btn btn-success'>
					更新
				</button>
			</div>
		</div> <!-- /.modal-content -->
	</div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->