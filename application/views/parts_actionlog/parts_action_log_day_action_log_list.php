<!-- 一日のアクションログリスト -->
<div id="day_action_log_list" class="day_action_log_list">

	<template v-if="dayActionLogListFlg">
		<h3>{{title}}</h3>
		<table>
			<tr>
				<th colspan="2">実行時間</th>
				<th>アクション</th>
				<th>実行</th>
				<th>編集</th>
				<th>削除</th>
			</tr>
			<template v-for="item in dayActionLogListItems">
				<tr>
					<td>{{ item.actionFromTime }} ～ {{ item.actionToTime }}
					</td>
					<td>{{ item.actionTimeSpan }}</td>
					<td>
						<img class="action_type" :src="item.actionImgSrc" />
						<a href="#" v-on:click="addFilterAction(item.actionId)">{{ item.actionTitle}}</a>
					</td>
					<td>
						<button class='btn btn-primary btn-sm' v-on:click="runAction(item.actionId)">
							<i class="fas fa-play"></i>
						</button>
					</td>
					<td>
						<a href="#" v-on:click="editActionLog(item.actionLogId)">
							<i class="far fa-edit"></i>
						</a>
					</td>
					<td>
						<a href="#" v-on:click="delActionLog(item.actionLogId,item.actionTitle)">
							<i class="far fa-trash-alt"></i>
						</a>
					</td>
				</tr>
			</template>
		</table>
	</template>
	<template v-else-if="day != null">
	<h3>{{title}} のアクションログはありません。</h3>
	</template>

</div>

<script>
	var day_action_log_list = new Vue({
		el: "#day_action_log_list",
		data: {
			selectDay: null,
			day: null,

			/* 2020/02 */
			yearMonth: '<?= $yearMonth ?>',
			actionId: '<?= $actionId ?>',
			actionTriggerId: '<?= $actionTriggerId ?>',
			error: null,
			dayActionLogListFlg: false,
			dayActionLogListItems: null
		},
		computed: {
			title: function (){
				var dtString = getDispDateString(this.yearMonth.replace('/', '-') + "-" + ('0' + this.day).slice(-2));


				return dtString ;
			}
		},
		methods: {
			getUrl: function() {
				var url = '<?php echo base_url(); ?>Actionlog/getDayActionList/';

				url += this.yearMonth.replace('/', '-') + "-" + ('0' + this.day).slice(-2); 
				if (this.actionId !== '') {
					url += "/" + this.actionId
				} else {
					url += "/0"
				}

				return url;
			},
			/*
			 * アクションフィルター追加
			 */
			addFilterAction: function(actionId) {
				addFilterAction(actionId);
			},
			/*
			* アクションの実行
			*/
			runAction: function(actionId){
				location.href='<?php echo base_url(); ?>action_run/index/<?php echo $nextReDispId ?>/' + actionId + '/0/1';
			},
			/**
			 * アクションログ編集
			 */
			editActionLog: function(actionLogId){
				location.href = '<?php echo base_url() . "actionlog_edit/index/" . $nextReDispId ?>' + '/' + actionLogId;
			},
			/**
			 * アクションログ削除
			 */
			delActionLog: function(actionLogId,actionTitle){
				if (!window.confirm(actionTitle + 'のアクションログを削除してよろしいですか？')) {
					return false;
				}

				let params = new URLSearchParams();
				params.append('actionLogId',actionLogId);

				axios.post('<?php echo base_url(); ?>Actionlog/delActionLog/', params)
				.then(response => {
					window.alert(actionTitle + 'のアクションログを削除しました。');

					$('#filter_submit').click();
				}).catch(err => {
					this.error = err
				});
			}
		},
		watch: {
			day: function(val) {
				if(val === null){
					this.dayActionLogListFlg = false;
					this.dayActionLogListItems = null;
				}
				else{
					// アクションログリストの取得
					axios
						.get(this.getUrl())
						.then(response => {
							this.dayActionLogListFlg = false;
							var logItems = new Array();

							if(response.data.findRecords){
								for (key in response.data.findRecords) {
									var item = response.data.findRecords[key];

									var actionTypeZero = ('0' + item.action_type).slice(-2);
									var actionImgSrc = '<?php echo base_url() . "images/com_action_type_" ?>' + actionTypeZero + '_on.png';

									logItems.push({
										"actionLogId": item.action_log_id,
										"actionId": item.action_id,
										"actionTitle": item.action_title,
										"actionImgSrc": actionImgSrc,
										"actionType": item.action_type,
										"actionFromDay": item.action_from_day,
										"actionFromTime": item.action_from_time,
										"actionToTime": item.action_to_time,
										"actionTimeSpan": item.action_time_span
									});
								}
								this.dayActionLogListItems = logItems;
								this.dayActionLogListFlg = true;
							}


						}).catch(err => {
							this.error = err
						});
				}
			}
		}

	});
</script>