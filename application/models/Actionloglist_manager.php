<?php
class Actionloglist_manager extends CI_Model{
	/**
	 * アクションログリストを取得する
	 */
	public function getActionLogList($userId,$actionlogfilterParams){
		$this->load->model(array('system_manager','Actionlog_manager'));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$actionStatus = Actionlog_manager::ACTION_STATUS_COMPLETE;

		$ymd = $actionlogfilterParams->ymd;

		$sqlAction = " select action_id,action_title,action_type from m_action where 1 = 1 ";
		if($actionlogfilterParams->actionTitle !== null){
			$sqlAction .= " and m_action.action_title like '%{$actionlogfilterParams->actionTitle}%'";
		}
		if($actionlogfilterParams->actionId !== null){
			$sqlAction .= " and m_action.action_id = {$actionlogfilterParams->actionId} ";
		}

		$actionLogFilter = "";
		if($actionlogfilterParams->actionTriggerId !== null){
			$actionLogFilter .= " and t_action_log.action_trigger_id = {$actionlogfilterParams->actionTriggerId} ";
		}

		$actionLogFilterDate = "";
		if($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_YEAR){
			// 仕様上、指定されない。
		}
		elseif($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_MONTH){
			$actionLogFilterDate = "and DATE_FORMAT(t_action_log.action_time_from , '%Y/%m') = '{$ymd}'";
		}
		elseif($actionlogfilterParams->ymdType === Actionlogfilter_params_entity::YMD_TYPE_DAY){
			$actionLogFilterDate = "and DATE_FORMAT(t_action_log.action_time_from , '%Y/%m/%d') = '{$ymd}'";
		}
				
		$sql = <<< SQL
select 
	t_action_log.action_log_id,
	v_action.action_id,
	v_action.action_title,
	v_action.action_type,
	t_action_log.action_time_from,
	t_action_log.action_time_to,
	t_action_log.action_time_span,
    v_action_cnt.action_cnt
from 
	t_action_log inner join ({$sqlAction}) v_action
    on t_action_log.delete_flg = 0
	and t_action_log.action_status = {$actionStatus}
    and t_action_log.action_id = v_action.action_id
	and t_action_log.user_id = {$userId}
	{$actionLogFilter}
	{$actionLogFilterDate}
	and t_action_log.delete_flg = false
    inner join (select DATE_FORMAT(action_time_from, '%Y%m%d') action_day,count(*) action_cnt
		from t_action_log inner join ({$sqlAction}) v_action
		on t_action_log.delete_flg = 0
		and t_action_log.action_status = {$actionStatus}
		and t_action_log.action_id = v_action.action_id
		and t_action_log.user_id = {$userId}
		{$actionLogFilterDate}
		GROUP BY DATE_FORMAT(action_time_from, '%Y%m%d')
	) v_action_cnt
    on DATE_FORMAT(action_time_from, '%Y%m%d') = v_action_cnt.action_day
order by t_action_log.action_time_from desc
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$fromDate = new DateTime($row->action_time_from);
			$toDate = new DateTime($row->action_time_to);
			$sFromTime = $fromDate->format('H:i');
			$sToTime = $toDate->format('H:i');

			$items = array(
				"action_log_id" => $row->action_log_id,
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_type" => $row->action_type,
				"action_from_day" => $fromDate->format('Y/m/d'),
				"action_from_time" => $sFromTime,
				"action_to_time" => $sToTime,
				"action_time_span" => dispTimeStrFromSec($fromDate,$toDate),
				"action_cnt" => $row->action_cnt
			);

			array_push($resItems,$items);
		}
		return $resItems;
	}
}