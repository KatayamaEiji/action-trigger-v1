<?php
/**
 * 定期予算編集画面
 */
class Badget_edit extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct();
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index - 新規作成
	 */
	public function index(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");

		$this->badgetEntryLoadView($userId);
	}

	/**
	 * 【action】
	 * edit - 編集画面
	 */
	public function edit($badgetId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");

		$this->badgetEditLoadView($userId,$badgetId);
	}

	/**
	 * 【action】
	 * badgetEditValidation
	 * 定期予算編集する。
	 */
	public function badgetEditValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("badget_manager");

		$userId = $this->session->userdata("user_id");
		$badgetId = $this->input->post("badgetId");
		$periodUnit = $this->input->post("periodUnit");

		$this->form_validation->set_rules("badgetTitle", "タイトル", "required|trim");
		$this->form_validation->set_rules("badgetMoney", "予算額", "required|integer|greater_than_equal_to[1]|less_than_equal_to[9999999]");

		if($periodUnit == badget_manager::PERIOD_UNIT_MONTH){
			$this->form_validation->set_rules("badgetActivationDay", "予算日", "required|integer|greater_than_equal_to[1]|less_than_equal_to[28]");
		}
		elseif($periodUnit == badget_manager::PERIOD_UNIT_YEAR){
			$this->form_validation->set_rules("badgetActivationMonth", "予算月", "required|integer|greater_than_equal_to[1]|less_than_equal_to[12]");
			$this->form_validation->set_rules("badgetActivationDay", "予算日", "required|integer|callback_badgetActivationDayValidateDate");
		}

		if($this->runFormValidation()){
			$this->db->trans_begin();

			$badgetTitle = $this->input->post("badgetTitle");
			$badgetMoney = $this->input->post("badgetMoney");
			
			$badgetActivationMonth = $this->input->post("badgetActivationMonth");
			$badgetActivationDay = $this->input->post("badgetActivationDay");
			$badgetDispUnit = $this->input->post("badgetDispUnit");
			$carryForwardKbn = $this->input->post("carryForwardKbn");
			if($carryForwardKbn == ""){
				$carryForwardKbn = 0;
			}
			$badgetEditMemo = $this->input->post("badgetEditMemo");

			$this->badget_manager->updBadgetMaster($userId,$badgetId,
							$periodUnit,$badgetDispUnit,$badgetActivationDay,$badgetActivationMonth,
							$badgetTitle,$badgetMoney,$carryForwardKbn,$badgetEditMemo);

			// コミット
			$result = $this->db->trans_complete();

			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}

			redirect($this->getActionView(self::ACTION_BADGET_EDIT) . "/edit/" . $badgetId);
		}else{
			$this->badgetEditLoadView($userId,$badgetId);
		}
	}

	/**
	 * 【callback】
	 * badgetActivationDayValidateDate
	 */
	public function badgetActivationDayValidateDate($badgetActivationDay){
		$this->load->model("user_manager");

		$badgetActivationMonth = $this->input->post("badgetActivationMonth");

		if($badgetActivationMonth == 2 && ($badgetActivationDay < 1 || $badgetActivationDay > 28)){
			return false;
		} 

		if(!$this->chkDate("2019/" . $badgetActivationMonth . "/" . $badgetActivationDay)){
			return false;
		}
		return true;
    }

	/**************************************************
	 * private method
	 **************************************************/
	
	/**
	 * 定期予算登録画面を表示する
	 */
	private function badgetEntryLoadView($userId){
		$this->load->model(array("system_manager","badget_manager"));

		// 通貨単位を取得
		$currencyUnit = $this->system_manager->getCurrencyUnit($userId);

		// 検索結果を格納
		$this->m_items["currencyUnit"] = $currencyUnit;

		$this->loadView(self::ACTION_BADGET_ENTRY);
	}
	
	/**
	 * 定期予算編集画面を表示する
	 */
	private function badgetEditLoadView($userId,$badgetId){
		$this->load->model(array("system_manager","badget_manager"));

		// 通貨単位を取得
		$currencyUnit = $this->system_manager->getCurrencyUnit($userId);

		// 定期予算マスタを取得
		$badgetItems = $this->badget_manager->getBadgetMaster($userId,$badgetId);

		// 検索結果を格納
		$this->m_items["badgetId"] = $badgetId;
		$this->m_items["currencyUnit"] = $currencyUnit;

		$this->m_items["badgetTitle"] = $badgetItems['badget_title'];
		$this->m_items["badgetMoney"] = $badgetItems['badget_money'];
		$this->m_items["periodUnit"] = $badgetItems['period_unit'];
		$this->m_items["badgetActivationDay"] = $badgetItems['badget_activation_day'];
		$this->m_items["badgetActivationMonth"] = $badgetItems['badget_activation_month'];
		$this->m_items["carryForwardKbn"] = $badgetItems['carry_forward_kbn'];
		$this->m_items["state"] = $badgetItems['state'];
		$this->m_items["badgetDispUnit"] = $badgetItems['badget_disp_unit'];
		$this->m_items["memo"] = $badgetItems['memo'];
		
		$this->loadView(self::ACTION_BADGET_EDIT);
	}
}