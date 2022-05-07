<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクション新規作成画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_add.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_add_0480.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_add_0768.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_add_1024.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<script>
		$(function() {
			$('#actionType_1,#actionType_2,#actionType_3').on('click', function() {
				viewActionType(this.value);
			});

			// アクションタイプ
			viewActionType(<?php echo $actionType ?>);
		});

		function viewActionType(actionType) {
			if (actionType == 2) {
				$('#count_down_area').show();
				$('#count_up_area').hide();
			} else if (actionType == 1) {
				$('#count_down_area').hide();
				$('#count_up_area').show();
			} else {
				$('#count_down_area').hide();
				$('#count_up_area').hide();
			}
		}

		/**
		 * 新規作成をキャンセルする
		 */
		function cancelAdd() {
			var result = comConfirm('新規作成をキャンセルしてよろしいですか？');

			if (result) {
				// 戻るを無効にする
				$('#addform').attr('action', '<?php echo base_url(); ?>action_add/cancelAdd');

				$('#addform').submit();
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
			<div id="action_title_container_item">
				<div class="action_title">
					<h1>
						アクション新規作成
					</h1>
					<a href="#" onClick="cancelAdd();return false;">
						<img class="btn_cancel" src="<?php echo base_url(); ?>images/ic_action_cancel.png" />
					</a>
				</div>
			</div>
			<div class="main_area">
				<?php
				$attributes = array('id' => 'addform');
				echo form_open("Action_add/addActionValidation",$attributes);	//フォームを開く
				$data = array(
					'type'        => 'hidden',
					'id'          => 'reDispId',
					'name'        => 'reDispId',
					'value'       => $reDispId
				);
				echo form_input($data);
				?>
				<div class="base_input_area">
					<div class="input_item">
						<span>アクション名</span><span class="required">●</span>
						<?php
						$data = array(
							'type'        => 'input',
							'id'          => 'actionTitle',
							'name'        => 'actionTitle',
							'class'       => 'form-control',
							'value'       => $actionTitle
						);
						echo form_input($data);
						echo form_error('actionTitle');
						?>

					</div> <!-- input_item -->

					<div class="input_item">
						<span>具体的なアクションを行う手順</span>
						<?php
						$data = array(
							'id'          => 'actionDescription',
							'name'        => 'actionDescription',
							'class'       => 'form-control',
							'rows'        => 5,
							'value'       => $actionDescription
						);
						echo form_textarea($data);
						echo form_error('actionDescription');
						?>
					</div> <!-- input_item -->

					<div class="input_item">
						<span>アクション実行時に表示するメッセージ※達成時のイメージなど</span>
						<?php
						$data = array(
							'id'          => 'actionMessage',
							'name'        => 'actionMessage',
							'class'       => 'form-control',
							'rows'        => 5,
							'value'       => $actionMessage
						);
						echo form_textarea($data);
						echo form_error('actionMessage');
						?>
					</div> <!-- input_item -->

					<!-- 今日のアクションに追加 -->
					<div class="input_item">
						<?php
						$data = array(
							'id'          => 'dayActionFlg',
							'name'        => 'dayActionFlg',
							'value'       => true,
							'checked'     => $dayActionFlg
						);
						echo form_checkbox($data);
						echo form_label('今日のアクションに追加', 'dayActionFlg');
						?>
					</div> <!-- input_item -->

					<!-- 公開・非公開 -->
					<div class="input_item">
						<?php
						$data = array(
							'name'          => 'authType',
							'id'            => 'authType_0',
							'value'         => '0',
							'checked'       => $authType == 0,
							'style'         => 'margin:10px'
						);
						echo form_radio($data);
						echo form_label('非公開', 'authType_0');

						$data = array(
							'name'          => 'authType',
							'id'            => 'authType_1',
							'value'         => '1',
							'checked'       => $authType == 1,
							'style'         => 'margin:10px'
						);
						echo form_radio($data);
						echo form_label('公開', 'authType_1');
						?>
					</div> <!-- input_item -->

					<div class="input_item">
						<span>アクションタイプ</span>
						<?php
						$data = array(
							'name'          => 'actionType',
							'id'            => 'actionType_3',
							'value'         => '3',
							'checked'       => $actionType == 3,
							'style'         => 'margin:10px'
						);
						echo form_radio($data);
						echo form_label('ファーストアクション', 'actionType_3');

						$data = array(
							'name'          => 'actionType',
							'id'            => 'actionType_2',
							'value'         => '2',
							'checked'       => $actionType == 2,
							'style'         => 'margin:10px'
						);
						echo form_radio($data);
						echo form_label('カウントダウン', 'actionType_2');

						$data = array(
							'name'          => 'actionType',
							'id'            => 'actionType_1',
							'value'         => '1',
							'checked'       => $actionType == 1,
							'style'         => 'margin:10px'
						);
						echo form_radio($data);
						echo form_label('カウントアップ', 'actionType_1');
						?>
					</div> <!-- input_item -->

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
								'value'       => $basicCompleteTimeDown
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
								'value'       => $basicCompleteTimeUp
							);
							echo form_input($data);
							echo form_error('basicCompleteTimeUp');
							?>
						</div> <!-- input_item -->
					</div> <!-- count_up_area -->
				</div> <!-- base_input_area -->

				<div class="button_field_area">
					<div class="button_field ">
						<?php
						echo form_submit("entry_submit", "登録", "class='btn btn-primary'");
						echo form_error('entry_submit');
						?>
					</div> <!-- button_field -->
				</div> <!-- button_field_area -->

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

</html>