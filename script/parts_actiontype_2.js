import {ACTION_LOG} from './common.js';

Vue.component('action-type-component', {
    props: ['prop','action_status'],
    data: function() {
        return {
            baseUrl : this.prop.baseUrl,
            actionTypeName: this.prop.actionTypeName,
            basicCompleteTime: Number(this.prop.basicCompleteTime),
            actionTimeFrom: this.prop.actionTimeFrom,
            actionTimeFromDt: new Date(this.prop.actionTimeFrom),
            basicCompleteTimeStr: this.prop.basicCompleteTimeStr,
            timerImgItems0: JSON.parse(JSON.stringify(this.prop.timerImgItems)),
            timerImgItems1: JSON.parse(JSON.stringify(this.prop.timerImgItems)),
            timerImgItems2: JSON.parse(JSON.stringify(this.prop.timerImgItems)),
            timerImgItems3: JSON.parse(JSON.stringify(this.prop.timerImgItems)),
            timerImgItems4: JSON.parse(JSON.stringify(this.prop.timerImgItems)),
            timerImgItems5: JSON.parse(JSON.stringify(this.prop.timerImgItems)),
            nowTime:new Date(),
            intervalTimer:null
        }
    },
    template: '#action_type_2_area',
    mounted : function(){
        this.$nextTick(function () {
            this.intervalTimer = setInterval(this.updNowTime,1000);
            this.chgActionStatus();
        })
    },
    computed:{
        diffTime : function(){
            return this.nowTime.getTime() - this.actionTimeFromDt.getTime();
        }
    },
    methods: {
        updNowTime : function() {
            // ビュー全体がレンダリングされた後にのみ実行されるコード
            this.nowTime = new Date();
        },
        chgTimeCounter : function() {
            var remainingSecond = this.basicCompleteTime - Math.floor(this.diffTime / (1000));
            if (remainingSecond < 0) {
                // intervalTimerの停止
                clearInterval(this.intervalTimer);
                
                var actionTimeToDt = new Date(this.actionTimeFrom);
                actionTimeToDt.setSeconds(actionTimeToDt.getSeconds() + this.basicCompleteTime);

                // 達成
                this.$emit('action-complete', {actionTimeTo: getStringFromDate(actionTimeToDt)});

                return;
            }

            this.setTimeCounter(remainingSecond);

        },
        setTimeCounter: function(val){
            var h = Math.floor(val / 3600);
            var m = Math.floor(val % 3600 / 60);
            var s = Math.floor(val % 60);

            this.setTimeCounterClear(this.timerImgItems0);
            this.setTimeCounterClear(this.timerImgItems1);
            this.setTimeCounterClear(this.timerImgItems2);
            this.setTimeCounterClear(this.timerImgItems3);
            this.setTimeCounterClear(this.timerImgItems4);
            this.setTimeCounterClear(this.timerImgItems5);

            this.setTimeItemCounter(this.timerImgItems0, Math.floor(h / 10));
            this.setTimeItemCounter(this.timerImgItems1, h % 10);
            this.setTimeItemCounter(this.timerImgItems2, Math.floor(m / 10));
            this.setTimeItemCounter(this.timerImgItems3, m % 10);
            this.setTimeItemCounter(this.timerImgItems4, Math.floor(s / 10));
            this.setTimeItemCounter(this.timerImgItems5, s % 10);
        },
        /**
         * 時刻クリア
         */
        setTimeCounterClear: function (items) {
            for(var j = 0;j < 10 ;j++){
                if(items[j].style !== "z-index:0"){
                    this.setTimeCounterStyle(items, j, "z-index:0");
                }
            }
        },
        /**
         * 時刻セット（個別）
         */
        setTimeItemCounter : function (items,num){
            this.setTimeCounterStyle(items, num, "z-index:2");
        },
        /**
         * 時刻セット
         */
        setTimeCounterStyle: function(items, num, style) {
            var cloned = Object.create(items[num]);
            cloned.style = style;

            this.$set(items, num, cloned);
        },
        chgActionStatus : function(){
            switch (this.action_status) {
                case ACTION_LOG.ACTION_STATE_VERIFICATION:
                    break;
                case ACTION_LOG.ACTION_STATE_READY:
                    break;
                case ACTION_LOG.ACTION_STATE_START:
                    this.$emit('complete-button-disable');
                    break;
                case ACTION_LOG.ACTION_STATE_STOP:
                    break;
                case ACTION_LOG.ACTION_STATE_COMPLETE:
                    break;
            }
        }
    },
    watch: {
        action_status: function(val){
            this.chgActionStatus();
        },
        diffTime: function(val) {
            if(this.diffTime >= 0){
                this.chgTimeCounter();
            }
            else{
                // 開始時間になるまでは待機
                this.setTimeCounter(this.basicCompleteTime);
            }
        },
        basicCompleteTime: function(val) {
            this.setTimeCounter(this.basicCompleteTime);
        }
    }
});
