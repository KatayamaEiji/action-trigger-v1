<?php
class System_manager extends CI_Model{
	/**
	 * 初期データが登録されているか確認する
	 */
	public function chkInitData($userId){	
		$this->db->where("user_id", $userId);	

		$query = $this->db->get("m_system");

		if($query->num_rows() >= 1){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * システムマスタを登録する。
	 */
	public function addSystem($userId){
		$sql = <<< SQL
INSERT INTO m_system(
user_id,
currency_unit,
rate,
home_currency_unit,

create_date,
create_user_id,
update_date,
update_user_id
)
VALUES
 ({$userId}, '円', 1, '円',now(),{$userId},now(),{$userId})
SQL;

		$query=$this->db->query($sql);

		if($query){		//データ取得が成功したらTrue、失敗したらFalseを返す
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 通貨単位を取得する。
	 */
	public function getCurrencyUnit($userId){
		$this->db->where("user_id", $userId);	
		$user = $this->db->get("m_system");		

		if($user){
			$row = $user->row();

			return $row->currency_unit;
		}
		return false;
	}	
}