<?php
class Action_manager extends CI_Model{
	/**************************************************
	 * const 期間単位
	 **************************************************/	
	const PERIOD_UNIT_DAY = 1; // 毎日
	const PERIOD_UNIT_MONTH = 2; // 毎月
	const PERIOD_UNIT_YEAR = 3; // 毎年

	/**************************************************
	 * const 状態
	 **************************************************/	
	const STATE_STOP = 0;   // 0:停止
	const STATE_RUN = 1;	// 1:実行中

	/**************************************************
	 * const 予算表示単位
	 **************************************************/	
	const BADGET_DISP_UNIT_ALL = 0; // 全て
	const BADGET_DISP_UNIT_DAY = 1;	// 日ごと
	
	/**************************************************
	 * const アクションタイプ
	 **************************************************/
	public const ACTION_TYPE_COUNT_UP = 1;   // 1:COUNT_UP
	public const ACTION_TYPE_COUNT_DOWN = 2;	// 2:COUNT_DOWN
	public const ACTION_TYPE_FIRST = 3;	// 3:FIRST
	public const ACTION_TYPE_YOUTUBE = 4;	// 4:YOUTUBE
	public const ACTION_TYPE_DECLARE = 5;	// 5:DECLARE
	public const ACTION_TYPE_REWARD = 6;	// 6:REWARD

	/**************************************************
	 * const 権限タイプ
	 **************************************************/	
	const AUTH_TYPE_PRIVATE = 0;   // 0:非公開
	const AUTH_TYPE_PUBLIC = 1;	// 1:公開

	/**************************************************
	 * member
	 **************************************************/

	/**************************************************
	 * constructor / destructor
	 **************************************************/

	/**************************************************
	 * public method
	 **************************************************/

