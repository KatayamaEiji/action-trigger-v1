<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badget_all_entry extends MY_Controller {

	/**************************************************
	 * action 
	 **************************************************/
	/**
	 * 【action】
	 * badgetAll
	 */
	public function badgetAll(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model("badget_manager");

		$userId = $this->session->userdata("user_id");

		$findRecords = $this->badget_manager->getBadgetAllListData($userId);

		$this->badgetAllLoadView($userId,$findRecords);
    }  

	/**
	 * 【action】
	 * badgetAllValidation
	 * 予算一括登録する。
	 */
	public function badgetAllValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("badget_manager");

		$recordCount = $this->input->post("recordCount");
		$rowNum = 1;
		for($rowNum=1;$rowNum<=$recordCount;$rowNum++){
			$this->form_validation->set_rules("badget".$rowNum, "予算額", "integer|less_than_equal_to[0]|greater_than_equal_to[9999999]");
		}
		$this->form_validation->set_rules("entry_submit", "予算登録", "callback_validateBadgetSum");

		$userId = $this->session->userdata("user_id");

		if($this->runFormValidation()){
			$this->db->trans_begin();

			for($rowNum=1;$rowNum<=$recordCount;$rowNum++){
				$appKbnId = $this->input->post("appKbnId_".$rowNum);
				$appKbnName = $this->input->post("appKbnName_".$rowNum);
				$badgetMoney = $this->input->post("badget_".$rowNum);

				if($badgetMoney && $badgetMoney != ""){
					$this->badget_manager->addBadget($userId,$appKbnName,$badgetMoney);
				}
			}

			// 予算マスタから予算データ作成
			$ret = $this->badget_manager->createBadgetDate($userId);

			// コミット
			$result = $this->db->trans_complete();

			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}

			redirect($this->getActionView(self::ACTION_TOP));
		}else{
			// 一括予算データ
			$findRecords = array();

			$rowNum = 1;
			while($this->input->post('appKbnId_'.$rowNum)){
				$baget = $this->input->post('badget_'.$rowNum);

				$findRecords[]= array(
					"app_kbn_id" =>$this->input->post('appKbnId_'.$rowNum),
					"app_kbn_name" =>$this->input->post('appKbnName_'.$rowNum),
					"badget"=>$baget
				);

				$rowNum = $rowNum + 1;
			}

			$this->badgetAllLoadView($userId,$findRecords);
		}
	}

	/**
	 * 【callback】
	 * validateBadgetSum
	 */
	public function validateBadgetSum(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
				
		$this->load->model("user_manager");

		$count = $this->input->post("recordCount");
		$rowNum = 1;
		$ret = 0;
		for($rowNum=1;$rowNum<=$count;$rowNum++){
			if($this->input->post("badget_".$rowNum)){
				$ret += $this->input->post("badget_".$rowNum);
			}
		}

		// 合計が０の場合エラー
		if($ret > 0){
			return true;
		}else{
			return false;
		}
    }
	
	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * badgetAllLoadView
	 */
	private function badgetAllLoadView($userId,$findRecords){
		$this->load->model("system_manager");

		// 通貨単位を取得
		$currencyUnit = $this->system_manager->getCurrencyUnit($userId);

		// 検索結果を格納
		$this->m_items["currencyUnit"] = $currencyUnit;
		$this->m_items["recordCount"] = count($findRecords);
		$this->m_items["findRecords"] = $findRecords;

		$this->loadView(self::ACTION_BADGET_ALL_ENTRY);
	}
}