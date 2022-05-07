Vue.component('action-type-component', {
    props: ['prop','action_status'],
    data: function() {
        return {
        }
    },
    template: '#action_type_3_area',
    mounted : function(){
        this.$emit('complete-button-enabled');
    },
});
