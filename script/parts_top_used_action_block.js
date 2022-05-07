
Vue.component('used_action_block', {
    props: ['base_url','disp_id','item'],
    data: function() {
        /*
        				echo "actionId : '" . $item['action_id'] . "',";
				echo "actionTitle : '" . $item['action_title'] . "',";
				echo "actionType : " . $item['action_type'] . ",";
				echo "actionTimeTo : '" . $item['action_time_to'] . "',";
				echo "actionTimeSpan : '" . $item['action_time_span'] . "',";
				echo "actionCnt : '" . $item['action_cnt'] . "',";
				echo "newActionFlg : " . js_bool_string($item['new_action_flg']) . ",";*/
        return {
            actionId : this.item["actionId"],
            actionTitle : this.item["actionTitle"],
            actionTimeTo : this.item["actionTimeTo"],
            actionTimeSpan : this.item["actionTimeSpan"],
            actionCnt : this.item["actionCnt"],
            actionType : this.item["actionType"],
            actionNowCnt : this.item["actionNowCnt"],
            continueActionCnt : this.item["continueActionCnt"],
            
            newActionFlg : this.item["newActionFlg"],
        }
    },
    template: '#used_action_block',
    computed: {
        actionTypeImgSrc: function() {
            var actionTypeZero = ('0' + this.actionType).slice(-2);
            return this.base_url + "images/com_action_type_" + actionTypeZero + "_on.png";
        },
        checkImgSrc: function() {
            return this.base_url + 'images/action_check_on.png'
        }
    },
    methods : {
        runAction : function(){
            this.$emit('run-action',{"actionId" : this.actionId,"actionTriggerId" : 0});
        }
    }
});
