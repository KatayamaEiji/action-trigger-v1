<!-- アクションログ　：　フィルター -->
<div id="filter_area" class="filter_area" v-cloak>
	<form id="filterform" action="<?= base_url() . $dispView ?>/updFilter" method="post">

		<div class="filter_tag">
			<div class="target_action_date">
				<button class="btn btn-info btn-sm" v-on:click="prevDateClick">
					<i class="fas fa-chevron-left"></i>
				</button>
				<span v-html="ymdDisp"></span>
				<button class="btn btn-info btn-sm" v-on:click="nextDateClick">
					<i class="fas fa-chevron-right"></i>
				</button>
			</div>

			<h1>絞込：</h1>
			<template v-if="!actionId && !actionTriggerId && !dayFilterFlg && !monthFilterFlg">
				<span>なし</span>
			</template>

			<template v-if="actionId">
				<span class="filter_item faa-parent animated-hover" onClick="delFilterAction();">
					{{actionTitle}}
					<i class="fas fa-times faa-wrench"></i>
				</span>
			</template>

			<template v-if="actionTriggerId">
				<span class="filter_item faa-parent animated-hover" onClick="delFilterActionTrigger();">
					{{actionTriggerTitle}}
					<i class="fas fa-times faa-wrench"></i>
				</span>
			</template>

			<template v-if="dayFilterFlg">
				<span class="filter_item faa-parent animated-hover" onClick="delFilterDay();">
					{{ymdDisp}}
					<i class="fas fa-times faa-wrench"></i>
				</span>
			</template>

			<template v-if="monthFilterFlg">
				<span class="filter_item faa-parent animated-hover" onClick="delFilterMonth();">
					{{ymdDisp}}
					<i class="fas fa-times faa-wrench"></i>
				</span>
			</template>


			<input type="hidden" name="baseUrl" id="baseUrl" :value="baseUrl" />
			<input type="hidden" name="deleteActionLogId" id="deleteActionLogId" />
			<input type="hidden" name="logType" id="logType" :value="logType" />
			<input type="hidden" name="dispId" id="dispId" :value="dispId" />
			<input type="hidden" name="reDispId" id="reDispId" :value="reDispId" />
			<input type="hidden" name="actionId" id="actionId" :value="actionId" />
			<input type="hidden" name="actionTriggerId" id="actionTriggerId" :value="actionTriggerId" />
			<input type="hidden" name="ymdType" id="ymdType" :value="ymdType" />
			<input type="hidden" name="ymd" id="ymd" :value="ymd" />
			<input type="submit" name="filter_submit" value="検索" id='filter_submit' class='btn btn-primary btn-outline-secondary' style='display:none' />

		</div> <!-- find_input -->
		<?php
		echo form_error('filter_submit');
		?>
	</form><!-- mainform -->
</div>

<script type="text/javascript">
	var dayFilterFlg = false;
	var monthFilterFlg = false;
	var reDispId = '<?= $reDispId ?>';
	var ymdType = '<?= $ymdType ?>';
	if (ymdType == 'day') {
		dayFilterFlg = true;
	}
	if (ymdType == 'month') {
		monthFilterFlg = true;
	}
	var filter_area = new Vue({
		el: "#filter_area",
		data: {
			baseUrl: '<?= base_url() ?>',
			logType: '<?= $logType ?>',
			dispId: '<?= $dispId ?>',
			reDispId: '<?= $reDispId ?>',
			actionId: '<?= $actionId ?>',
			actionTitle: '<?= $actionTitle ?>',
			actionTriggerId: '<?= $actionTriggerId ?>',
			actionTriggerTitle: '<?= $actionTriggerTitle ?>',
			ymdType: '<?= $ymdType ?>',
			ymd: '<?= $ymd ?>',
			ymdDisp: '<?= getDateDispStr($ymd) ?>',
			dayFilterFlg: dayFilterFlg,
			monthFilterFlg: monthFilterFlg
		},
		methods: {
			prevDateClick: function() {
				moveDateClick(-1, this.ymdType);
			},
			nextDateClick: function() {
				moveDateClick(1, this.ymdType);
			},
		}
	});
</script>