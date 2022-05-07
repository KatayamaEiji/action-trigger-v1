<script type="text/x-template" id="complete_button" >
<div class="button_field compleate_button_area ">

<template v-if="isACTION_STATE_VERIFICATION">
    <button type="button" name="entry_submit" id="entry_submit" class="btn btn-primary" >
        <i class="fas fa-check  faa-wrench animated"></i>
        実行
    </button>
</template>
<template v-else-if="isACTION_STATE_READY">
    <button type="button" name="entry_submit" id="entry_submit" class="btn btn-info" disabled >
        開始待ち・・・
    </button>
</template>
<template v-else-if="isACTION_STATE_START">
    <template v-if="disabled">
        <template v-if="isACTION_TYPE_COUNT_DOWN">
            <button type="button" name="entry_submit" id="entry_submit" class="btn btn-info" disabled >
                Complete 待ち・・・
            </button>
            <span>
                目標時間を達成すれば自動でCompleteされます。
            </span>
        </template>
        <template v-else-if="isACTION_TYPE_COUNT_UP">
            <button type="button" name="entry_submit" id="entry_submit" class="btn btn-info" disabled >
                目標時間待ち・・・
            </button>
            <span>
                目標時間を達成すればCompleteをクリックできます。
            </span>
        </template>
        <template v-else>
            <button type="button" name="entry_submit" id="entry_submit" class="btn btn-info" disabled >
                ------
            </button>
        </template>
    </template>
    <template v-else>
        <button type="button" name="entry_submit" id="entry_submit" class="btn btn-primary"  v-on:click="completeAction">
            <i class="fas fa-check  faa-wrench animated"></i>
            Complete
        </button>
    </template>
</template>
<template v-else-if="isACTION_STATE_STOP">
    <!-- 停止 -->
    <button type="submit" name="entry_submit" id="entry_submit" class="btn btn-primary">
        <i class="fas fa-check faa-wrench animated"></i>
        再開
    </button>
</template>
<template v-else-if="isACTION_STATE_COMPLETE">
    <!-- コンプリート -->
    <button type="submit" name="entry_submit" id="entry_submit" class="btn btn-primary">
        <i class="fas fa-check faa-wrench animated"></i>
        もう一度
    </button>

    <button class='btn btn-primary' name="other_submit" id="other_submit" :style="otherSubmitStyle" v-on:click="cancelAction">
        <i class='fas fa-play'></i>&nbsp;他のアクションへ
    </button>
</template>

</div>
</script>