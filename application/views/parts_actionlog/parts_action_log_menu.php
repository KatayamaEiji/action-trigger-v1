<!-- アクションログメニュー -->
<div id="action_log_menu_area" >
	<template v-if="logType!=='list'">
		<button class="btn btn-info btn-sm" onclick="subMenuClick('list');">
			<i class="fas fa-list"></i>
			リスト
		</button>
	</template>
	<template v-else>
		<div class="btn btn-primary btn-sm" >
			<i class="fas fa-list"></i>
			リスト
		</div>
	</template>

	<template v-if="logType!=='calendar'">
		<button class="btn btn-info btn-sm" onclick="subMenuClick('calendar');">
			<i class="far fa-calendar-alt"></i>
			カレンダー
		</button>
	</template>
	<template v-else>
		<div class="btn btn-primary btn-sm" >
			<i class="fas fa-list"></i>
			カレンダー
		</div>
	</template>

	<template v-if="logType!=='graph'">
		<button class="btn btn-info btn-sm" onclick="subMenuClick('graph');">
			<i class="fas fa-chart-bar"></i>
			グラフ
		</button>
	</template>
	<template v-else>
		<div class="btn btn-primary btn-sm" >
			<i class="fas fa-list"></i>
			グラフ
		</div>
	</template>
</div>

<script  type="text/javascript">
	var action_log_menu_area = new Vue({
		el: "#action_log_menu_area",
		data: {
			logType: '<?= $logType ?>'
		}
	});
</script>
