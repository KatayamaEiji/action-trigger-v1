<?php
if($infoItems):
?>
<!-- お知らせパーツ -->
<div>
<script>
/*
* アクション実行履歴
*/
function partsInfoActionLog(actionId){
	window.location.href = '<?php echo base_url();?>actionlog_list/actionIdFilter/' + actionId;
}
</script>
<table>
<?php
$rowno=1;
    foreach ($infoItems as $row):
        $actionId = $row['action_id'];
     ?>
    <tr>
        <td >
            <?php echo $row['action_day'] ?>&nbsp;-&nbsp;
            「<a href="#" onClick="
                partsInfoActionLog(<?php echo $actionId ?>);return false;"
              ><?php echo $row['action_title'] ?></a>」達成！&nbsp;-&nbsp;
            <?php echo $row['action_cnt'] ?>回
        </td>
    </tr>
<?php 
    $rowno++;
    endforeach;
?>
</table>
</div>
<?php endif; ?>
