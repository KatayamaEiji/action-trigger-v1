<?php
class Actiontrigger_manager extends CI_Model{
	/**************************************************
	 * const 期間単位
	 **************************************************/	
	const CONTINUE_KBN_MI = 0; // ０：未継続
	const CONTINUE_KBN_KEIZOKU = 1; // １：継続済み
	const CONTINUE_KBN_KEIZOKU_END = 2; // ２：継続終了

	/**************************************************
	 * public method - アクショントリガーマスタ関連
	 **************************************************/
	/**
	 * アクショントリガー情報取得
	 */
	public function getActionTriggerTitle($actionTriggerId){
		if($actionTriggerId == ""){
			return "";
		}

        // ログイン情報の読み込み
        $sql = <<< SQL
select 
	m_action_trigger.action_trigger_id,
	m_action_trigger.kigen_from,
	m_action_trigger.kigen_to,
	m_action.action_title
from 
	m_action_trigger inner join m_action
	on m_action_trigger.action_id = m_action.action_id
where 
	m_action_trigger.action_trigger_id = {$actionTriggerId}
SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();

		return $row->action_title . " - " . getFromToDayString($row->kigen_from,$row->kigen_to);
	}


	/**
	 * アクショントリガー情報取得
	 */
	public function getActionTriggerInfo($actionTriggerId){

        // ログイン情報の読み込み
        $sql = <<< SQL
select 
	m_action_trigger.action_trigger_id,
	m_action_trigger.kigen_from,
	m_action_trigger.kigen_to,
	m_action.action_title
from 
	m_action_trigger inner join m_action
	on m_action_trigger.action_id = m_action.action_id
where 
	m_action_trigger.action_trigger_id = {$actionTriggerId}
SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();

		$dtFrom = new DateTime($row->kigen_from);
		$dtTo = new DateTime($row->kigen_to);

		$items = array(
			"action_trigger_id" => $row->action_trigger_id,
			"action_title" => $row->action_title,
			"kigen_from" => $dtFrom,
			"kigen_to" => $dtTo
		);

		return $items;
	}

	/**
	 * 継続状態取得
	 */
	public function getContinueKbn($actionTriggerId){

        // ログイン情報の読み込み
        $sql = <<< SQL
select 
	m_action_trigger.continue_kbn
from 
	m_action_trigger
where 
	m_action_trigger.action_trigger_id = {$actionTriggerId}
SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();
		return $row->continue_kbn;
	}

