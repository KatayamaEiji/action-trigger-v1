<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクションログカレンダー（年）画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_year_calendar.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<script type="text/javascript" src="<?php echo base_url() . "script/action_trigger.js?" . date('YmdHHmmss'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url() . "script/action_log.js?" . date('YmdHHmmss'); ?>"></script>

	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_list_0480.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_list_0768.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_list_1024.css?<?php echo date('YmdHHmmss'); ?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->

	<script type="text/javascript">
		$(function() {
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
				<div id="result_menu">
					<?php $this->load->view('parts_actionlog/parts_action_log_menu', $partsMenu); ?>
				</div>
				<div id="result_data" class="result_data" v-cloak>
					<div class="result_data_title">
						<span v-html="ymdDisp + ' カレンダー'"></span>
					</div>
					<div id="calendar_area" class="calendar_area" v-cloak>
						<table>
							<template v-for="item in calendar_items">
								<tr>
									<template v-for="td_item in item.month_items">
										<td v-bind:class="td_item.class">
											<div class="month_title" v-on:click="addFilterMonth(td_item.month)">
												<span>{{td_item.month}} 月</span>
											</div>
											<div class="month_body">
												<span>合計回数：{{ td_item.cnt }} 回</span><br />
												<span>合計時間：{{ td_item.sumtime }} {{ td_item.unit_name }}</span>
											</div>
										</td>
									</template>
								</tr>
							</template>
						</table>
					</div>

				</div> <!-- result_data -->
			</div> <!-- result_area -->
		</div>

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div>

	<script>
		var calendar_items = [
			<?php
			$idx = 0;
			for($i = 0; $i < 3;$i++){

				echo "{";
				echo "month_items : [";

				for($j = 0;$j < 4;$j++){
					$item = $actionlogRecords[$idx];

					$unit = getSumTimeUnit($item["sumtime"]);
					$unitName = getUnitName($unit);

					$class = "currentYear";

					echo "{";
					echo "month : " . convDateTime($item["ym"])->format('n') . ",";
					echo "cnt : " . $item["cnt"] . ",";
					echo "sumtime : " . round($item["sumtime"] / $unit, 2) . ",";
					echo "class:'" . $class . "',";
					echo "unit_name : '$unitName'";
					echo "},";

					$idx ++ ;
				}
				echo "]";
				echo "},";
			}
			?>
		];

		var result_data = new Vue({
			el: "#result_data",
			data: {
				ymdDisp: '<?= getDateDispStr($partsActionLogFilterParams['ymd']) ?>',
				calendar_items: calendar_items
			},
			methods: {
				addFilterMonth : function(month){
					addFilterMonth(month);
				}
			}
		});
	</script>
</body>

</html>