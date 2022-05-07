<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクションログ一覧画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?= base_url(); ?>styles/actionlog_list.css?<?= date('YmdHHmmss'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?= base_url(); ?>styles/actionlog.css?<?= date('YmdHHmmss'); ?>" type="text/css" />

	<script type="text/javascript" src="<?= base_url() . "script/action_trigger.js?" . date('YmdHHmmss'); ?>"></script>
	<script type="text/javascript" src="<?= base_url() . "script/action_log.js?" . date('YmdHHmmss'); ?>"></script>

	<!--<link rel="stylesheet" href="<?= base_url(); ?>styles/actionlog_list_0480.css?<?= date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?= base_url(); ?>styles/actionlog_list_0768.css?<?= date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?= base_url(); ?>styles/actionlog_list_1024.css?<?= date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<script type="text/javascript">
		$(function() {
			// 共通初期化処理を呼び出す
			actionTrigger_init('<?= $editDialogID; ?>', '<?php validation_errors(); ?>');

			<?php
			if ($deleteActionLogFlg) {
				echo "feadMessage('アクションログ','削除しました。');";
			} ?>

		});

		/**
		 * アクションログ削除
		 */
		function deleteActionLog(actionLogId, actionTitle) {
			if (!window.confirm(actionTitle + 'のアクションログを削除してよろしいですか？')) {
				return false;
			}

			$('#filterform').attr('action', '<?= base_url(); ?>actionlog_list/actionLogDeleteValidation');

			$('#deleteActionLogId').val(actionLogId);

			$('#filter_submit').click();
		}

		/**
		 * アクションログ編集
		 */
		function editActionLogData(actionLogId) {
			location.href = '<?= base_url() . "actionlog_edit/index/" . $reDispId . "-" . $dispId ?>' + '/' + String(actionLogId);
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

			<!-- アクションログ　フィルター -->
			<?php $this->load->view('parts_actionlog/parts_action_log_filter', $partsActionLogFilterParams); ?>

			<!-- 出力結果 -->
			<div id="result_area" class="result_area">
				<vue-input label="Input" name="input" v-model="form.input"></vue-input>


				<div id="result_menu">
					<?php $this->load->view('parts_actionlog/parts_action_log_menu', $partsMenu); ?>
				</div>
				<div id="result_data" class="result_data" v-cloak>

					<div class="result_data_title">
						<span v-html="ymdDisp + ' リスト'"></span>
					</div>
					<div class="list_area">
						<template v-if="list_items">
							<table>
								<tr>
									<th>日付</th>
									<th colspan="2">実行時間</th>
									<th>アクション</th>
									<th>実行</th>
									<th>編集</th>
									<th>削除</th>
								</tr>
								<template v-for="item in list_items">
									<tr>
										<template v-if="item.view_day_flg">
											<td class="action_from_day date_font" :rowspan="item.action_cnt">
												<a href="#" v-on:click="addFilterDay(item.action_from_day);">
													{{ item.disp_action_from_day }}
												</a>
											</td>
										</template>
										<td class="action_time">
											{{ item.action_from_time }} ～ {{ item.action_to_time }}
										</td>
										<td class="action_time_span">
											{{ item.action_time_span }}
										</td>
										<td class="memo">
											<img class="action_type" :src="item.action_type_src" />

											<a href="#" v-on:click="addFilterAction(item.action_id)">
												{{ item.action_title }}
											</a>
										</td>
										<td>
											<button class='btn btn-primary btn-sm' v-on:click="runAction(item.action_id)">
												<i class="fas fa-play"></i>
											</button>
										</td>
										<td class="edit">
											<a href="#" v-on:click="editActionLogData(item.action_log_id)">
												<i class="far fa-edit"></i>
											</a>
										</td>
										<td class="delete">
											<a href="#" v-on:click="deleteActionLog(item.action_log_id,item.action_title)">
												<i class="far fa-trash-alt"></i>
											</a>
										</td>
									</tr>
								</template>
							</table>

						</template>
						<template v-else>
							<h3>※アクションログはありません。</h3>
						</template>
					</div>

				</div> <!-- result_data -->
			</div> <!-- result_area -->
		</div>

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div>

	<script>
		var list_items = [
			<?php
			$dateValue = "";
			$viewDayFlg = 'false';

			foreach ($actionlogRecords as $item) {
				$viewDayFlg = 'false';

				if ($dateValue != $item['action_from_day']) {
					$viewDayFlg = 'true';
					$dateValue = $item['action_from_day'];
				}

				$actionTypeZero = str_pad($item['action_type'], 2, 0, STR_PAD_LEFT);
				$actionTypeSrc = base_url() . "images/com_action_type_" . $actionTypeZero . "_on.png";

				echo "{";
				echo "view_day_flg : " . $viewDayFlg . ",";
				echo "action_cnt : " . $item['action_cnt'] . ",";
				echo "disp_action_from_day : '" . getDateMDStr($item['action_from_day']) . "',";
				echo "action_from_day : '" . $item['action_from_day'] . "',";
				echo "action_from_time : '" . $item['action_from_time'] . "',";
				echo "action_to_time : '" . $item['action_to_time'] . "',";
				echo "action_time_span : '" . $item['action_time_span'] . "',";
				echo "action_id : " . $item['action_id'] . ",";
				echo "action_log_id : " . $item['action_log_id'] . ",";
				echo "action_type_src : '"  . $actionTypeSrc . "',";
				echo "action_title : '" . $item['action_title'] . "',";
				echo "},";
			}
			?>
		];


		var result_data = new Vue({
			el: "#result_data",
			data: {
				ymdDisp: '<?= getDateDispStr($partsActionLogFilterParams['ymd']) ?>',
				list_items: list_items
			},
			methods: {
				addFilterDay: function(day) {
					addFilterDay(day);
				},
				addFilterAction: function(actionId) {
					addFilterAction(actionId);
				},
				runAction: function(actionId) {
					runAction(actionId, 0);
				},
				editActionLogData: function(actionLogId) {
					editActionLogData(actionLogId);
				},
				deleteActionLog: function(actionLogId, actionTitle) {
					deleteActionLog(actionLogId, actionTitle);
				},
			}
		});
	</script>
</body>

</html>