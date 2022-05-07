<?php
class Top_manager extends CI_Model{

	/**
	 * アクションデータ一覧を取得する。
	 */
	public function getNowActionListData($userId){	
		$this->load->model(array('system_manager','actiontrigger_manager'));

		// 未継続のもののみ
		$continueKbn = actionTrigger_manager::CONTINUE_KBN_MI;

        $sql = <<< SQL
select 
	m_action_trigger.action_id,
    m_action_trigger.action_trigger_id,
	m_action_trigger.kigen_from,
	m_action_trigger.kigen_to,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
    (select count(*) from t_action_log 
     where t_action_log.delete_flg = 0  
     and t_action_log.user_id = m_action_trigger.user_id
     and t_action_log.action_id = m_action_trigger.action_id
     and t_action_log.action_trigger_id = m_action_trigger.action_trigger_id
     and t_action_log.action_status = 3 -- 3:COMPLETE
	 and t_action_log.action_time_from BETWEEN DATE_FORMAT(now(),'%Y/%m/%d 00:00:00') AND DATE_FORMAT(now(),'%Y/%m/%d 23:59:59')
     ) action_cnt,
	 (select count(*) from t_action_log 
     where t_action_log.delete_flg = 0  
     and t_action_log.user_id = m_action_trigger.user_id
     and t_action_log.action_id = m_action_trigger.action_id
     and t_action_log.action_trigger_id = m_action_trigger.action_trigger_id
     and t_action_log.action_status = 3 -- 3:COMPLETE
	 and t_action_log.action_time_from 
	 BETWEEN DATE_FORMAT(CURRENT_TIMESTAMP() - INTERVAL 1 DAY,'%Y/%m/%d 00:00:00') AND 
	 DATE_FORMAT(CURRENT_TIMESTAMP() - INTERVAL 1 DAY,'%Y/%m/%d 23:59:59')
     ) continue_action_cnt,
	 m_action_trigger.priority_order_no,
	 false continue_flg,
	 CASE WHEN (CURRENT_TIMESTAMP() - INTERVAL 3 DAY) < m_action.create_date THEN true ELSE false END new_action_flg
from 
	m_user_in_action inner join m_action_trigger
    on m_user_in_action.enable_flg = 1
    and m_user_in_action.delete_flg = 0
    and m_action_trigger.enable_flg = 1
    and m_action_trigger.delete_flg = 0    
    and m_user_in_action.user_id = m_action_trigger.user_id
    and m_user_in_action.action_id = m_action_trigger.action_id
	inner join m_action
    on m_action.enable_flg = 1
    and m_action.delete_flg = 0
    and m_action_trigger.action_id = m_action.action_id
where 
	m_user_in_action.user_id = {$userId}
and m_action_trigger.kigen_from <= CURRENT_TIMESTAMP()
and CURRENT_TIMESTAMP() <= m_action_trigger.kigen_to
union all 
select 
	m_action.action_id,
	m_action_trigger.action_trigger_id,
	m_action_trigger.kigen_from,
	m_action_trigger.kigen_to,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
    0 action_cnt,
	0 continue_action_cnt,
	999 priority_order_no,
	true continue_flg,
	CASE WHEN (CURRENT_TIMESTAMP() - INTERVAL 3 DAY) < m_action.create_date THEN true ELSE false END new_action_flg
from 
	m_user_in_action inner join m_action_trigger
    on m_user_in_action.enable_flg = 1
    and m_user_in_action.delete_flg = 0
    and m_action_trigger.enable_flg = 1
    and m_action_trigger.delete_flg = 0
    and m_user_in_action.user_id = m_action_trigger.user_id
    and m_user_in_action.action_id = m_action_trigger.action_id
	inner join m_action
    on m_action.enable_flg = 1
    and m_action.delete_flg = 0
    and m_action_trigger.action_id = m_action.action_id
where 
	m_user_in_action.user_id = {$userId}
and m_action_trigger.kigen_to <= CURRENT_TIMESTAMP() 
and CURRENT_TIMESTAMP() <= (m_action_trigger.kigen_to + INTERVAL 7 DAY)
and m_action_trigger.continue_kbn = {$continueKbn}
order by priority_order_no,action_trigger_id
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return array();
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$items = array(
				"action_id" => $row->action_id,
				"action_trigger_id" => $row->action_trigger_id,
				"action_title" => $row->action_title,
				"action_message" => $row->action_message,
				"action_description" => $row->action_description,
				"action_type" => $row->action_type,
				"action_cnt" => $row->action_cnt,
				"kigen_kikan" => getFromToDayString($row->kigen_from,$row->kigen_to),
				"continue_flg" => $row->continue_flg,
				"new_action_flg" => $row->new_action_flg,
				"continue_action_cnt" => $row->continue_action_cnt
			);

			array_push($resItems,$items);
		}
		return $resItems;
	}

	/**
	 * 最近使用した、使用回数順のアクションデータ一覧を10件取得する。
	 * ※今日のアクションは除く
	 */
	public function getUsedActionListData($userId){	
		$this->load->model(array('system_manager','actiontrigger_manager'));

        $sql = <<< SQL
select 
	m_action.action_id,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
    v_action_log.action_time_to,
    v_action_log.action_time_span,
    v_action_log.action_cnt,
	CASE WHEN (CURRENT_TIMESTAMP() - INTERVAL 3 DAY) < m_action.create_date THEN true ELSE false END new_action_flg,
    (select count(*) from t_action_log 
     where t_action_log.delete_flg = 0  
     and t_action_log.user_id = {$userId}
     and t_action_log.action_id = v_action_log.action_id
     and t_action_log.action_status = 3 -- 3:COMPLETE
	 and t_action_log.action_time_from BETWEEN DATE_FORMAT(now(),'%Y/%m/%d 00:00:00') AND DATE_FORMAT(now(),'%Y/%m/%d 23:59:59')
     ) action_now_cnt,
	 (select count(*) from t_action_log 
     where t_action_log.delete_flg = 0  
     and t_action_log.user_id = {$userId}
     and t_action_log.action_id = v_action_log.action_id
     and t_action_log.action_status = 3 -- 3:COMPLETE
	 and t_action_log.action_time_from 
	 BETWEEN DATE_FORMAT(CURRENT_TIMESTAMP() - INTERVAL 1 DAY,'%Y/%m/%d 00:00:00') AND 
	 DATE_FORMAT(CURRENT_TIMESTAMP() - INTERVAL 1 DAY,'%Y/%m/%d 23:59:59')
     ) continue_action_cnt
from 
	m_action inner join (
		select 
			t_action_log.action_id,
			max(t_action_log.action_time_to) action_time_to,
			sum(t_action_log.action_time_span) action_time_span,
			count(*) action_cnt
		from t_action_log left join m_action_trigger
		on m_action_trigger.kigen_from <= CURRENT_TIMESTAMP() and CURRENT_TIMESTAMP() <= m_action_trigger.kigen_to
		and t_action_log.user_id = m_action_trigger.user_id
		and t_action_log.action_id = m_action_trigger.action_id
		where 
			t_action_log.user_id = {$userId}
		and t_action_log.action_status = 3
		and m_action_trigger.user_id is null
		group by t_action_log.action_id
	) as v_action_log
    on m_action.action_id = v_action_log.action_id
order by v_action_log.action_time_to desc
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return array();
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			/*
			
	m_action.action_id,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
    v_action_log.action_time_to,
    v_action_log.action_time_span,
    v_action_log.action_cnt,
	CASE WHEN (CURRENT_TIMESTAMP() - INTERVAL 3 DAY) < m_action.create_date THEN true ELSE false END new_action_flg
	*/
			$items = array(
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_message" => $row->action_message,
				"action_description" => $row->action_description,
				"action_type" => $row->action_type,
				"action_time_to" => $row->action_time_to,
				"action_time_span" => $row->action_time_span,
				"action_cnt" => $row->action_cnt,
				"action_now_cnt" => $row->action_now_cnt,
				"continue_action_cnt" => $row->continue_action_cnt,
				"new_action_flg" => $row->new_action_flg,
			);

			array_push($resItems,$items);
		}
		return $resItems;
	}
}