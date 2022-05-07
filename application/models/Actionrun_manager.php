<?php
class Actionrun_manager extends CI_Model{
	/**
	 * アクション実行情報を取得
	 */
	public function isActionRun($userId){
        $this->load->model(array("actionlog_manager"));
        
		$actionStatusComplete = ActionLog_manager::ACTION_STATUS_COMPLETE;

        // ログイン情報の読み込み
        $sql = <<< SQL
select count(*) cnt
from t_action_log
where t_action_log.action_status <> {$actionStatusComplete}
and t_action_log.user_id = {$userId}
SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();
		if($row->cnt == 0){
			return false;
		}
		return true;
    }
    
	/**
	 * アクション実行情報を取得
	 */
	public function getActionRunInfo($userId,$actionId,$actionTriggerId){
        $this->load->model(array("actionlog_manager"));
		$this->load->helper('Util_helper');

		$actionStatusComplete = ActionLog_manager::ACTION_STATUS_COMPLETE;

        // ログイン情報の読み込み
        $sql = <<< SQL
select 
	m_action.action_id,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
	m_action.auth_type,
	m_action.action_image_flg,
	m_action.basic_complete_time,
    (select count(*) from t_action_log 
     where t_action_log.delete_flg = 0  
     and t_action_log.user_id = m_user_in_action.user_id
     and t_action_log.action_id = m_user_in_action.action_id
     and t_action_log.action_status = {$actionStatusComplete} -- 3:COMPLETE
     and t_action_log.action_time_from BETWEEN DATE_FORMAT(now(),'%Y/%m/%d 00:00:00') AND DATE_FORMAT(now(),'%Y/%m/%d 23:59:59')
     ) action_cnt,
     v_action_log.action_status action_status,
	 v_action_log.action_log_id,
	 {$actionTriggerId} action_trigger_id,
     v_action_log.action_time_from,
     v_action_log.action_time_to
from 
	m_action left join m_user_in_action
    on m_action.enable_flg = 1
    and m_action.delete_flg = 0
	and m_user_in_action.user_id = {$userId}
    and m_user_in_action.enable_flg = 1
    and m_user_in_action.delete_flg = 0
    and m_action.action_id = m_user_in_action.action_id
    left join (
		select action_id,action_status,action_log_id,action_time_from,action_time_to from t_action_log
		where t_action_log.action_status <> {$actionStatusComplete}
		and t_action_log.user_id = {$userId}
	) as v_action_log
    on m_action.action_id = v_action_log.action_id
where 
	m_action.action_id = {$actionId}

SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();
		return $this->setActionRunInfo($row);
	}

	/**
	 * アクション実行情報を取得
	 */
	public function getActionRunInfoFromUserId($userId){
        $this->load->model(array("actionlog_manager"));
        
		$actionStatusComplete = ActionLog_manager::ACTION_STATUS_COMPLETE;

        // ログイン情報の読み込み
        $sql = <<< SQL
select 
	m_action.action_id,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
	m_action.auth_type,
	m_action.action_image_flg,
	m_action.basic_complete_time,
    0 action_cnt,
     t_action_log.action_status,
	 t_action_log.action_log_id,
	 t_action_log.action_trigger_id,
     t_action_log.action_time_from,
     t_action_log.action_time_to
from 
	t_action_log inner join m_action
    on t_action_log.action_id = m_action.action_id
where 
	t_action_log.action_status <> {$actionStatusComplete}
and t_action_log.user_id = {$userId}
SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();
		return $this->setActionRunInfo($row);
	}

	/**
	 * アクション実行情報を取得
	 */
	public function getActionRunInfoFromLogId($actionLogId){
        $this->load->model(array("actionlog_manager"));

		$actionStatusComplete = ActionLog_manager::ACTION_STATUS_COMPLETE;

        // ログイン情報の読み込み
        $sql = <<< SQL
select 
	m_action.action_id,
	m_action.action_title,
    m_action.action_message,
	m_action.action_description,
	m_action.action_type,
	m_action.auth_type,
	m_action.action_image_flg,
	m_action.basic_complete_time,
    (select count(*) + 1 from t_action_log 
     where t_action_log.delete_flg = 0  
     and t_action_log.user_id = t_action_log.user_id
     and t_action_log.action_id = t_action_log.action_id
     and t_action_log.action_status = {$actionStatusComplete} -- 3:COMPLETE
     and t_action_log.action_time_from BETWEEN DATE_FORMAT(now(),'%Y/%m/%d 00:00:00') AND DATE_FORMAT(now(),'%Y/%m/%d 23:59:59')
     ) action_cnt,
     t_action_log.action_status,
	 t_action_log.action_log_id,
	 t_action_log.action_trigger_id,
     t_action_log.action_time_from,
     t_action_log.action_time_to
from 
	t_action_log inner join m_action
    on t_action_log.action_id = m_action.action_id
where 
	t_action_log.action_log_id = {$actionLogId}
SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();
		return $this->setActionRunInfo($row);
    }
    
