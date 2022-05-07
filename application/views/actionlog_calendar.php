<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクションログカレンダー画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_calendar.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<script type="text/javascript" src="<?php echo base_url() . "script/action_log.js?" . date('YmdHHmmss'); ?>"></script>
	
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_calendar_0480.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_calendar_0768.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_calendar_1024.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<script>
		$(function() {
			// 共通初期化処理を呼び出す
			actionTrigger_init('<?php echo $editDialogID; ?>', '<?php validation_errors(); ?>');
		});

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
				<div id="sub_menu">
					<?php $this->load->view('parts_actionlog/parts_action_log_menu', $partsMenu); ?>
				</div>
				<div id="result_data" class="result_data"  >
					<div class="result_data_title">
						<span v-html="ymdDisp + ' カレンダー'"></span>
					</div>
					<div id="calendar_area" class="calendar_area" v-cloak>
						<table>
							<thead>
								<tr>
								<th>日</th>
								<th>月</th>
								<th>火</th>
								<th>水</th>
								<th>木</th>
								<th>金</th>
								<th>土</th>
								</tr>
							</thead>
							<tbody>
								<template v-for="item in calendar_items">
									<tr>
										<template v-for="td_item in item.week_items">
											<td v-on:click="showDayActionLogList(td_item.day)"
											v-bind:class="[td_item.class , td_item.day == selectDay ? 'select_day':'' ]">
												<span class="day" :title="td_item.public_holiday" href="#" >{{td_item.day}}</span><br/>
												<span class="action_chk" :title="td_item.action_message" :class="td_item.class" >
													<img :src="td_item.check_image_path"  />
												</span>
											</td>
										</template>
									</tr>
								</template>
							</tbody>
						</table>
					</div>

				</div> <!-- result_data -->

				<!-- 選択日のアクションログリスト -->
				<?php $this->load->view('parts_actionlog/parts_action_log_day_action_log_list', $partsDayActionLogListParams); ?>
			</div> <!-- result_area -->
		</div>

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div>

	<script type="text/javascript" >

		var calendar_items = [
			<?php
			$this->lang->load('calendar');

			$week = 0;
			$currendMonth = convDateTime($partsActionLogFilterParams["ymd"]);
			$today = new DateTime('now');

			foreach ($actionLogRecords as $item) {
				if($week == 0){
					echo "{week_items:[";
				}

				$datetime = new DateTime($item["ymd"]);

				$class = "otherMonth";
				$publicHoliday = "";
				if($currendMonth->format("m") == $datetime->format("m")){
					$class = "currentMonth";

					// 祝日
					$key = str_replace("/","",'calendar_' . $item["ymd"]);
					$publicHoliday = $this->lang->line($key);
					if($publicHoliday){
						$class .= " public_holiday";
					}
					// 曜日
					$class .= " week_" . $week;
				}
				if($today->format("Ymd") == $datetime->format("Ymd") ){
					// 今日
					$class .= " today";
				}

				
				$actionMessage = "";
				$checkImagePath = "";
				if($item["cnt"] > 0){
					$class .= " check_on";
					$checkImagePath = base_url() . "images/actcal_chk_on.png";

					$actionMessage = "実行回数：" . $item["cnt"] . "回";
				}
				else{
					$checkImagePath = base_url() . "images/actcal_chk_off.png";
					$class .= " check_off";
				}

				echo "{";
				echo "day:" . intval($datetime->format("d")) . ",";
				echo "ymd:'" . $datetime->format("Y/m/d") . "',";
				echo "action_message:'" .$actionMessage . "',";
				echo "check_image_path:'" . $checkImagePath . "',";
				echo "class:'" . $class . "',";
				echo "public_holiday:'" . $publicHoliday . "',";
				echo "},";

				if($week == 6){
					echo "]},";
					$week = 0;	
				}
				else{
					$week ++;
				}
			}
			if($week != 0){
				echo "]},";
			}
			?>
		];

		var result_data = new Vue({
			el: "#result_data",
			data: {
				ymdDisp: '<?= getDateDispStr($partsActionLogFilterParams['ymd']) ?>',
				selectDay: 0,
				yearMonth: '<?= $partsActionLogFilterParams["ymd"] ?>',
				ymdDisp: '<?= getDateDispStr($partsActionLogFilterParams["ymd"]) ?>',
				prevDateClick: "moveDateClick(-1,'<?= $partsActionLogFilterParams["ymdType"] ?>');",
				nextDateClick: "moveDateClick(1,'<?= $partsActionLogFilterParams["ymdType"] ?>');",
				calendarDayClick: "openActionLogDayList('<?= base_url(); ?>','<?= $partsActionLogFilterParams["ymd"] ?>');",
				
				calendar_items: calendar_items
			},
			mounted : function(){
				var dt = new Date();
				var nowYearMonth = getYearMonthString(dt);
				
				if(nowYearMonth == this.yearMonth.replace('-','/')){
					this.selectDay = dt.getDate();

					// クリックされた時にTooltip表示時の日付を「選択日アクションログリスト」にセット
					day_action_log_list.selectDay = this.selectDay ;
					day_action_log_list.day = this.selectDay ;
				}
			},
			methods: {
				/*
				* アクションログ（リスト）日に移動
				*/
				openActionLogDayList: function (ymd) {

					openActionLogDayList('<?= base_url(); ?>',ymd);
				},
				showDayActionLogList: function(day){
					this.selectDay = day;

					// クリックされた時にTooltip表示時の日付を「選択日アクションログリスト」にセット
					day_action_log_list.selectDay = day;
					day_action_log_list.day = day_action_log_list.selectDay;
				}
			}

		});

	</script>

</body>

</html>