	/**
	 * アクショントリガーマスタ（物理追加）
	 */
	public function addActionTrigger($userId,$actionId){
		$date = new DateTime('now');
		$fromDate = $date->format('Y/m/d') . " 00:00:00";
		$date->modify('+1 months');
		$date->modify('-1 days');
		$toDate = $date->format('Y/m/d') . " 23:59:59";
		
		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
-- m_action_trigger
insert into m_action_trigger(
	user_id,
	action_id,
	priority_order_no,
	continue_kbn,

	kigen_from,
	kigen_to,
	target_count_flg,
	target_count,
	target_sum_time_flg,
	target_sum_time,
	target_sum_time_h,
	target_sum_time_m,
	target_input_num_flg,
	target_input_num,
	day_count_flg,
	day_count,
	day_time_span_flg,
	day_time_span,
	action_time_flg,
	action_time_from,
	action_time_to,
	action_week_all,
	action_week_sunday,
	action_week_monday,
	action_week_tuesday,
	action_week_wednesday,
	action_week_thursday,
	action_week_friday,
	action_week_saturday,
	action_must_flg,

	enable_flg,
	delete_flg,
	create_user_id,
	create_date,
	update_user_id,
	update_date
) 
values (
	{$userId},
	{$actionId},

	0,
	0,
	'{$fromDate}',
	'{$toDate}',
	false,
	0,
	false,
	0,
	0,
	0,
	false,
	0,
	false,
	0,
	false,
	0,
	false,
	0,
	0,
	false,
	false,
	false,
	false,
	false,
	false,
	false,
	false,
	false,

	true,
	false,
	{$userId},
	CURRENT_TIMESTAMP,
	{$userId},
	CURRENT_TIMESTAMP
	);
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 元のアクショントリガーから新しいアクショントリガーを作る。
	 */
	public function continuationActionTrigger($userId,$actionTriggerId){
		$sql = <<< SQL
-- m_action_trigger
--    action_trigger_idをpriority_order_noとして登録して最初の登録順を保存
insert into m_action_trigger(
	user_id,
	action_id,
	priority_order_no,
	continue_kbn,

	kigen_from,
	kigen_to,
	target_count_flg,
	target_count,
	target_sum_time_flg,
	target_sum_time,
	target_sum_time_h,
	target_sum_time_m,
	target_input_num_flg,
	target_input_num,
	day_count_flg,
	day_count,
	day_time_span_flg,
	day_time_span,
	action_time_flg,
	action_time_from,
	action_time_to,
	action_week_all,
	action_week_sunday,
	action_week_monday,
	action_week_tuesday,
	action_week_wednesday,
	action_week_thursday,
	action_week_friday,
	action_week_saturday,
	action_must_flg,

	enable_flg,
	delete_flg,
	create_user_id,
	create_date,
	update_user_id,
	update_date
) 
select 
	user_id,
	action_id,
	action_trigger_id,
	0,

	DATE_FORMAT(DATE_ADD(kigen_to, INTERVAL 1 DAY),'%Y/%m/%d 00:00:00'),
    DATE_FORMAT(DATE_ADD(kigen_to, INTERVAL DATEDIFF(kigen_to,kigen_from) + 1 DAY),'%Y/%m/%d 23:59:59'),

	target_count_flg,
	target_count,
	target_sum_time_flg,
	target_sum_time,
	target_sum_time_h,
	target_sum_time_m,
	target_input_num_flg,
	target_input_num,
	day_count_flg,
	day_count,
	day_time_span_flg,
	day_time_span,
	action_time_flg,
	action_time_from,
	action_time_to,
	action_week_all,
	action_week_sunday,
	action_week_monday,
	action_week_tuesday,
	action_week_wednesday,
	action_week_thursday,
	action_week_friday,
	action_week_saturday,
	action_must_flg,

	true,
	false,
	{$userId},
	CURRENT_TIMESTAMP,
	{$userId},
	CURRENT_TIMESTAMP
from m_action_trigger
where 
	action_trigger_id = {$actionTriggerId}
and user_id = {$userId}

SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * アクショントリガーを更新する
	 */
	public function updActionTrigger($userId,$actionTriggerInfo){
		$actionTriggerId = $actionTriggerInfo["action_trigger_id"];
		$kigenTo = $actionTriggerInfo["kigen_to"];

		$sql = <<< SQL
-- m_action_trigger
update m_action_trigger
set
	kigen_to = '{$kigenTo} 23:59:59',

	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP
where 
	action_trigger_id = {$actionTriggerId}
and user_id = {$userId}

SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 元のアクショントリガーを継続情報を更新する
	 */
	public function updActionTriggerContinueKbn($userId,$actionTriggerId,$continueKbn){
		$sql = <<< SQL
-- m_action_trigger
update m_action_trigger
set
	continue_kbn = {$continueKbn},

	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP
where 
	action_trigger_id = {$actionTriggerId}
and user_id = {$userId}

SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * アクショントリガー削除（論理削除）
	 */
	public function delActionTrigger($userId,$actionTriggerId){
		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
		update m_action_trigger
		set 
			delete_flg = true,
			update_user_id = {$userId},
			update_date = CURRENT_TIMESTAMP
		where
			user_id = {$userId}
		and action_trigger_id = {$actionTriggerId}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * アクション全体のアクショントリガー削除（論理削除）
	 */
	public function delActionTriggerFromActionId($userId,$actionId){
		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
		update m_action_trigger
		set 
			delete_flg = true,
			update_user_id = {$userId},
			update_date = CURRENT_TIMESTAMP
		where
			user_id = {$userId}
		and action_id = {$actionId}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}
}