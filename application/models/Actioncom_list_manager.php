<?php
class Actioncom_list_manager extends CI_Model{
	/**
	 * 共有アクションリストを取得する
	 */
	public function getActionComList($userId,$findActionInfo){
		$actionTitle = $findActionInfo['actionTitle'];

		$sql = <<< SQL
select 
    m_action.action_id,
    m_action.action_title,
    m_action.action_type,
	v_action_trigger.action_trigger_id,
    (case when v_user_in_action.action_id is null then false else true end) add_action_flg,
	(case m_action.create_user_id when {$userId} then true else false end) create_flg
from
    m_action left join (select action_id from m_user_in_action where user_id = {$userId} and delete_flg = false) v_user_in_action
    on m_action.action_id = v_user_in_action.action_id
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
    m_action.delete_flg = false
and m_action.enable_flg = true
and m_action.auth_type = 1
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
			$items = array(
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_trigger_id" => $row->action_trigger_id,
				"action_type" => $row->action_type,
				"add_action_flg" => $row->add_action_flg,
				"create_flg" => $row->create_flg
			);

			array_push($resItems,$items);
		}
		return $resItems;
	}

}