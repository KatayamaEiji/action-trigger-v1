// :baseUrl="baseUrl" :dispId="dispId" :item="item"
/*
echo "actionId : '" . $item['action_id'] . "',";
echo "actionTriggerId : '" . $item['action_trigger_id'] . "',";
echo "actionTitle : '" . $item['action_title'] . "',";
echo "actionCnt : '" . $item['action_cnt'] . "',";
echo "actionTypeZero : '" . $actionTypeZero . "',";
echo "kigenKikan : " . $item['kigen_kikan'] . ",";
echo "continueFlg : " . $item['continue_flg'] . ",";
echo "actionRunFlgStr : '"  . $actionRunFlg . "',";
echo "newActionFlg : " . $item['new_action_flg'] . ",";
*/
Vue.component('action_block', {
    props: ['base_url','disp_id','item'],
    data: function() {
        return {
            actionId : this.item["actionId"],
            actionTriggerId : this.item["actionTriggerId"],
            actionTitle : this.item["actionTitle"],
            actionCnt : this.item["actionCnt"],
            actionType : this.item["actionType"],
            kigenKikan : this.item["kigenKikan"],
            
            continueFlg : this.item["continueFlg"],
            newActionFlg: this.item["newActionFlg"],
            continueActionCnt: this.item["continueActionCnt"],
        }
    },
    template: '#action_block',
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
            this.$emit('run-action',{"actionId" : this.actionId,"actionTriggerId" : this.actionTriggerId});
        }
    }
});
