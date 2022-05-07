<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクションログ編集画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>

	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_edit.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_edit_0480.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_edit_0768.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_edit_1024.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<script>
		$(function() {
			<?php
			if ($editCompleteFlg) {
				echo "feadMessage('アクションログ','更新しました。');";
			} ?>
		});

		/**
		 * 編集をキャンセルする
		 */
		function cancelActionLogEdit() {
			if (!main_container.editFlg) {
				// 戻るを無効にする
				$('#mainForm').attr('action', '<?php echo base_url(); ?>actionlog_edit/cancelActionLogEdit');

				$('#mainForm').submit();

			} else {
				var result = comConfirm('アクションログの編集をキャンセルしてよろしいですか？');

				if (result) {
					// 戻るを無効にする
					$('#mainForm').attr('action', '<?php echo base_url(); ?>actionlog_edit/cancelActionLogEdit');

					$('#mainForm').submit();
				}

			}
		}
	</script>

</head>

<body>

	<div id="container">
		<div id="header">
			<?php $this->load->view('parts/parts_header'); ?>
		</div>
		<div id="menu">
			<?php $this->load->view('parts/parts_menu', $partsMenu); ?>
		</div> <!-- #menu -->

		<div id="main">
			<div id="main_container">

				<?php

				$attributes = array('id' => 'mainForm', 'autocomplete' => 'off');
				echo form_open("actionlog_edit/updActionLogValidation", $attributes);	//フォームを開く


				$data = array(
					'name' => 'actionLogId',
					'type'  => 'hidden',
					'value'   => $actionLogId
				);
				echo form_input($data);

				$data = array(
					'name' => 'actionType',
					'type'  => 'hidden',
					'value'   => $actionType
				);
				echo form_input($data);

				$data = array(
					'name' => 'actionTitle',
					'type'  => 'hidden',
					'value'   => $actionTitle
				);
				echo form_input($data);

				$data = array(
					'name' => 'actionTimeFrom',
					'type'  => 'hidden',
					'value'   => $actionTimeFrom
				);
				echo form_input($data);

				$data = array(
					'type'        => 'hidden',
					'id'          => 'reDispId',
					'name'        => 'reDispId',
					'value'       => $reDispId
				);
				echo form_input($data);

				$actionTypeZero = str_pad($actionType, 2, 0, STR_PAD_LEFT);
				?>
				<div id="action_title_container_item">
					<div class="action_title">
						<h1>
							<img class="action_type" src="<?php echo base_url() . "images/com_action_type_" . $actionTypeZero . "_on.png" ?>" />

							<?php echo $actionTitle ?>
						</h1>
						<a class="cancel_action" href="#" onClick="cancelActionLogEdit();return false;">
							<img class="btn_cancel" src="<?php echo base_url(); ?>images/ic_action_cancel.png" />
						</a>
					</div>
				</div> <!-- action_title_container_item -->

				<div id="edit_container_item">
					<div class="base_input_box">

						<div class="input_item">
							<span>開始日時</span>

							<table>
								<tr>
									<td>
										<div class="form-control disp_day" readonly>
											<?php echo $actionTimeFromDay ?>
										</div>
									</td>

									<td>
										<div class="form-control disp_time" readonly>
											<?php echo $actionTimeFromTime ?>
										</div>
									</td>
								</tr>
							</table>
						</div> <!-- input_item -->

						<div class="input_item">
							<span>終了日</span>

							<table>
								<tr>
									<td>
										<?php
										$data = array(
											'type'        => 'date',
											'id'          => 'actionTimeToDay',
											'name'        => 'actionTimeToDay',
											'class'       => 'form-control',
											'v-model'       => 'actionTimeToDay'
										);
										echo form_input($data);
										echo form_error('actionTimeToDay');
										?>
									</td>

									<td>
										<?php
										$data = array(
											'type'        => 'time',
											'id'          => 'actionTimeToTime',
											'name'        => 'actionTimeToTime',
											'class'       => 'form-control',
											'step'        => 60, // 60秒単位
											'v-model'       => 'actionTimeToTime'
										);
										echo form_input($data);
										echo form_error('actionTimeToTime');
										?>
									</td>
								</tr>
							</table>
						</div> <!-- input_item -->

					</div> <!-- base_input_box -->
				</div> <!-- edit_container_item -->


				<div id="button_field_container_item">
					<div class="button_field ">
						<?php
						echo form_submit("entry_submit", "更新", "class='btn btn-primary'");
						echo form_error('entry_submit');
						?>
					</div> <!-- button_field -->
				</div> <!-- button_field_container_item -->
				<?php
				echo form_close();	//フォームを閉じる
				?>

			</div> <!-- main_area -->
		</div> <!-- main -->

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div> <!-- fotter -->
	</div> <!-- container -->

</body>

<script>
	var main_container = new Vue({
		el: "#main_container",
		data: {
			editFlg: false,
			actionTimeToDay: '<?= $actionTimeToDay; ?>',
			actionTimeToTime: '<?= $actionTimeToTime; ?>'
		}
	});

	main_container.$watch(function() {
		return this.actionTimeToDay;
	}, function(quantity) {
		this.editFlg = true;
	});

	main_container.$watch(function() {
		return this.actionTimeToTime;
	}, function(quantity) {
		this.editFlg = true;
	})
</script>

</html>