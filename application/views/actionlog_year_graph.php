<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
アクションロググラフ画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog_year_graph.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/actionlog.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<script type="text/javascript" src="<?php echo base_url() . "script/action_log.js?" . date('YmdHHmmss'); ?>"></script>

	<!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>-->
	<script src="<?= base_url() . $chartjsUrl ?>"></script>

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

			<div id="sub_menu">
				<?php $this->load->view('parts_actionlog/parts_action_log_menu', $partsMenu); ?>
			</div>

			<!-- 出力結果 -->
			<div id="result_area" class="result_area">
				<div id="result_data" class="result_data" >
					<div class="result_data_title">
						<span v-html="ymdDisp + ' 年間グラフ'"></span>
					</div>
					<div id="graph_body" class="graph_body">
						<canvas id="actionloggraph_canvas"></canvas>
					</div>
				</div>
			</div>

			<!-- 画面情報保持 -->
			<div id="disp_info">
				<input type="hidden" name="select_month" id="select_month" :value="select_month" />
			</div>
		</div>

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>

		<script>
			var result_data = new Vue({
				el: "#result_data",
				data: {
					ymdDisp: '<?= getDateDispStr($partsActionLogFilterParams['ymd']) ?>',
				}
			});

			var disp_info = new Vue({
				el: "#disp_info",
				data: {
					select_month: null
				}
			});


			var labelItems = [
				<?php
				foreach ($actionLogRecords as $item) {
					echo convDateTime($item["ym"])->format('n') . ",";
				}
				?>
			];
			var cntItems = [
				<?php
				foreach ($actionLogRecords as $item) {
					echo $item["cnt"] . ",";
				}
				?>
			];
			var sumTimeItems = [
				<?php
				$maxSumTime = getItemsMaxValue($actionLogRecords, "sumtime");
				$unit = getSumTimeUnit($maxSumTime);
				$unitName = getUnitName($unit);

				foreach ($actionLogRecords as $item) {
					echo round($item["sumtime"] / $unit, 2) . ",";
				}
				?>
			];
			var sumTimeUnitName = '<?= $unitName ?>';

			var actionloggraph_canvas = document.getElementById('actionloggraph_canvas');

			if (window.innerWidth > window.innerHeight) {
				// 横長の場合
				$("#graph_body").attr('style', "position: relative; padding:0px;margin:0px; height:500px;width:<?= count($actionLogRecords) * 120 ?>px");
			} else {
				// 縦長の場合
				$("#graph_body").attr('style', "position: relative; padding:0px;margin:0px; height:700px;width:<?= count($actionLogRecords) * 120 ?>px");
			}


			var ctx = actionloggraph_canvas.getContext('2d');

			Chart.pluginService.register({
				beforeDraw: function(c) {
					if (c.config.options.chartArea && c.config.options.chartArea.backgroundColor) {
						var ctx = c.chart.ctx;
						var chartArea = c.chartArea;
						ctx.save();
						ctx.fillStyle = c.config.options.chartArea.backgroundColor;
						ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
						ctx.restore();
					}
				}
			});

			new Chart(ctx, {
				"type": "bar",
				"data": {
					"labels": labelItems,
					"datasets": [{
						"label": "合計回数",
						"data": cntItems,
						"fill": false,
						"backgroundColor": "rgba(255, 99, 132, 0.2)",
						"borderColor": "rgb(255, 99, 132)",
						"borderWidth": 1,
						yAxisID: "y-axis-1", // 追加
					}, {
						"label": "合計時間",
						"data": sumTimeItems,
						"fill": false,
						"backgroundColor": "rgba(128, 99, 132, 0.2)",
						"borderColor": "rgb(128, 99, 132)",
						"borderWidth": 1,
						yAxisID: "y-axis-2", // 追加
					}]
				},
				"options": {
					// グラフエリアのオプション
					chartArea: {
						backgroundColor: 'rgba(255, 255, 255)'
					},
					responsive: true,
					maintainAspectRatio: false,
					title: {
						display: false
					},
					"legend": { //凡例設定
						"display": true, //表示設定

						labels: {
							"fontSize": 30,
						}
					},
					"scales": {
						"yAxes": [{
							id: "y-axis-1", // Y軸のID
							position: "left", // どちら側に表示される軸か？
							"ticks": {
								"beginAtZero": true,
								"fontSize": 30,
								"stepSize": 1,
								callback: function(value, index, values) {
									return value + ' 回';
								}
							},
							"scaleLabel": {
								//表示されるy軸の名称について
								"display": true, //表示するか否か
								"fontSize": 30
							}
						}, {
							id: "y-axis-2", // Y軸のID
							position: "right", // どちら側に表示される軸か？
							"ticks": {
								"beginAtZero": true,
								"fontSize": 30,
								"stepSize": 1,
								callback: function(value, index, values) {
									return value + " " + sumTimeUnitName;
								}
							},
							"scaleLabel": {
								//表示されるy軸の名称について
								"display": true, //表示するか否か
								"fontSize": 30
							}
						}],
						"xAxes": [{
							"ticks": {
								"beginAtZero": true,
								"fontSize": 30,
								"stepSize": 1,
								callback: function(value, index, values) {
									return value + " 月";
								}
							},
							"scaleLabel": {
								//表示されるy軸の名称について
								"display": true, //表示するか否か
								"fontSize": 30
							}
						}]
					},
					layout: { //レイアウト
						padding: { //余白設定
							left: -20,
							right: -20,
							top: 0,
							bottom: -50
						}
					},
					tooltips: {
						titleFontSize: 30,
						"bodyFontSize": 30,
						callbacks: {
							title: function(tooltipItem, data) {
								disp_info.select_month = tooltipItem[0].xLabel;
								return tooltipItem[0].xLabel + " 月";
							},
							label: function(tooltipItem, data) {
								if (tooltipItem.datasetIndex == 0) {
									return ["実行回数:" + tooltipItem.yLabel + " 回"];
								} else {
									return ["合計時間:" + tooltipItem.yLabel + " " + sumTimeUnitName];
								}
							}
						}
					}
				}
			});

			// グラフがクリックされたとき
			actionloggraph_canvas.addEventListener('click', function(e) {
				if (disp_info.select_month !== null) {
					addFilterMonth(disp_info.select_month);
				}
			});
		</script>
	</div>
</body>

</html>