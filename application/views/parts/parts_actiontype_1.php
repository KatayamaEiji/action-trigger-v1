<div class="action_type_1_area">
	<h2>
		<?php echo $actionRunInfo["action_type_name"] ?>
	</h2>

	<div class="action_type_1_time_area">
		<div class="action_type_1_basic_complete_time">
			<span>目標時間：</span>{{basic_complete_time_str}}
		</div>
		<div class="action_type_1_time">

			<div id="action_type_1_time_counter0" class="action_type_1_time_counter">
				<template v-for="item in timer_img_items">
					<img class="img_num" :src="item.src" :style="item.style" />
				</template>
			</div>

			<div id="action_type_1_time_counter1" class="action_type_1_time_counter">
				<template v-for="item in timer_img_items">
					<img class="img_num" :src="item.src" :style="item.style" />
				</template>
			</div>

			<div id="action_type_1_time_counter_sp1" class="action_type_1_time_counter_sp">
				<img class="img_sp" src="<?php echo base_url() . "images/actp_cd_timer_sp.png"; ?>" />
			</div>

			<div id="action_type_1_time_counter2" class="action_type_1_time_counter">
				<template v-for="item in timer_img_items">
					<img class="img_num" :src="item.src" :style="item.style" />
				</template>
			</div>

			<div id="action_type_1_time_counter3" class="action_type_1_time_counter">
				<template v-for="item in timer_img_items">
					<img class="img_num" :src="item.src" :style="item.style" />
				</template>
			</div>

			<div id="action_type_1_time_counter_sp2" class="action_type_1_time_counter_sp">
				<img class="img_sp" src="<?php echo base_url() . "images/actp_cd_timer_sp.png"; ?>" />
			</div>

			<div id="action_type_1_time_counter4" class="action_type_1_time_counter">
				<template v-for="item in timer_img_items">
					<img class="img_num" :src="item.src" :style="item.style" />
				</template>
			</div>

			<div id="action_type_1_time_counter5" class="action_type_1_time_counter">
				<template v-for="item in timer_img_items">
					<img class="img_num" :src="item.src" :style="item.style" />
				</template>
			</div>
		</div> <!-- action_type_1_time -->
	</div> <!-- action_type_1_time_area -->
</div><!-- action_type_1_area -->