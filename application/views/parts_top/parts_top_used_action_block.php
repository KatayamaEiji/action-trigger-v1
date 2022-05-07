<script type="text/x-template" id="used_action_block" >
<div class="action_block">
    <div class="action_box_line">
        <div class="action_main" v-on:click="runAction">
            <img class="action_type" :src="actionTypeImgSrc" />
            <div class="action_block_title">
                <h3 >
                    <span >{{ actionTitle }}</span>
                    <img v-if="this.actionNowCnt > 0" class="action_type" :src="checkImgSrc" />
                </h3>
            </div>
            <div class="action_info_block">
                <div class="action_mark_block">
                    <div v-if="newActionFlg" class="new_action">NEW</div>
                    <div v-if="continueActionCnt > 0" class="continue_action"><p>継続中</p></div>
                </div>
            </div>

        </div>

        <div class="action_table_menu">
            <a data-toggle="modal" data-target="#actionMenuModal" 
                :data-action_id="actionId" 
                data-action_trigger_id="0"
                :data-action_title="actionTitle"
                data-add_action_flg="1" 
                data-create_flg="1" 
                :data-disp_id="disp_id">
                <i class="far fa-caret-square-down hover_pointer" ></i>
            </a>
        </div>

    </div><!-- action_block_line -->
</div><!-- action_block -->
</script>