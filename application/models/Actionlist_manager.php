<?php
class Actionlist_manager extends CI_Model{
	/**
	 * アクションリストを取得する
	 */
	public function getActionList($userId,$findActionInfo){
		$this->load->model(array('actionlog_manager'));

		$actionStatus = actionlog_manager::ACTION_STATUS_COMPLETE;

		$actionTitle = $findActionInfo['actionTitle'];

		$sql = <<< SQL
select 
	m_action.action_id,
	m_action.action_title,
	m_action.action_type,
    v_action_cnt.action_cnt,
	CASE WHEN m_action.create_user_id = {$userId} THEN true ELSE false END edit_flg,
	v_action_trigger.action_trigger_id,
	v_action_trigger.kigen_from,
	v_action_trigger.kigen_to,
	CASE WHEN (CURRENT_TIMESTAMP() - INTERVAL 3 DAY) < m_action.create_date THEN true ELSE false END new_action_flg
from 
	m_user_in_action inner join m_action
	on m_user_in_action.action_id = m_action.action_id
	and m_user_in_action.delete_flg = false
	and m_action.delete_flg = false
	left join (
		select action_id,count(*) action_cnt
		from t_action_log
		where user_id = {$userId}
		and t_action_log.action_status = {$actionStatus}
		and t_action_log.delete_flg = false
		and t_action_log.action_time_from BETWEEN DATE_FORMAT(now(),'%Y/%m/%d 00:00:00') AND DATE_FORMAT(now(),'%Y/%m/%d 23:59:59')
		GROUP BY action_id
	) v_action_cnt
	on m_action.action_id = v_action_cnt.action_id
	left join (select 
			action_id,action_trigger_id,kigen_from,kigen_to
		from m_action_trigger
		where user_id = {$userId}
		and m_action_trigger.kigen_from <= CURRENT_TIMESTAMP
		and CURRENT_TIMESTAMP <= m_action_trigger.kigen_to
		and m_action_trigger.delete_flg = false
	) v_action_trigger
	on m_action.action_id = v_action_trigger.action_id
where 
	m_user_in_action.user_id = {$userId}
SQL;
		if($actionTitle != ""){
			$sql .= " and m_action.action_title like '%{$actionTitle}%'";
		}
		$sql .= " order by m_action.action_title";

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$fromDate = new DateTime($row->kigen_from);
			$toDate = new DateTime($row->kigen_to);
			$sFromDay = $fromDate->format('Y年m月d日');

			$items = array(
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_type" => $row->action_type,
				"action_cnt" => $row->action_cnt,
				"edit_flg" => $row->edit_flg,
				"action_trigger_id" => $row->action_trigger_id,
				"kigen_from" => $row->kigen_from,
				"kigen_to" => $row->kigen_to,
				"new_action_flg" => $row->new_action_flg,
				"add_action_flg" => true,
				"create_flg" => true
			);

			array_push($resItems,$items);
		}
		return $resItems;
	}

}