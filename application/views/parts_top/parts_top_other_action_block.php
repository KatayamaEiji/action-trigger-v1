<?php
/* 今日のアクション以外での実行 */
$actionTypeZero = str_pad($action_type, 2, 0, STR_PAD_LEFT);

$actionRunFlg = $action_cnt > 0?"on":"off";
?>
<div class="action_block">
<div class="action_block_line">
    <div class="action_table_header">
        <?= $rowno ?>.
    </div>

    <div class="action_table_menu">
        <span class="far fa-caret-square-down hover_pointer" data-toggle="modal" data-target="#actionMenuModal"
            data-action_id="<?= $action_id ?>"
            data-action_trigger_id="<?= $action_trigger_id ?>"
            data-action_title="<?= $action_title ?>"
            data-add_action_flg="<?= $add_action_flg ?>"
            data-create_flg="<?= $create_flg ?>"
            data-disp_id="<?= $dispId ?>" ></span>
    </div>
    <div class="action_main">
        <img class="action_type" src="<?= base_url() . "images/com_action_type_" . $actionTypeZero . "_on.png" ?>" />
        <span><?=  $action_title ?></span>
        <img class="action_type" src="<?= base_url() . "images/action_check_" . $actionRunFlg . ".png" ?>" />
    </div>

    <div class="action_fotter">
        <div class="action_fotter_message ">
            今日の実行回数：<?= $action_cnt ?>
        </div>
        
        <?php 
        $pickUp = "";
        if($action_cnt == 0){
            $pickUp = "fuwafuwa";
        }
        ?>
        <button class='btn btn-primary <?= $pickUp ?>' 
        onClick="location.href='<?= base_url();?>action_run/index/<?= $dispId ?>/<?= $action_id ?>/<?= $action_trigger_id ?>/1';return false;">
        <i class="fas fa-play"></i>&nbsp;アクション実行
        </button>
    </div>
</div><!-- action_block_line -->
</div><!-- action_block -->