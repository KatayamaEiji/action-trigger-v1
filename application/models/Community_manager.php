<?php
class Community_manager extends CI_Model{
	/**
	 * コミュニティ情報を取得
	 */
    public function getInfoList(){
        $sql = <<< SQL
select 
	DATE_FORMAT(t_action_log.action_time_from, '%Y%m%d') group_day,
    min(t_action_log.action_time_from) action_time_from,
    m_action.action_id,
    max(m_action.action_title) action_title,
    count(*) action_cnt
from 
	t_action_log inner join m_action
	on t_action_log.action_id = m_action.action_id
where t_action_log.delete_flg = false
GROUP BY DATE_FORMAT(t_action_log.action_time_from, '%Y%m%d'),m_action.action_id
order by DATE_FORMAT(t_action_log.action_time_from, '%Y%m%d') desc,m_action.action_id
LIMIT 10
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$items = array(
				"action_day" => getDateMDStr($row->action_time_from),
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_cnt" => $row->action_cnt
			);

			array_push($resItems,$items);
		}

		return $resItems;
	}
	
	/**
	 * コミュニティ情報を取得
	 */
    public function getUserInfoList($userId){
        $sql = <<< SQL
select 
	DATE_FORMAT(t_action_log.action_time_from, '%Y%m%d') group_day,
    min(t_action_log.action_time_from) action_time_from,
    m_action.action_id,
    max(m_action.action_title) action_title,
    count(*) action_cnt
from 
	t_action_log inner join m_action
	on t_action_log.action_id = m_action.action_id
where t_action_log.delete_flg = false
and t_action_log.user_id = {$userId}
GROUP BY DATE_FORMAT(t_action_log.action_time_from, '%Y%m%d'),m_action.action_id
order by DATE_FORMAT(t_action_log.action_time_from, '%Y%m%d') desc,m_action.action_id
LIMIT 10
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$items = array(
				"action_day" => getDateMDStr($row->action_time_from),
				"action_id" => $row->action_id,
				"action_title" => $row->action_title,
				"action_cnt" => $row->action_cnt
			);

			array_push($resItems,$items);
		}

		return $resItems;
	}

	/**
	 * コミュニティ情報を取得
	 */
    public function getCommunityNowList($userId){
		$this->load->helper('Util_helper');

        $sql = <<< SQL
select 
	DATE_FORMAT(create_date, '%Y%m%d') create_day,
	t_community.create_date,
    t_community.message_type,
	t_community.message,
	t_community.icon_no,
	CASE WHEN t_community.user_id = {$userId} THEN true ELSE false END mymessage_flg
from 
	t_community inner join m_user
	on t_community.user_id = m_user.user_id
where delete_flg = false
order by create_date desc
LIMIT 1000
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$resItems = array();
		foreach ($query->result() as $row)
		{
			$items = array(
				"create_day" => $row->create_day,
				"create_date" => $row->create_date,
				"message_type" => $row->message_type,
				"message" => $row->message,
				"icon_no" => $row->icon_no,
				"mymessage_flg" => $row->mymessage_flg
			);

			array_push($resItems,$items);
		}

		return $resItems;
    }
}