<?php
class Common extends CI_Model{
	/**************************************************
	 * const 削除フラグ
	 **************************************************/	
	const DELETE_FLG_NONE = 0; // 0:有効
	const DELETE_FLG_DELETE = 1; // 1:削除


	/**
	 * 追加したアクションIDを取得
	 */
	public function getInsertId(){
		$sql= "select LAST_INSERT_ID() insid";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() == 0){
			return false;
		}

		$row = $query->row();

		return $row->insid;
	}
}