<?php
class Actionlog_manager extends CI_Model{
	/**************************************************
	 * const アクショントリガーの状態
	 **************************************************/	
	const ACTION_STATUS_READY = 0;  // 0:READY
	const ACTION_STATUS_START = 1;	// 1:START
	const ACTION_STATUS_STOP = 2;	// 2:STOP
	const ACTION_STATUS_COMPLETE = 3;	// 3:COMPLETE
	const ACTION_STATUS_VERIFICATION = 9;	// 9:VERIFICATION

	/**
	 * アクショントリガーの状態
	 */
	public function getActionStatusName($actionStatus){
		switch($actionStatus ?? self::ACTION_STATUS_VERIFICATION){
		case self::ACTION_STATUS_READY:   // 0:READY
			return "READY";
		case self::ACTION_STATUS_START:	// 1:START
			return "START";
		case self::ACTION_STATUS_STOP:	// 2:STOP
			return "STOP";
		case self::ACTION_STATUS_COMPLETE:	// 3:COMPLETE
			return "COMPLETE";
		case self::ACTION_STATUS_VERIFICATION:	// 9:VERIFICATION
			return "VERIFICATION";
		}
		return "VERIFICATION";
	}

    /**
	 * アクションログを編集する。
	 */
	public function updActionLog($userId,$actionLogInfo){
		$this->load->model(array("common"));
		
		$actionStatus = self::ACTION_STATUS_COMPLETE;
		$actionTimeTo = $actionLogInfo["action_time_to"];

		$sql = <<< SQL
update t_action_log
set 
	action_time_to = '{$actionTimeTo}',
	action_time_span = TIMESTAMPDIFF(SQL_TSI_SECOND,action_time_from,'{$actionTimeTo}'),
	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP()
where 
	action_log_id =  {$actionLogInfo["action_log_id"]}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

    /**
	 * アクションログを削除する。
	 */
	public function delActionLog($userId,$actionLogId){

		$sql = <<< SQL
delete from t_action_log
where
	user_id = {$userId}
and action_log_id = {$actionLogId}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

    /**
	 * アクションを開始する。
	 */
	public function startActionLog($userId,$actionRunInfo){
		$this->load->model(array("common"));
		
		$actionStatus = self::ACTION_STATUS_START;

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
	CURRENT_TIMESTAMP(),null,0,
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
	public function completeActionLog($userId,$actionRunInfo){
		$this->load->model(array("common"));
		
		$actionStatus = self::ACTION_STATUS_COMPLETE;

		$sql = <<< SQL
update t_action_log
set 
	action_status = {$actionStatus},
	action_time_to = CURRENT_TIMESTAMP(),
	action_time_span = TIMESTAMPDIFF(SQL_TSI_SECOND,action_time_from,CURRENT_TIMESTAMP()),
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
		$this->load->model(array("common"));
		
		$complete = self::ACTION_STATUS_COMPLETE;

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

	/**
	 * アクションログ情報を取得する
	 */
	public function getActionLog($userId,$actionLogId){

		$sql = <<< SQL
select 
	t_action_log.action_log_id,
	m_action.action_id,
	m_action.action_title,
	m_action.action_type,
	t_action_log.action_time_from,
	t_action_log.action_time_to,
	t_action_log.action_time_span
from 
	t_action_log inner join m_action
    on t_action_log.delete_flg = false
    and t_action_log.action_id = m_action.action_id
	and t_action_log.user_id = {$userId}
	and t_action_log.delete_flg = false
where
	t_action_log.action_log_id = {$actionLogId}
SQL;

        $query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();

		$items = array(
			"action_log_id" => $row->action_log_id,
			"action_id" => $row->action_id,
			"action_title" => $row->action_title,
			"action_type" => $row->action_type,
			"action_time_from" => $row->action_time_from,
			"action_time_to" => $row->action_time_to,
			"action_time_span" => $row->action_time_span
		);

		return $items;
	}

	/**************************************************
	 * 日別アクションログリスト
	 **************************************************/	
	/**
	 * 日別アクションログリストのパラメータを取得
	 * 
	 * @yearMonth : 例 - 2020/02
	 */
	public function getPartsDayActionLogListParams($nextReDispId,$yearMonth,$actionId,$actionTriggerId){
		return array(
			"nextReDispId" => $nextReDispId,
			"actionId" => $actionId,
			"actionTriggerId" => $actionTriggerId,
			"yearMonth" => $yearMonth,
		);
	}

	/**
	 * 日別アクションログリストを取得する。
	 */
	public function getDayActionLogList($userId,$ymd,$actionId){
		$this->load->model(array('system_manager','Actionlog_manager'));

		$actionStatus = Actionlog_manager::ACTION_STATUS_COMPLETE;
		$sqlAction = " select action_id,action_title,action_type from m_action  ";
		if($actionId != "0"){
			$sqlAction .= " where m_action.action_id = {$actionId} ";
		}

		$ymd = str_replace( "-","/",$ymd);

		$actionLogFilterDate = "and DATE_FORMAT(t_action_log.action_time_from , '%Y/%m/%d') = '{$ymd}'";
				
		$sql = <<< SQL
select 
	t_action_log.action_log_id,
	v_action.action_id,
	v_action.action_title,
	v_action.action_type,
	t_action_log.action_time_from,
	t_action_log.action_time_to,
	t_action_log.action_time_span
from 
	t_action_log inner join ({$sqlAction}) v_action
    on t_action_log.delete_flg = 0
	and t_action_log.action_status = {$actionStatus}
    and t_action_log.action_id = v_action.action_id
	and t_action_log.user_id = {$userId}
	{$actionLogFilterDate}
	and t_action_log.delete_flg = false
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
				"action_time_span" => dispTimeStrFromSec($fromDate,$toDate)
			);

			array_push($resItems,$items);
		}
		return $resItems;
	}
}