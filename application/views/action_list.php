<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクション一覧画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php
	$this->load->view('parts/parts_include');
	?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_list.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />


	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_list_0480.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_list_0768.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_list_1024.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<script>
		$(function() {
			// 共通初期化処理を呼び出す
			actionTrigger_init('<?php echo $editDialogID; ?>', '<?php validation_errors(); ?>');

			// アクションメニュー
			$('#actionMenuModal').on('show.bs.modal', actionMenuModal);

			// アクション追加メニュー
			$('#actionAddMenuModal').on('show.bs.modal', function(event) {
				console.log("b-1");
			});

			<?php
			if ($deleteActionFlg) {
				echo "feadMessage('アクション','削除しました。');";
			} ?>
		});

		/**
		 * アクション削除
		 */
		function deleteActionData(actionID, actionTitle) {
			if (!comConfirm(actionTitle + 'のアクションを削除してよろしいですか？')) {
				return false;
			}

			$('#mainform').attr('action', '<?php echo base_url(); ?>action_list/actionDeleteValidation');

			$('#deleteActionId').val(actionID);

			$('#find_submit').click();
		}

		/**
		 * アクション編集
		 */
		function editActionData(actionID) {
			location.href = '<?php echo base_url() . "action_edit/editActionData/" . $nextReDispId . "/" ?>' + actionID + '/0';
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
		</div>

		<div id="main">
			<div class="find_area">
				<?php
				$attributes = array('id' => 'mainform');
				echo form_open("Action_list/findList", $attributes);	//フォームを開く


				$data = array(
					'type'        => 'hidden',
					'id'          => 'reDispId',
					'name'        => 'reDispId',
					'value'       => $reDispId
				);
				echo form_input($data);

				?>
				検索条件：
				<div class="find_input input-group mb-3">
					<?php
					$data = array(
						'type'        => 'text',
						'id'          => 'findActionTitle',
						'name'        => 'findActionTitle',
						'class'       => 'form-control',
						'value'       => $findActionInfo['actionTitle'],
						'placeholder' => 'ここに検索するアクション名を入力して、検索ボタンをクリックして下さい。'
					);
					echo form_input($data);
					?>
					<div class="input-group-append">
						<?php
						echo form_submit("find_submit", "検索", "id='find_submit' class='btn btn-primary btn-outline-secondary'");
						?>
					</div>
				</div><!-- find_input -->
				<?php
				echo form_error('find_submit');

				// 削除用ActionId
				$data = array(
					'name' => 'deleteActionId',
					'id'  => 'deleteActionId',
					'type'  => 'hidden',
					'value'   => ''
				);
				echo form_input($data);

				echo form_close();	//フォームを閉じる
				?>
			</div>

			<div class="result_area">
				<div class="result_data">
					<div class="list_header_area">
						<div class="list_header">
							&nbsp;
							<span class="action_add" data-toggle="modal" data-target="#actionAddMenuModal">
								<img src="<?php echo base_url() . "images/com_add_button_on.png" ?>" />
							</span>
						</div>
					</div> <!-- list_header_area -->
					<div class="list_area">
						<?php
						if ($actionRecords) {
						?>
							<table>
								<tr>
									<th colspan="2">アクション</th>
									<th>実行</th>
									<th>編集</th>
									<th>削除</th>
									<th></th>
								</tr>
								<?php
								$rowno = 1;
								$dateValue = "";
								if ($actionRecordCount > 0) :
									foreach ($actionRecords as $row) :
										$actionTypeZero = str_pad($row['action_type'], 2, 0, STR_PAD_LEFT);

										$actionRunFlg = $row['action_cnt'] > 0 ? "on" : "off";

										$actionTriggerId = $row['action_trigger_id'];
										if ($actionTriggerId == "") {
											$actionTriggerId = "0";
										}
								?>
										<tr>
											<td>
												<img class="action_type" src="<?php echo base_url() . "images/com_action_type_" . $actionTypeZero . "_on.png" ?>" />
											</td>
											<td>
												<span>
													<?php echo $row['action_title'] ?>
												</span>
												<?php if ($row['action_trigger_id'] != "") : ?>
													<span class="active_action">ACTIVE</span>
												<?php endif; ?>
												<?php if ($row['new_action_flg']) : ?>
													<span class="new_action">NEW</span>
												<?php endif; ?>
												<img class="action_type" src="<?php echo base_url() . "images/action_check_" . $actionRunFlg . ".png" ?>" />
											</td>
											<td>
												<button class='btn btn-primary btn-sm' onClick="location.href='<?php echo base_url(); ?>action_run/index/<?php echo $nextReDispId ?>/<?php echo $row['action_id'] ?>/<?php echo $actionTriggerId ?>/1';return false;">
													<i class="fas fa-play"></i>
												</button>
											</td>
											<td class="edit">
												<?php if ($row['edit_flg']) : ?>
													<a href="#" onClick="editActionData(<?php echo $row['action_id'] ?>);return false;">
														<i class="far fa-edit"></i>
													</a>
												<?php endif; ?>
											</td>
											<td class="delete">
												<?php if ($row['edit_flg']) : ?>
													<a href="#" onClick="deleteActionData('<?php echo $row['action_id'] ?>','<?php echo $row['action_title'] ?>');return false;">
														<i class="far fa-trash-alt"></i>
													</a>
												<?php endif; ?>
											</td>
											<td class="menu">
												<a href="#" data-toggle="modal" data-target="#actionMenuModal" data-action_id="<?php echo $row['action_id'] ?>" data-action_trigger_id="<?php echo $actionTriggerId ?>" data-action_title="<?php echo $row['action_title'] ?>" data-add_action_flg="<?php echo $row['add_action_flg'] ?>" data-create_flg="<?php echo $row['create_flg'] ?>" data-disp_id="<?php echo $nextReDispId ?>">
													<i class="far fa-caret-square-down"></i>
												</a>
											</td>
										</tr>
								<?php
										$rowno++;
									endforeach;
								endif;
								?>
							</table>
						<?php
						} else {
						?>
							<h3>※アクションはありません。</h3>
						<?php
						}
						?>
					</div> <!-- list_area -->
				</div> <!-- result_data -->
			</div> <!-- result_area -->
		</div>

		<!-- メニューダイアログ -->
		<?php
		$this->load->view('parts/parts_action_menu', $actionMenuItems);
		?>

		<!-- アクション追加メニューダイアログ -->
		<?php
		$this->load->view('parts/parts_action_add_menu', $actionAddMenuItems);
		?>

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div>
</body>


</html>