<script type="text/x-template" id="continue_action_block" >
<div class="action_block" >
    <!-- 期限が終了し、継続するか確認 -->
    <div class="action_box_line">


        <div class="action_main" >
            <img class="action_type" :src="actionTypeImgSrc" />
            <div class="action_block_title">
                <h3 >
                    <span :class="{del:this.actionCnt > 0}" >{{ actionTitle }}</span>
                    <img v-if="this.actionCnt > 0" class="action_type" :src="checkImgSrc" />
                </h3>
            </div>
            <div class="action_info_block">
                <div class="action_continue_block">
                    <button class='btn btn-primary' v-on:click="continueAction">
                        継続する
                    </button>
                    <button class='btn btn-primary' v-on:click="notContinueAction">
                        継続しない
                    </button>
                </div>

                <div class="action_kigen_block">
                    期間：{{ kigenKikan }}
                </div>
            </div>

        </div>

        <div class="action_table_menu">
            <a data-toggle="modal" data-target="#actionMenuModal" 
                :data-action_id="actionId" 
                :data-action_trigger_id="actionTriggerId"
                :data-action_title="actionTitle"
                data-add_action_flg="1" 
                data-create_flg="1" 
                :data-disp_id="disp_id">
                <i class="far fa-caret-square-down hover_pointer" ></i>
            </a>
        </div>
    </div><!-- action_box_line -->
</div><!-- action_block -->
</script>