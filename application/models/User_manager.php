<?php
class User_manager extends CI_Model
{
	/**
	 * ログインができるか確認する
	 */
	public function chkLogIn($loginId, $password)
	{	//can_log_inファンクションを作っていく
		$sql = <<< SQL
select
    m_user.login_id
from 
    m_user
where 
    m_user.login_id = '{$loginId}'
and m_user.password = '{$password}'
and m_user.enable_flg = true
and m_user.delete_flg = false
and (m_user.enable_to_date is null or m_user.enable_to_date > current_timestamp)
SQL;
		$query = $this->db->query($sql);

		$this->db->where("login_id", $this->input->post("login_id"));	//POSTされたlogin_idデータとDB情報を照合する
		$this->db->where("password", md5($this->input->post("password")));	//POSTされたパスワードデータとDB情報を照合する

		$query = $this->db->get("m_user");

		if ($query->num_rows() == 1) {	//ユーザーが存在した場合の処理
			return true;
		} else {					//ユーザーが存在しなかった場合の処理
			return false;
		}
	}

	/**
	 * LoginIDからUser情報を取得する。
	 */
	public function getUserFromLoginid($loginID)
	{
		$this->db->where("login_id", $loginID);	//POSTされたemailデータとDB情報を照合する
		$user = $this->db->get("m_user");		//usersテーブルからすべての値を取得

		if ($user) {
			$row = $user->row();

			$items = array(
				"user_id" => $row->user_id,
				"login_id" => $row->login_id,
				"user_name" => $row->user_name
			);
			return $items;
		}
		return false;
	}

	/**
	 * UserIDからUser情報を取得する。
	 */
	public function getUser($userID)
	{
		$this->db->where("user_id", $userID);	//POSTされたemailデータとDB情報を照合する
		$user = $this->db->get("m_user");		//usersテーブルからすべての値を取得

		if ($user) {
			$row = $user->row();

			$items = array(
				"user_id" => $row->user_id,
				"login_id" => $row->login_id,
				"user_name" => $row->user_name,
				"mail_address" => $row->mail_address,
				"values01" => $row->values01,
				"values02" => $row->values02,
				"values03" => $row->values03,
				"mission" => $row->mission
			);
			return $items;
		}
		return false;
	}

	/**
	 * 仮ユーザーを登録する。
	 */
	public function addTempUsers($key)
	{

		//add_temp_usersモデルの実行時に、以下のデータを取得して、$dataと紐づける
		$data = array(
			"mail_address" => $this->input->post("t_mail_address"),
			"password" => md5($this->input->post("t_password")),
			"key" => $key
		);

		//$dataをDB内のm_user_tempに挿入↓後に、$queryと紐づける
		$query = $this->db->insert("m_user_temp", $data);

		if ($query) {		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 仮ユーザーが登録されているか確認する。
	 */
	public function isValidKey($key)
	{
		$this->db->where("key", $key);	// $keyと等しいレコードを指定
		$query = $this->db->get("m_user_temp");		//temp_userテーブルから情報を取得

		if ($query->num_rows() == 1) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ユーザーを登録する。
	 */
	public function addUser($key)
	{
		$this->db->where("key", $key);		//keyのテーブルを選択
		$temp_user = $this->db->get("m_user_temp");		//temp_usersテーブルからすべての値を取得

		if ($temp_user) {
			$row = $temp_user->row();
			//$rowでは、temp_usersの行を返します。
			//しかし、このままでは1行目のみが返されるので、さらに以下を実行。

			$data = array(	//$rowで取得した値のうち、必要な情報のみを取得する
				"login_id" => $row->mail_address,
				"mail_address" => $row->mail_address,
				"password" => $row->password
			);

			$did_add_user = $this->db->insert("m_user", $data);
		}

		if ($did_add_user) {		//did_add_userが成功したら以下を実行
			$this->db->where("key", $key);
			$this->db->delete("m_user_temp");
			return true; 
		}

		return false;
	}


	/**
	 * ユーザーを更新する。
	 */
	public function updUser($userId, $userInfo)
	{
		$this->load->model(array("common"));

		if ($userInfo['password'] == "") {
			$sql = <<< SQL
update m_user
set 
	login_id = '{$userInfo["login_id"]}',
	user_name = '{$userInfo["user_name"]}',
	mail_address = '{$userInfo["mail_address"]}',
	values01 = '{$userInfo["values01"]}',
	values02 = '{$userInfo["values02"]}',
	values03 = '{$userInfo["values03"]}',
	mission = '{$userInfo["mission"]}',
	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP()
where 
	user_id =  {$userId}
SQL;
		} else {
			$password = md5($userInfo['password']);

			$sql = <<< SQL
update m_user
set 
	login_id = '{$userInfo["login_id"]}',
	user_name = '{$userInfo["user_name"]}',
	mail_address = '{$userInfo["mail_address"]}',
	password = '{$password}',
	values01 = '{$userInfo["values01"]}',
	values02 = '{$userInfo["values02"]}',
	values03 = '{$userInfo["values03"]}',
	mission = '{$userInfo["mission"]}',
	update_user_id = {$userId},
	update_date = CURRENT_TIMESTAMP()
where 
	user_id =  {$userId}
SQL;
		}
		$ret = $this->db->query($sql);

		if ($ret) {		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ユーザー情報更新：ミッションのみ
	 */
	function updUserMission($userId, $mission)
	{
		$sql = <<< SQL
		update m_user
		set 
			mission = '{$mission}',
			update_user_id = {$userId},
			update_date = CURRENT_TIMESTAMP()
		where 
			user_id =  {$userId}
SQL;

		$ret = $this->db->query($sql);

		if ($ret) {		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		} else {
			return false;
		}
	}

	/**
	 * ユーザー情報更新：価値観のみ
	 */
	function updUserValues($userId, $values01, $values02, $values03)
	{
		$sql = <<< SQL
		update m_user
		set 
			values01 = '{$values01}',
			values02 = '{$values02}',
			values03 = '{$values03}',
			update_user_id = {$userId},
			update_date = CURRENT_TIMESTAMP()
		where 
			user_id =  {$userId}
SQL;

		$ret = $this->db->query($sql);

		if ($ret) {		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		} else {
			return false;
		}
	}
}
