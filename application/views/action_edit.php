<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクション編集画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?= base_url(); ?>styles/action_edit.css?<?= date('YmdHHmmss'); ?>" type="text/css" />

	<!--<link rel="stylesheet" href="<?= base_url(); ?>styles/action_edit_0480.css?<?= date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?= base_url(); ?>styles/action_edit_0768.css?<?= date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?= base_url(); ?>styles/action_edit_1024.css?<?= date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<!-- マークダウン編集 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
	<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

	<script>
		$(function() {

			// アクションタイプ
			//viewActionType(<?= $actionType ?>);

			<?php
			if ($editCompleteFlg) {
				echo "feadMessage('アクション','更新しました。');";
			} ?>

		});

		function fileUpload() {
			$("#userfile").click();
		}

		function fileDelete() {
			$("#btnDeletePicture").click();
		}


		/**
		 * 編集をキャンセルする
		 */
		function cancelEdit() {
			if (!main_container.editFlg) {
				// 戻るを無効にする
				$('#mainForm').attr('action', '<?php echo base_url(); ?>action_edit/cancelEdit');

				$('#mainForm').submit();

			} else {
				var result = comConfirm('アクションの編集をキャンセルしてよろしいですか？');

				if (result) {
					// 戻るを無効にする
					$('#mainForm').attr('action', '<?php echo base_url(); ?>action_edit/cancelEdit');

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
				echo form_open("Action_edit/updActionValidation", $attributes);	//フォームを開く

				$data = array(
					'name' => 'actionId',
					'type'  => 'hidden',
					'value'   => $actionId
				);
				echo form_input($data);
				$data = array(
					'name' => 'actionTriggerId',
					'type'  => 'hidden',
					'value'   => $actionTriggerId
				);
				echo form_input($data);
				$data = array(
					'type'        => 'hidden',
					'id'          => 'reDispId',
					'name'        => 'reDispId',
					'value'       => $reDispId
				);
				echo form_input($data);
				?>

				<div id="title_container_item">
					<div class="action_title">
						<h1>
							アクション編集
						</h1>
						<a class="cancel_edit" href="#" onClick="cancelEdit();return false;">
							<img class="btn_cancel" src="<?php echo base_url(); ?>images/ic_action_cancel.png" />
						</a>
					</div>
				</div> <!-- title_container_item -->

				<div id="main_container_item">
					<div class="input_item">
						<span>アクション名</span><span class="required">●</span>
						<?php
						$data = array(
							'type'        => 'input',
							'id'          => 'actionTitle',
							'name'        => 'actionTitle',
							'class'       => 'form-control',
							'v-model'     => 'actionTitle',
							'placeholder' => '例：5分間歯磨き'
						);
						echo form_input($data);
						echo form_error('actionTitle');
						?>
					</div> <!-- input_item -->


					<div class="input_item">
						<span>アクションを実行することによる効果・結果</span><span class="required">●</span>
						<?php
						$data = array(
							'id'          => 'actionMessage',
							'name'        => 'actionMessage',
							'class'       => 'form-control',
							'rows'        => 5,
							'v-model'     => 'actionMessage',
							'placeholder' => '例：歯が綺麗になり、歯医者の定期検査で褒められる。'
						);
						echo form_textarea($data);
						echo form_error('actionMessage');
						?>
					</div> <!-- input_item -->

					<div class="input_item">
						<span>実行方法</span><span class="required">●</span>
						<?php
						$data = array(
							'id'          => 'actionDescription',
							'name'        => 'actionDescription',
							'class'       => 'form-control',
							'rows'        => 5,
							'v-model'     => 'actionDescription',
							'placeholder' => '例：5分間、歯磨きを行う'
						);
						echo form_textarea($data);
						echo form_error('actionDescription');
						?>
					</div> <!-- input_item -->


					<!-- 公開・非公開 -->
					<div class="input_item">
						<div class="radio_item">
							<?php
							$data = array(
								'name'          => 'authType',
								'id'            => 'authType_0',
								'value'         => '0',
								'v-model'     	=> 'authType',
								'style'         => 'margin:10px'
							);
							echo form_radio($data);
							echo form_label('非公開', 'authType_0');

							$data = array(
								'name'          => 'authType',
								'id'            => 'authType_1',
								'value'         => '1',
								'v-model'     	=> 'authType',
								'style'         => 'margin:10px'
							);
							echo form_radio($data);
							echo form_label('公開', 'authType_1');
							?>
						</div>	
					</div> <!-- input_item -->

					<div class="input_item">

						<div class="radio_item">
							<span class="radio_title">アクションタイプ</span>

							<?php
							$data = array(
								'name'          => 'actionType',
								'id'            => 'actionType_3',
								'value'         => '3',
								'v-model'     	=> 'actionType',
								'style'         => 'margin:10px'
							);
							echo form_radio($data);
							echo form_label('ファーストアクション', 'actionType_3');

							$data = array(
								'name'          => 'actionType',
								'id'            => 'actionType_2',
								'value'         => '2',
								'v-model'     	=> 'actionType',
								'style'         => 'margin:10px'
							);
							echo form_radio($data);
							echo form_label('カウントダウン', 'actionType_2');

							$data = array(
								'name'          => 'actionType',
								'id'            => 'actionType_1',
								'value'         => '1',
								'v-model'     	=> 'actionType',
								'style'         => 'margin:10px'
							);
							echo form_radio($data);
							echo form_label('カウントアップ', 'actionType_1');
							?>

							<div id="count_down_area" class="actionType_area" style="display:none">
								<h3>カウントダウン</h3>
								<p>目標とする実行時間を決め、その時間だけ実行するアクションです。
									目標時間を入力してください。</p>
								<div class="input_item">
									<span>目標時間</span><span class="required">●</span>
									<?php
									$data = array(
										'type'        => 'time',
										'id'          => 'basicCompleteTimeDown',
										'name'        => 'basicCompleteTimeDown',
										'class'       => 'form-control',
										'step'        => 1, // 1秒単位
										'v-model'     	=> 'basicCompleteTimeDown'
									);
									echo form_input($data);
									echo form_error('basicCompleteTimeDown');
									?>
								</div> <!-- input_item -->
							</div> <!-- count_down_area -->

							<div id="count_up_area" class="actionType_area" style="display:none">
								<h3>カウントアップ</h3>
								<p>目標時間を達成した後、達成できます。</p>

								<div class="input_item">
									<span>目標時間</span><span class="required">●</span>
									<?php
									$data = array(
										'type'        => 'time',
										'id'          => 'basicCompleteTimeUp',
										'name'        => 'basicCompleteTimeUp',
										'class'       => 'form-control',
										'step'        => 1, // 1秒単位
										'v-model'     	=> 'basicCompleteTimeUp'
									);
									echo form_input($data);
									echo form_error('basicCompleteTimeUp');
									?>
								</div> <!-- input_item -->
							</div> <!-- count_up_area -->
						</div>
					</div> <!-- input_item -->

				</div> <!-- main_container_item -->


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
			</div> <!-- main_container -->

		</div> <!-- main -->

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div> <!-- fotter -->
	</div> <!-- container -->

	<script>
		var main_container = new Vue({
			el: "#main_container",
			data: {
				editFlg: false,
				actionTitle: '<?= $actionTitle; ?>',
				actionDescription: '<?= js_multi_string($actionDescription); ?>',
				actionMessage: '<?= js_multi_string($actionMessage); ?>',
				authType: '<?= $authType; ?>',
				actionType: '<?= $actionType; ?>',
				basicCompleteTimeDown: '<?= $basicCompleteTimeDown; ?>',
				basicCompleteTimeUp: '<?= $basicCompleteTimeUp; ?>'
			},
			methods: {
				chgActionType: function(val) {
					if (val == 2) {
						$('#count_down_area').show();
						$('#count_up_area').hide();
					} else if (val == 1) {
						$('#count_down_area').hide();
						$('#count_up_area').show();
					} else {
						$('#count_down_area').hide();
						$('#count_up_area').hide();
					}

				}
			},
			watch: {
				actionTitle: function(val) {
					this.editFlg = true;
				},
				actionDescription: function(val) {
					this.editFlg = true;
				},
				actionMessage: function(val) {
					this.editFlg = true;
				},
				authType: function(val) {
					this.editFlg = true;
				},
				actionType: function(val) {
					this.editFlg = true;

					this.chgActionType(val);
				},
				basicCompleteTimeDown: function(val) {
					this.editFlg = true;
				},
				basicCompleteTimeUp: function(val) {
					this.editFlg = true;
				}
			}
		});

		main_container.chgActionType('<?= $actionType; ?>');

		var actionMessage = new SimpleMDE({ element: document.getElementById("actionMessage") });
		var actionDescription = new SimpleMDE({ element: document.getElementById("actionDescription") });

	</script>
</body>

</html>