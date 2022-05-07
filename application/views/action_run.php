<!DOCTYPE html>
<html lang="ja">
<?php
$actionType = $actionRunInfo["action_type"];
?>

<!-------------------------------------------------------------------------
アクション実行画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>

	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_run.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_run_0480.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_run_0768.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/action_run_1024.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" />
	<!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js"></script>-->
	<!--<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>-->
	<script src="<?php echo base_url(); ?>node_modules/push.js/bin/push.min.js"></script>
	<script src="<?php echo base_url(); ?>node_modules/marked/marked.min.js"></script>

	<script type="module" src="<?php echo base_url() . "script/action_run.js?" . date('YmdHHmmss'); ?>"></script>
	<script type="module" src="<?php echo base_url() . "script/parts_actiontype_" . $actionType . ".js?" . date('YmdHHmmss'); ?>"></script>

</head>

<body>

	<div id="container" v-cloak>
		<div id="header">
			<?php $this->load->view('parts/parts_header'); ?>
		</div>

		<div id="menu">
			<!-- メニュー -->
			<div id="menu_area">
				<a href="#" v-on:click="cancelAction">実行をキャンセル</a>
			</div>
		</div>

		<div id="main">
			<form id="completeform" :action="actionPath" method="post">
				<input type="hidden" name="actionFlg" id="actionFlg" :value="actionFlg" />
				<input type="hidden" name="actionId" id="actionId" :value="actionId" />
				<input type="hidden" name="actionTriggerId" id="actionTriggerId" :value="actionTriggerId" />
				<input type="hidden" name="actionLogId" id="actionLogId" :value="actionLogId" />
				<input type="hidden" name="actionStatus" id="actionStatus" v-model="actionStatus" />
				<input type="hidden" name="actionTimeFrom" id="actionTimeFrom" :value="actionTimeFrom" />
				<input type="hidden" name="basicCompleteTime" id="basicCompleteTime" :value="basicCompleteTime" />
				<input type="hidden" name="reDispId" id="reDispId" :value="reDispId" />

				<div id="main_container">
					<div id="action_title_container_item" v-cloak>
						<div class="action_title">
							<h1>
								<img class="action_type" :src="actionTypeImgSrc" />

								{{ actionTitle }}
							</h1>
							<a class="cancel_action" href="#" v-on:click="cancelAction">
								<img class="btn_cancel" :src="baseUrl + 'images/ic_action_cancel.png'" />
							</a>
						</div>
					</div>
					<div id="action_run_container_item" v-cloak>
						<transition name="fade" mode="out-in" v-on:after-enter="actionRunContaineItemAfterEnter">
							<div v-if="isACTION_STATE_VERIFICATION" key="verification">
								<p>
									本日のアクション実行回数は {{ actionCnt }} 回です。<br />
									実行する準備が出来ましたら「実行」ボタンをクリックしてください。
								</p>
							</div>
							<div v-else-if="isACTION_STATE_READY" key="ready">
								<!-- ready : よ～いどん！ -->
								<div id="actionReadyMessage" class="actionReadyMessage alert">
									<img id="member1" src="<?php echo base_url(); ?>images/action_ready.gif"></img>
								</div>
							</div>
							<div v-else-if="isACTION_STATE_START" key="start">
								<!-- アクション実行中 -->
								<div v-show="actionImageFlg" class="action_image_area">
									<img :src="actionImagePath" />
								</div>

								<div class="action_info">
									<!-- アクション実行状態情報 -->
									<div class="action_run_info">
										<h1>
											アクション情報
										</h1>
										<div>
											<p><b>現在実行中</b>です。<br />
										</div>
										<div class="action_type_area">
											<action-type-component :prop="actionTypeProp" :action_status="actionStatus"
											v-on:action-complete="setActionRunComplete($event.actionTimeTo)"
											v-on:complete-button-disable="setDisableCompleteButton(true)"
											v-on:complete-button-enabled="setDisableCompleteButton(false)"
											></action-type-component>
										</div> <!-- action_type_area -->
									</div>

									<div v-show="actionMessage">
										<h1>
											効果、達成後のイメージ
										</h1>
										<div v-html="actionMessage"></div>
									</div> <!-- actionMessage -->

									<div v-show="actionDescription">
										<h1>
											実行方法
										</h1>
										<div v-html="actionDescription"></div>
									</div> <!-- actionDescription -->
								</div>
								<!--action_info-->

							</div>
							<div v-else-if="isACTION_STATE_STOP" key="stop">
								<p>現在停止中です。</p>
							</div>
							<div v-else-if="isACTION_STATE_COMPLETE" key="complete">
								<!-- COMPLETE : 達成おめでとう！ -->
								<div id="actionCompleteMessage" class="actionCompleteMessage alert">
									<img id="member1" src="<?php echo base_url(); ?>images/action_complete.gif"></img>
								</div>
							</div>
						</transition>
					</div>

					<div id="button_field_container_item" v-cloak>
						<complete-button :disabled="completeButtonDisabled" 
							:action_status="actionStatus"
							:action_type="actionType"
							v-on:complete-action="setActionRunComplete('')"
							v-on:cancel-action="cancelAction"
							>
						</complete-button>
					</div> <!-- button_field_container_item -->
				</div> <!-- main_container -->
			</form>
		</div> <!-- main -->

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div> <!-- container -->

	<?php $this->load->view('parts_action_run/parts_complete_button'); ?>
	<?php $this->load->view('parts_action_run/parts_actiontype_' . $actionType); ?>

	<script type="module">
		import {ACTION_LOG} from '<?= base_url();?>script/common.js';

		var timerImgItems = [
			<?php

			for ($i = 0; $i < 10; $i++) {
				$idxZero = str_pad($i, 2, 0, STR_PAD_LEFT);

				echo "{";
				echo "id: " . $i . ",";
				echo "src: '" . base_url() . "images/actp_cd_timer" . $idxZero . ".png',";
				if ($i === 0) :
					echo "style: 'z-index:2'";
				else :
					echo "style: 'z-index:0'";
				endif;
				echo "},";
			}
			?>
		];

		
		var audioElem;

		/**
		 * ファンファーレ
		 */
		function playSoundFanfare() {
			audioElem = null;
			audioElem = new Audio();
			audioElem.src = "<?php echo base_url(); ?>sound/fan.mp3";
			audioElem.volume = 1;
			audioElem.play();
		}

		var container = new Vue({
			el: "#container",
			data: {
				reDispId: '<?= $reDispId ?>',
				baseUrl: '<?= base_url() ?>',

				actionFlg: <?= js_bool_string($actionFlg) ?>,
				actionId: <?= $actionRunInfo["action_id"] ?>,
				actionTriggerId: <?= js_numeric_string($actionRunInfo["action_trigger_id"]) ?>,
				actionLogId: <?= js_numeric_string($actionRunInfo["action_log_id"]) ?>,
				actionTimeFrom: '<?= $actionRunInfo["action_time_from"] ?>',
				basicCompleteTime: '<?= $actionRunInfo["basic_complete_time"] ?>',

				actionTitle: '<?= $actionRunInfo["action_title"] ?>',
				actionImageFlg: <?= js_bool_string($actionRunInfo["action_image_flg"]) ?>,
				actionImagePath: '<?= base_url() . $actionRunInfo["action_image_path"] ?>',
				actionStatus: <?= $actionRunInfo["action_status"] ?>,
				actionType: <?= $actionRunInfo["action_type"] ?>,
				actionTypeName: '<?= $actionRunInfo["action_type_name"] ?>',
				actionTypeTimeRendarKey: 0,
				actionCnt: <?= $actionRunInfo["action_cnt"] ?>,
				basicCompleteTimeStr: '<?= $actionRunInfo["basic_complete_time_str"] ?>',
				actionDescription: marked('<?= js_multi_string($actionRunInfo["action_description"]); ?>'),
				actionMessage: marked('<?= js_multi_string($actionRunInfo["action_message"]); ?>'),

				completeButtonDisabled: true,

				actionTypeProp: {
					baseUrl: '<?= base_url() ?>',
					actionTypeName: '<?= $actionRunInfo["action_type_name"] ?>',
					actionTimeFrom: '<?= $actionRunInfo["action_time_from"] ?>',
					basicCompleteTime: '<?= $actionRunInfo["basic_complete_time"] ?>',
					basicCompleteTimeStr: '<?= $actionRunInfo["basic_complete_time_str"] ?>',
					timerImgItems: timerImgItems
				}
			},
			computed: {
				actionPath: function() {
					switch (this.actionStatus) {
						case ACTION_LOG.ACTION_STATE_VERIFICATION:
							return "<?= base_url() ?>Action_run/actionRunStartValidation";
						case ACTION_LOG.ACTION_STATE_READY:
							return "";
						case ACTION_LOG.ACTION_STATE_START:
							return "<?= base_url() ?>Action_run/actionRunCompleteValidation";
						case ACTION_LOG.ACTION_STATE_STOP:
						case ACTION_LOG.ACTION_STATE_COMPLETE:
							return "<?= base_url() ?>Action_run/actionRunStartValidation";
					}
					return "";
				},
				actionTypeImgSrc: function() {
					var actionTypeZero = ('0' + this.actionType).slice(-2);
					return "<?= base_url() ?>images/com_action_type_" + actionTypeZero + "_on.png";
				},
				isACTION_STATE_VERIFICATION: function() {
					return this.actionStatus === ACTION_LOG.ACTION_STATE_VERIFICATION;
				},
				isACTION_STATE_READY: function() {
					return this.actionStatus === ACTION_LOG.ACTION_STATE_READY;
				},
				isACTION_STATE_START: function() {
					return this.actionStatus === ACTION_LOG.ACTION_STATE_START;
				},
				isACTION_STATE_STOP: function() {
					return this.actionStatus === ACTION_LOG.ACTION_STATE_STOP;
				},
				isACTION_STATE_COMPLETE: function() {
					return this.actionStatus === ACTION_LOG.ACTION_STATE_COMPLETE;
				}
			},
			watch: {
				actionStatus: function(val) {
					this.chgActionStatus();
				},
				actionTimeFrom: function(val) {
					this.actionTypeProp.actionTimeFrom = val;
				}
			},
			methods: {
				chgActionStatus: function() {
					var entrySubmit = $("#entry_submit");

					// コンソールログ出力
					ACTION_LOG.consoleLogActionState(this.actionStatus);

					switch (this.actionStatus) {
						case ACTION_LOG.ACTION_STATE_VERIFICATION:
							break;
						case ACTION_LOG.ACTION_STATE_READY:
							this.setActionRunReady();
							break;
						case ACTION_LOG.ACTION_STATE_START:
							break;
						case ACTION_LOG.ACTION_STATE_STOP: // 停止
							break;
						case ACTION_LOG.ACTION_STATE_COMPLETE: // 達成
							break;
					}
				},
				/**
				 * transition のEnter完了後に実行
				 */
				actionRunContaineItemAfterEnter: function() {
					switch (this.actionStatus) {
						case ACTION_LOG.ACTION_STATE_VERIFICATION:
							break;
						case ACTION_LOG.ACTION_STATE_READY:
							break;
						case ACTION_LOG.ACTION_STATE_START:
							break;
						case ACTION_LOG.ACTION_STATE_STOP:
							break;
						case ACTION_LOG.ACTION_STATE_COMPLETE:
							//actionMessage('達成おめでとう！！');
							this.showActionCompleteMessage();
							break;
					}
				},
				cancelAction: function() {
					if (this.actionStatus === ACTION_LOG.ACTION_STATE_START || this.actionStatus === ACTION_LOG.ACTION_STATE_STOP) {
						var result = comConfirm('アクションの実行をキャンセルしてよろしいですか？');

						if (result) {
							// 戻るを無効にする
							$('#completeform').attr('action', '<?php echo base_url(); ?>action_run/cancelActionRunLog');

							$('#completeform').submit();
						}
					} else {
						$('#completeform').attr('action', '<?php echo base_url(); ?>action_run/cancelActionRunNormal');

						$('#completeform').submit();
					}
					return false;
				},
				/**
				 * アクション実行：Ready状態にする。
				 */
				setActionRunReady: function() {
					setTimeout(container.setActionRunStart,3200);

					/*
					$('#actionReadyMessage').fadeIn(500);
					setTimeout(function() {
						$('#actionReadyMessage').fadeIn(1500);
						setTimeout(function() {
							$('#actionReadyMessage').fadeOut(1000);
							setTimeout(function() {
								container.setActionRunStart();

								//$("#actionMessage").remove();
							}, 1000);
						}, 1500);
					}, 500);*/
				},
				showActionCompleteMessage: function() {
					playSoundFanfare(); // ファンファーレ
					$('#actionCompleteMessage').fadeIn(500);
				},
				/**
				 * アクションスタート
				 */
				setActionRunStart: function() {
					$.ajax({
							url: '<?= base_url(); ?>Action_run/actionRunStart/',
							type: 'POST',
							data: {
								'actionId': this.actionId,
								'actionTriggerId': this.actionTriggerId
							}
						})
						// Ajaxリクエストが成功した時発動
						.done((data) => {
							this.actionTimeFrom = data.actionTimeFrom;
							this.actionLogId = data.actionLogId;

							this.actionStatus = Number(data.actionStatus); // actionStatus変更イベント発動
						})
						// Ajaxリクエストが失敗した時発動
						.fail((data) => {
							console.error(data);
						})
						// Ajaxリクエストが成功・失敗どちらでも発動
						.always((data) => {

						});
				},
				/**
				 * アクションコンプリート（達成）
				 */
				setActionRunComplete: function(actionTimeTo) {
					$.ajax({
							url: '<?= base_url(); ?>Action_run/actionRunComplete/',
							type: 'POST',
							data: {
								'actionLogId': this.actionLogId,
								'actionTimeTo': actionTimeTo
							}
						})
						// Ajaxリクエストが成功した時発動
						.done((data) => {
							console.log("setActionRunComplete:" + data.actionStatus);
							this.actionStatus = Number(data.actionStatus); // actionStatus変更イベント発動

							Push.create('達成おめでとう！', {
								body: 'アクションが達成されました。',
								icon: '<?= base_url() ?>images/system.ico',
								timeout: 8000, // 通知が消えるタイミング
								vibrate: [100, 100, 100], // モバイル端末でのバイブレーション秒数
								onClick: function() {
									// 通知がクリックされた場合の設定
									console.log(this);
								}
							});

						})
						// Ajaxリクエストが失敗した時発動
						.fail((data) => {
							console.error(data);
						})
						// Ajaxリクエストが成功・失敗どちらでも発動
						.always((data) => {

						});
				},
				setDisableCompleteButton : function(disableFlg){
					this.completeButtonDisabled = disableFlg;
				}
			}
		});

		/**
		 * 起動時
		 */
		$(function() {
			// 起動時メッセージ
			//startAlert();
			container.chgActionStatus(); // action_status変更イベント発動

			// リロードを繰り返し、画面スリープを防ぐ
			/*setTimeout(function() {
				location.reload();
			}, 30000);*/

			Push.Permission.request();
		});

	</script>
</body>

</html>