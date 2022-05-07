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
	
	<script type="module" src="<?php echo base_url() . "script/parts_top_action_block.js?" . date('YmdHHmmss'); ?>"></script>
	<script type="module" src="<?php echo base_url() . "script/parts_top_continue_action_block.js?" . date('YmdHHmmss'); ?>"></script>
	<script type="module" src="<?php echo base_url() . "script/parts_top_used_action_block.js?" . date('YmdHHmmss'); ?>"></script>
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
					<div class="action_table_list" >
						<div class="action_title">
							<h2>今日のアクション</h2>
							<span class="action_add hover_pointer" data-toggle="modal" data-target="#actionAddMenuModal">
								<img :src="baseUrl + 'images/com_add_button_on.png'" />
							</span>
						</div> <!-- action_title -->
						<div class="action_area">
							<template v-for="item in nowActionRecords">
								<action_block v-if="!item.continueFlg" :base_url="baseUrl" :disp_id="dispId" :item="item"
								v-on:run-action="runAction($event.actionId,$event.actionTriggerId)">
								</action_block>
								<continue_action_block v-else :base_url="baseUrl" :disp_id="dispId" :item="item"
								v-on:continue-action="continueAction($event.actionTriggerId)"
								v-on:not-continue-action="notContinueAction($event.actionTriggerId)">
								</cotinue_naction_block>
							</template>
						</div><!-- action_area -->

						<template v-if="usedActionRecordCount > 0">
						<div class="action_title">
							<h2>よく使うアクション</h2>
						</div> <!-- action_title -->
						<div class="action_area">
							<template v-for="item in usedActionRecords">
								<used_action_block :base_url="baseUrl" :disp_id="dispId" :item="item"
								v-on:run-action="runAction($event.actionId,$event.actionTriggerId)">
								</used_action_block>
							</template>
						</div><!-- action_area -->
						</template>
					</div><!-- action_table_list -->
				</div><!-- #body_area_main -->

				<div id="body_area_side">
					<div class="side_user_info">
						<table>
							<template v-if="mission !== ''">
								<tr>
									<td>
										<span class="edit_item faa-parent animated-hover" data-toggle="modal" 
										data-target="#missionEditModal" :data-mission="mission " data-edit_id="#user_mission" >
										ミッション：
											<i class="fas fa-edit faa-wrench" ></i>
										</span>
										<div>
											<span id='user_mission' class='user_mission' v-html="mission"></span>
										</div>
									</td>
								</tr>
							</template>
							<template v-if="values01 !== '' && values02 !== '' && values03 !== ''  ">
								<tr>
									<td>
										<span class="edit_item faa-parent animated-hover" data-toggle="modal" data-target="#valuesEditModal" 
											:data-values01="values01" 
											:data-values02="values02" 
											:data-values03="values03"
											data-values01_id="#user_values01" data-values02_id="#user_values02" data-values03_id="#user_values03"  >
										価値観：
											<i class="fas fa-edit faa-wrench" ></i>
										</span>

										<template v-if="values01 !== ''">
											<span id='user_values01' class='user_values' v-html="values01"></span>
										</template>
										<template v-if="values02 !== ''">
											<span id='user_values02' class='user_values' v-html="values02"></span>
										</template>
										<template v-if="values03 !== ''">
											<span id='user_values03' class='user_values' v-html="values03"></span>
										</template>
									</td>
								</tr>
							</template>
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
										<img id="fb_title" class="side_banner" :src="baseUrl + 'images/fb_title.png'" />
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

	<?php $this->load->view('parts_top/parts_top_action_block'); ?>
	<?php $this->load->view('parts_top/parts_top_continue_action_block'); ?>
	<?php $this->load->view('parts_top/parts_top_used_action_block'); ?>

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

	<script type="module">
		var nowActionRecords = [
			<?php
			foreach ($nowActionRecords as $item) {
				/*
								"action_id" => $row->action_id,
				"action_trigger_id" => $row->action_trigger_id,
				"action_title" => $row->action_title,
				"action_message" => $row->action_message,
				"action_description" => $row->action_description,
				"action_type" => $row->action_type,
				"action_cnt" => $row->action_cnt,
				"kigen_kikan" => getFromToDayString($row->kigen_from,$row->kigen_to),
				"continue_flg" => $row->continue_flg,
				"new_action_flg" => $row->new_action_flg
				*/

				echo "{";
				echo "actionId : '" . $item['action_id'] . "',";
				echo "actionTriggerId : '" . $item['action_trigger_id'] . "',";
				echo "actionTitle : '" . $item['action_title'] . "',";
				echo "actionCnt : '" . $item['action_cnt'] . "',";
				echo "actionType : " . $item['action_type'] . ",";
				echo "kigenKikan :'" . $item['kigen_kikan'] . "',";
				echo "continueActionCnt : " . $item['continue_action_cnt'] . ",";
				echo "continueFlg : " . js_bool_string($item['continue_flg']) . ",";
				echo "newActionFlg : " . js_bool_string($item['new_action_flg']) . ",";
				echo "},";
			}
			?>
		];

		var usedActionRecords = [
			<?php
			
			foreach ($usedActionRecords as $item) {
				/*
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_message" => $row->action_message,
				"action_description" => $row->action_description,
				"action_type" => $row->action_type,
				"action_time_to" => $row->action_time_to,
				"action_time_span" => $row->action_time_span,
				"action_cnt" => $row->action_cnt,
				"new_action_flg" => $row->new_action_flg,
				*/
				echo "{";
				echo "actionId : '" . $item['action_id'] . "',";
				echo "actionTitle : '" . $item['action_title'] . "',";
				echo "actionType : " . $item['action_type'] . ",";
				echo "actionTimeTo : '" . $item['action_time_to'] . "',";
				echo "actionTimeSpan : '" . $item['action_time_span'] . "',";
				echo "actionCnt : '" . $item['action_cnt'] . "',";
				echo "actionNowCnt : '" . $item['action_now_cnt'] . "',";
				echo "continueActionCnt : '" . $item['continue_action_cnt'] . "',";
				echo "newActionFlg : " . js_bool_string($item['new_action_flg']) . ",";
				echo "},";
			}
			?>
		];

		var main = new Vue({
			el: "#main",
			data: {
				baseUrl : '<?= base_url(); ?>',
				dispId : '<?= $dispId ?>',
				nowActionRecords: nowActionRecords,
				usedActionRecords: usedActionRecords,
				usedActionRecordCount: <?= $usedActionRecordCount ?>,
				mission: '<?= $userItems['mission'] ?>',
				values01: '<?= $userItems['values01'] ?>',
				values02: '<?= $userItems['values02'] ?>',
				values03: '<?= $userItems['values03'] ?>',
			},
			methods: {
				runAction : function(actionId,actionTriggerId){
					location.href= this.baseUrl + 'action_run/index/' + this.dispId + '/' + actionId + '/' + actionTriggerId + '/1';
					return false;
				},
				continueAction : function(actionTriggerId){
					location.href= this.baseUrl + 'top/Continuation/' + actionTriggerId;
					return false;
				},
				notContinueAction : function(actionTriggerId){
					location.href= this.baseUrl + 'top/NotContinuation/' + actionTriggerId;
					return false;
				},
			}
		});
	</script>

</body>

</html>