import {ACTION_LOG} from './common.js';
import {ACTION} from './common.js';

Vue.component('complete-button', {
    props: ['disabled','action_status','action_type'],
    data: function() {
        return {
            
        }
    },
    methods : {
        completeAction :function(){
            this.$emit('complete-action');
        },
        cancelAction : function(){
            this.$emit('cancel-action');
        }

    },
    computed :{
        isACTION_STATE_VERIFICATION: function() {
            return this.action_status === ACTION_LOG.ACTION_STATE_VERIFICATION;
        },
        isACTION_STATE_READY: function() {
            return this.action_status === ACTION_LOG.ACTION_STATE_READY;
        },
        isACTION_STATE_START: function() {
            return this.action_status === ACTION_LOG.ACTION_STATE_START;
        },
        isACTION_STATE_STOP: function() {
            return this.action_status === ACTION_LOG.ACTION_STATE_STOP;
        },
        isACTION_STATE_COMPLETE: function() {
            return this.action_status === ACTION_LOG.ACTION_STATE_COMPLETE;
        },

        isACTION_TYPE_COUNT_DOWN: function(){
            return this.action_type === ACTION.ACTION_TYPE_COUNT_DOWN;
        },
        isACTION_TYPE_COUNT_UP: function(){
            return this.action_type === ACTION.ACTION_TYPE_COUNT_UP;
        }

    },
    template: '#complete_button',
});
