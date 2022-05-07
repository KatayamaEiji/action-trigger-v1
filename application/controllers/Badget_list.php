<?php
/**
 * 定期予算リスト
 */
class Badget_list extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct();

		$this->m_items['editDialogID'] = "";

	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");

		$this->badgetListLoadView($userId,"");
	}

	/**
	 * 【action】
	 * findList　：検索処理
	 */
	public function findList(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");
		$findBadgetTitle = $this->input->post("findBadgetTitle");

		$this->badgetListLoadView($userId,$findBadgetTitle);
	}

	/**
	 * 定期予算マスタを削除
	 */
	public function badgetDeleteValidation(){
		$this->load->model(array("badget_manager"));

		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$badgetId = $this->input->post("badgetID");
		if($this->partsBadgetDelete($badgetId)){
			// 登録できた場合は、再表示
			redirect("Badget_list/index/");
			return;
		}

		$this->badgetListLoadView($userId,"");
	}


	/**************************************************
	 * private method
	 **************************************************/
	
	/**
	 * badgetListLoadView
	 */
	private function badgetListLoadView($userId,$findBadgetTitle){
		$this->load->model(array("Spend_data_list_manager","system_manager","badget_manager"));

		// 通貨単位を取得
		$currencyUnit = $this->system_manager->getCurrencyUnit($userId);

		// 予算データを取得
		$findRecords = $this->badget_manager->getBadgetList($userId,$findBadgetTitle);

		// 検索結果を格納
		$this->m_items["currencyUnit"] = $currencyUnit;

		// 検索条件
		$this->m_items["findBadgetTitle"] = $findBadgetTitle;
		
		// 検索結果
		$this->m_items["badgetRecordCount"] = count($findRecords);
		$this->m_items["badgetRecords"] = $findRecords;

		$this->loadView(self::ACTION_BADGET_LIST);
	}
}