	/**
	 * 
	 */
	private function setActionRunInfo($row){
        $this->load->model(array("action_manager","actionlog_manager"));
		$this->load->helper('Util_helper');

		$items = array(
			"action_id" => $row->action_id,
			"action_trigger_id" => $row->action_trigger_id,
			"action_title" => $row->action_title,
			"action_description" => $row->action_description,
			"action_message" => $row->action_message,
			"action_type" => $row->action_type,
			"action_type_name" => $this->action_manager->getActionTypeName($row->action_type),
			"action_image_flg" => convDBFlag($row->action_image_flg),
			"basic_complete_time" => $row->basic_complete_time,
			"basic_complete_time_str" => convTimeStrFromSec($row->basic_complete_time),
			"action_image_path" => $row->action_image_flg ? "/images/action/" . $row->action_id . "_title.png" : null,
			"auth_type" => $row->auth_type,
			"auth_type_name" => $this->action_manager->getAuthTypeName($row->auth_type),
			"action_cnt" => $row->action_cnt,
			"action_log_id" => $row->action_log_id,
			"action_time_from" => $row->action_time_from,
			"action_time_to" => $row->action_time_to,
			"action_status" => $row->action_status ?? Actionlog_manager::ACTION_STATUS_VERIFICATION,
			"action_status_name" => $this->actionlog_manager->getActionStatusName($row->action_status),
		);

		return $items;
    }
    
    /**
	 * アクションを開始する。
	 */
	public function startActionLog($userId,$actionRunInfo){
        $this->load->model(array("actionlog_manager"));
		
		$actionStatus = ActionLog_manager::ACTION_STATUS_START;

		// 開始時間は2秒後とする。
		$sql = <<< SQL
insert into t_action_log(
	user_id,
	action_id,
	action_trigger_id,
	action_status,
	action_time_from,action_time_to,action_time_span,
	action_counter_count,input_num,
	delete_flg,
	create_user_id,create_date,update_user_id,update_date) 
values (
	{$userId},
	{$actionRunInfo["action_id"]},
	{$actionRunInfo["action_trigger_id"]},
	{$actionStatus},
	CURRENT_TIMESTAMP() + INTERVAL 2 SECOND,
	null,0,
	0,0,False,
	{$userId},CURRENT_TIMESTAMP(),{$userId},CURRENT_TIMESTAMP());
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

    /**
	 * アクションを達成にする。
	 */
	public function completeActionLog($userId,$actionRunInfo,$actionTimeTo){
		$this->load->model(array("actionlog_manager"));
		
		$actionStatus = ActionLog_manager::ACTION_STATUS_COMPLETE;

		if($actionTimeTo === ""){
			$actionTimeTo = "CURRENT_TIMESTAMP()";
		}
		else{
			$actionTimeTo = "'" . $actionTimeTo . "'";
		}
			
		$sql = <<< SQL
update t_action_log
set 
	action_status = {$actionStatus},
	action_time_to = {$actionTimeTo},
	action_time_span = TIMESTAMPDIFF(SQL_TSI_SECOND,action_time_from,{$actionTimeTo}),
	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP()
where 
	action_log_id =  {$actionRunInfo["action_log_id"]}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
    }
    
    /**
	 * アクションをキャンセルする。
	 */
	public function cancelAction($userId){
		$this->load->model(array("actionlog_manager"));
		
		$complete = ActionLog_manager::ACTION_STATUS_COMPLETE;

		$sql = <<< SQL
delete from t_action_log
where
	user_id = {$userId}
and action_status != {$complete}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

}