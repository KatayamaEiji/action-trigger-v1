<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
トップ（メニュー）画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?= base_url(); ?>styles/top.css?<?= date('YmdHHmmss'); ?>" type="text/css" />

	<link rel="stylesheet" href="<?= base_url(); ?>styles/top_0480.css?<?= date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<link rel="stylesheet" href="<?= base_url(); ?>styles/top_0768.css?<?= date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<link rel="stylesheet" href="<?= base_url(); ?>styles/top_1024.css?<?= date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" />
	<!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

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
			<div id="body_area">
				<div id="body_area_main">
					<div class="action_table_list">
						<div class="action_title">
							<h2>今日のアクション</h2>
							<span class="action_add hover_pointer" data-toggle="modal" data-target="#actionAddMenuModal">
								<img src="<?= base_url() . "images/com_add_button_on.png" ?>" />
							</span>
						</div> <!-- action_title -->
						<div class="action_area">
							<?php
							$rowno = 1;
							if ($nowActionRecordCount > 0) :
								foreach ($nowActionRecords as $row) :
									$row['rowno'] = $rowno;
									$this->load->view('parts_top/parts_top_action_block', $row);
									$rowno++;
								endforeach;
							endif;
							?>
						</div><!-- action_area -->

						<?php
						$rowno = 1;
						if ($otherActionRecordCount > 0) :
							?>
							<div class="action_title">
								<h2>今日のアクション以外で実行</h2>
							</div> <!-- action_title -->
							<div class="action_area">
								<?php
									foreach ($otherActionRecords as $row) :
										$row['rowno'] = $rowno;
										$this->load->view('parts_top/parts_top_other_action_block', $row);
										$rowno++;
									endforeach;
									?>
							</div><!-- action_area -->
						<?php
						endif;
						?>
					</div><!-- action_table_list -->
				</div><!-- #body_area_main -->
				<div id="info_area">
					<div class="info_messege">
						<?php $this->load->view('parts/parts_info'); ?>
					</div>
				</div> <!-- info_area -->

				<div id="body_area_side">
					<div class="side_user_info">
						<table>
							<?php
							if ($userItems["mission"] != "") :
								?>
								<tr>
									<td>
										<span class="edit_item faa-parent animated-hover" data-toggle="modal" data-target="#missionEditModal" data-mission="<?= $userItems["mission"] ?>" data-edit_id="#user_mission" >
										ミッション：
											<i class="fas fa-edit faa-wrench" ></i>
										</span>
										<div>
											<?= "<span id='user_mission' class='user_mission'>" . $userItems["mission"] . "</span>"; ?>
										</div>
									</td>
								</tr>
							<?php
							endif
							?>
							<?php
							if ($userItems["values01"] != "" && $userItems["values02"] != "" && $userItems["values03"] != "") :
								?>
								<tr>
									<td>
										<span class="edit_item faa-parent animated-hover" data-toggle="modal" data-target="#valuesEditModal" 
											data-values01="<?= $userItems["values01"] ?>" data-values02="<?= $userItems["values02"] ?>" data-values03="<?= $userItems["values03"] ?>"
											data-values01_id="#user_values01" data-values02_id="#user_values02" data-values03_id="#user_values03"  >
										価値観：
											<i class="fas fa-edit faa-wrench" ></i>
										</span>

										<?php
											if ($userItems["values01"] != "") {
												echo "<span id='user_values01' class='user_values'>" . $userItems["values01"] . "</span>";
											}
											?>
										<?php
											if ($userItems["values02"] != "") {
												echo "<span id='user_values02' class='user_values'>" . $userItems["values02"] . "</span>";
											}
											?>
										<?php
											if ($userItems["values03"] != "") {
												echo "<span id='user_values03' class='user_values'>" . $userItems["values03"] . "</span>";
											}
											?>
									</td>
								</tr>
							<?php
							endif
							?>
							<tr>
								<td>
									<hr />
								</td>
							</tr>
							<tr>
								<td>
									↓Facebookグループはこちらから♪
								</td>
							</tr>
							<tr>
								<td>
									<a target="_blank" href="https://www.facebook.com/groups/323643691866455/">
										<img id="fb_title" class="side_banner" src="<?= base_url(); ?>images/fb_title.png" />
									</a>
								</td>
							</tr>
						</table>
					</div><!-- side_user_info -->
				</div><!-- body_area_side -->
			</div> <!-- body_area -->

		</div> <!-- main -->

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div> <!-- container -->

	<!-- メニューダイアログ -->
	<?php
	$this->load->view('parts/parts_action_menu', $actionMenuItems);
	?>

	<!-- アクション追加メニューダイアログ -->
	<?php
	$this->load->view('parts/parts_action_add_menu', $actionAddMenuItems);
	?>

	<!-- ミッション編集ダイアログ -->
	<?php
	$this->load->view('parts_top/parts_top_mission_edit', $userItems);
	?>

	<!-- 価値観編集ダイアログ -->
	<?php
	$this->load->view('parts_top/parts_top_values_edit', $userItems);
	?>

	<script>
		$(function() {
			

			// 共通初期化処理を呼び出す
			// アクションメニュー
			$('#actionMenuModal').on('show.bs.modal', actionMenuModal);

			// アクション追加メニュー
			$('#actionAddMenuModal').on('show.bs.modal', function(event) {
				console.log("b-1");
			});

			// ミッション編集画面
			$('#missionEditModal').on('show.bs.modal', function(event) {
				var button = $(event.relatedTarget); //モーダルを呼び出すときに使われたボタンを取得
				var mission = button.data('mission');
				var editId = button.data('edit_id');

				$('#mission_edit_txtMission').val(mission);
				$('#mission_edit_txtEditId').val(editId);
			});

			// 価値観編集画面
			$('#valuesEditModal').on('show.bs.modal', function(event) {
				var button = $(event.relatedTarget); //モーダルを呼び出すときに使われたボタンを取得
				var values01 = button.data('values01');
				var values02 = button.data('values02');
				var values03 = button.data('values03');

				var values01Id = button.data('values01_id');
				var values02Id = button.data('values02_id');
				var values03Id = button.data('values03_id');

				$('#values_edit_txtValues01').val(values01);
				$('#values_edit_txtValues02').val(values02);
				$('#values_edit_txtValues03').val(values03);

				$('#values_edit_txtValues01Id').val(values01Id);
				$('#values_edit_txtValues02Id').val(values02Id);
				$('#values_edit_txtValues03Id').val(values03Id);
			});

			/* ミッション編集ダイアログ */
			initPartsTopMissionEdit();

			/* 価値観編集ダイアログ */
			initPartsTopValuesEdit();
		});

		/*
		 * アクション履歴
		 */
		function actionlogFromActionTriggerId(actionTriggerId) {
			window.location.href = '<?= base_url(); ?>actionlog_list/actionTriggerIdFilter/' + actionTriggerId;
		}
	</script>

</body>

</html>