	/**************************************************
	 * public method - アクション、アクションログ画面関連
	 **************************************************/
	/**
	 * 初期データが登録されているか確認する
	 */
	public function chkInitData($userId){	
		$this->db->where("user_id", $userId);	

		$query = $this->db->get("m_user_in_action");

		if($query->num_rows() >= 1){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * アクションタイトルを取得
	 */
	public function getActionTitle($actionId){
		if($actionId == 0){
			return false;
		}

        $sql = <<< SQL
select 
	m_action.action_title
from 
	m_action
    
where 
	m_action.action_id = {$actionId}

SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();
		return $row->action_title;
	}

	/**
	 * アクションタイトルが自分で登録したものの中にあるか存在をチェック
	 */
	public function isUserActionTitle($userId,$actionId,$actionTitle){
		$actionTitle = $this->convActionTitle($actionTitle);
		$findActionTtile = $this->convFindString($actionTitle);

		// 自分が登録したアクションと名前が被っていないか確認。
		// （編集している自分のアクションは当然除く）
		$actionIdSql = "";
		if($actionId){
			$actionIdSql = "and m_action.action_id != {$actionId}";
		}
        $sql = <<< SQL
	select 
		count(*) cnt
	from 
		m_action inner join m_user_in_action
		on m_action.action_id = m_user_in_action.action_id
	where 
		(m_action.action_title = '{$actionTitle}' or m_action.action_find_title = '{$findActionTtile}')
	and m_user_in_action.user_id = {$userId}
	{$actionIdSql}
	and m_action.delete_flg = 0
SQL;
		$query = $this->db->query($sql);

		$row = $query->row();
		return $row->cnt != 0;
	}
	/**
	 * 公開されているアクションと同じ名前が存在するかチェック
	 */
	public function isReleaseActionTitle($userId,$actionTitle){
		$actionTitle = $this->convActionTitle($actionTitle);
		$findActionTtile = $this->convFindString($actionTitle);

        $sql = <<< SQL
	select 
		count(*) cnt
	from 
		m_action inner join m_user_in_action
		on m_action.action_id != m_user_in_action.action_id
		and m_user_in_action.user_id = {$userId}
	where 
		(m_action.action_title = '{$actionTitle}' or m_action.action_find_title = '{$findActionTtile}')
	and m_action.delete_flg = 0
	and m_action.auth_type = 1
SQL;
		$query = $this->db->query($sql);

		$row = $query->row();
		return $row->cnt != 0;
	}

	/**
	 * 
	 */
	public function getActionTypeName($actionType){
	
		switch($actionType){
		case self::ACTION_TYPE_COUNT_UP:   //
			return "カウントアップ";
		case self::ACTION_TYPE_COUNT_DOWN:	//
			return "カウントダウン";
		case self::ACTION_TYPE_FIRST:	//
			return "";
		}
	}

	/**
	 * 
	 */
	public function getAuthTypeName($authType){
		switch($authType){
		case self::AUTH_TYPE_PRIVATE:   // 0:非公開
			return "非公開";
		case self::AUTH_TYPE_PUBLIC:	// 1:公開
			return "公開";
		}
	}


	/**************************************************
	 * public method - アクションマスタ関連
	 **************************************************/
	/**
	 * アクション追加（物理追加）
	 */
	public function addAction($userId,$actionInfo){
		$this->load->helper('Util_helper');

		$action_title = "'" . $this->convActionTitle($actionInfo["action_title"]) . "'";
		$action_message = "'" . $actionInfo["action_message"]. "'";
		$action_description = "'" . $actionInfo["action_description"]. "'";
		$action_type = $actionInfo["action_type"];
		$action_plus_type = 1;
		$parent_action_id = 0;
		$action_counter_flg = 'false';
		$input_num_type = 0;
		$input_num_title = "''";
		$input_num_unit = "''";
		$action_find_title =  "'" . $this->convFindString($actionInfo["action_title"]) . "'";
		$action_find_message =  "'" . $this->convFindString($actionInfo["action_message"]) . "'";
		$auth_type = $actionInfo["auth_type"]; // 非公開
		$graph_total_type = 0;
		$background_image_path = "''";
		$background_music_path = "''";
		$basic_complete_time = getTimeNum($actionInfo["basic_complete_time"]);
		$count_down_timer_limmit = 0;
		$youtube_id = "''";

		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
-- m_action
insert into m_action(
	action_title,
	action_message,
	action_description,
	action_type,
	action_plus_type,
	parent_action_id,
	action_counter_flg,
	input_num_type,
	input_num_title,
	input_num_unit,
	action_find_title,
	action_find_message,
	auth_type,
	graph_total_type,
	background_image_path,
	background_music_path,
	basic_complete_time,
	count_down_timer_limmit,
	youtube_id,
	enable_flg,
	delete_flg,
	create_user_id,
	create_date,
	update_user_id,
	update_date
) 
values (
	{$action_title},
	{$action_message},
	{$action_description},
	{$action_type},
	{$action_plus_type},
	{$parent_action_id},
	{$action_counter_flg},
	{$input_num_type},
	{$input_num_title},
	{$input_num_unit},
	{$action_find_title},
	{$action_find_message},
	{$auth_type},
	{$graph_total_type},
	{$background_image_path},
	{$background_music_path},
	{$basic_complete_time},
	{$count_down_timer_limmit},
	{$youtube_id},
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
	 * アクション更新（物理追加）
	 */
	public function updAction($userId,$actionInfo){
		$this->load->helper('Util_helper');

		$actionId = $actionInfo["action_id"];
		$action_title = "'" . $this->convActionTitle($actionInfo["action_title"]) . "'";
		$action_message = "'" . $actionInfo["action_message"]. "'";
		$action_description = "'" . $actionInfo["action_description"]. "'";
		$action_type = $actionInfo["action_type"];
		$action_plus_type = 1;
		$parent_action_id = 0;
		$action_counter_flg = 'false';
		$input_num_type = 0;
		$input_num_title = "''";
		$input_num_unit = "''";
		$action_find_title = "'" . $this->convFindString($actionInfo["action_title"]) . "'";
		$action_find_message = "'" . $this->convFindString($actionInfo["action_message"]) . "'";
		$auth_type = $actionInfo["auth_type"]; // 非公開
		$graph_total_type = 0;
		$background_image_path = "''";
		$background_music_path = "''";
		$basic_complete_time = getTimeNum($actionInfo["basic_complete_time"]);
		$count_down_timer_limmit = 0;
		$youtube_id = "''";

		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
-- m_action
update m_action
set
	action_title = {$action_title},
	action_message = {$action_message},
	action_description = {$action_description},
	action_type = {$action_type},
	action_plus_type = {$action_plus_type},
	parent_action_id = {$parent_action_id},
	action_counter_flg = {$action_counter_flg},
	input_num_type = {$input_num_type},
	input_num_title = {$input_num_title},
	input_num_unit = {$input_num_unit},
	action_find_title = {$action_find_title},
	action_find_message = {$action_find_message},
	auth_type = {$auth_type},
	graph_total_type = {$graph_total_type},
	background_image_path = {$background_image_path},
	background_music_path = {$background_music_path},
	basic_complete_time = {$basic_complete_time},
	count_down_timer_limmit = {$count_down_timer_limmit},
	youtube_id = {$youtube_id},
	enable_flg = true,
	delete_flg = false,
	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP
where 
	action_id = {$actionId}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * アクション削除（論理削除）
	 */
	public function delAction($userId,$actionId){
		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
		update m_action
		set
			delete_flg = true,
			update_user_id = {$userId},
			update_date = CURRENT_TIMESTAMP
		where
			create_user_id = {$userId}
		and action_id = {$actionId}
SQL;
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**
	 * ユーザーアクション削除（論理削除）
	 */
	public function delUserInAction($userId,$actionId){
		// 作成したユーザーしか削除ができない
		$sql = <<< SQL
		update m_user_in_action
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


	/**************************************************
	 * public method - ユーザー関連アクションマスタ関連
	 **************************************************/
	/**
	 * ユーザー関連アクションマスタ存在チェック（物理）
	 * ※delete_flgを考慮しない。
	 */
	public function isUserIdAction($userId,$actionId){
        $sql = <<< SQL
select 
	m_user_in_action.action_id
from 
	m_user_in_action
where 
	m_user_in_action.user_id = {$userId}
and m_user_in_action.action_id = {$actionId}

SQL;
		$query = $this->db->query($sql);

		if ($query->num_rows() == 0){
			return false;
		}

		return true;
	}

	/**
	 * ユーザー関連アクションマスタ（物理追加）
	 */
	public function addUserIdAction($userId,$actionId){
		if($this->isUserIdAction($userId,$actionId)){
			// 既に存在した場合、更新
$sql = <<< SQL
update m_user_in_action
set 
	delete_flg = false,
	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP
where
	user_id = {$userId}
and action_id = {$actionId}
SQL;
		}
		else{
			$sql = <<< SQL
-- m_user_in_action
insert into m_user_in_action(
	action_id	,
	user_id,

	enable_flg,
	delete_flg,
	create_user_id,
	create_date,
	update_user_id,
	update_date
) 
values (
	{$actionId},
	{$userId},

	true,
	false,
	{$userId},
	CURRENT_TIMESTAMP,
	{$userId},
	CURRENT_TIMESTAMP
	);
SQL;
		}
		$ret = $this->db->query($sql);

		if($ret){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}

	/**************************************************
	 * public method - アクションログ関連
	 **************************************************/
	/**
	 * アクションログ削除
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
	 * 通常アクションタイトルを補正する。
	 */
	public function convActionTitle($val){
		// 全角スペースを半角スペースにする。
		$search = array('　');
		$replace = array(' ');
		$val = str_replace($search, $replace, $val);

		return trim($val);
	}

	/**
	 * 検索用アクションタイトルを取得する。
	 */
	public function convFindString($val){
		// 全角を半角にする。
		$val = trim(mb_convert_kana($val, 'kvrn'));

		$search = array('ー','～',"【","】","『","』","(",")","､",":",";","。");
		$replace = array('-','-',"[","]","[","]","[","]",",",",",",",".");
		$val = str_replace($search, $replace, $val);

		$search = array('零','一', '二', '三', '四', '五', '六', '七', '八', '九','十','百','千');
		$replace = array('0','1', '2', '3', '4', '5', '6', '7', '8', '9','10','100','1000');
		$val = str_replace($search, $replace,$val);

		$search = array('①', '②', '③', '④', '⑤', '⑥', '⑦', '⑧', '⑨','⑩','⑪','⑫','⑬','⑭','⑮','⑯','⑰','⑱','⑲','⑳');
		$replace = array('1', '2', '3', '4', '5', '6', '7', '8', '9','10','11','12','13','14','15','16','17','18','19','20');
		$val = str_replace($search, $replace, $val);

		return $val;
	}


}