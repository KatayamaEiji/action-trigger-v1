<?php
/**
 * 支出データ履歴画面
 */
class Spend_data_list extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct();

		$this->m_items['editDialogID'] = "";
		$spendDataEditItems['badgetDataId'] = ""; // 予算データID
		$spendDataEditItems['spendModalDay'] = "";
		$spendDataEditItems['spendModalSpend'] = "";
		$spendDataEditItems['spendModalMemo'] = "";		
		$this->m_items['spend_data_edit_items'] = $spendDataEditItems;

		$spendDataEntryItems['badgetDataId'] = ""; // 予算データID
		$spendDataEntryItems['spendModalDay'] = "";
		$spendDataEntryItems['spendModalSpend'] = "";
		$spendDataEntryItems['spendModalMemo'] = "";		
		$spendDataEntryItems['currencyUnit'] = "";
		$this->m_items['spend_data_entry_items'] = $spendDataEntryItems;

		$this->m_items['nowDate'] = date("Y-m-d");
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index($badgetDataId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");

		$this->spendLoadView($userId,$badgetDataId);
	}

	/**
	 * 【action】
	 * spendDataEntryValidation
	 * 支出データを登録する。
	 */
	public function spendDataEntryValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$badgetDataId = $this->input->post("badgetDataID");

		if($this->partsSpendDataEntry()){
			// 登録できた場合は、再表示
			redirect("Spend_data_list/index/" . $badgetDataId);
			return;
		}

		$this->m_items['editDialogID'] = $this->input->post("editDialogID");
		$userId = $this->session->userdata("user_id");

		$this->spendLoadView($userId,$badgetDataId);
	}

	/**
	 * 支出データを削除
	 */
	public function spendDataDeleteValidation(){
		$this->load->model(array("spend_manager"));

		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$badgetDataId = $this->input->post("badgetDataID");
		$deleteSpendDataId = $this->input->post("deleteSpendDataID");

		if($this->partsSpendDataDelete($badgetDataId,$deleteSpendDataId)){
			// 登録できた場合は、再表示
			redirect("Spend_data_list/index/" . $badgetDataId);
			return;
		}

		$this->spendLoadView($userId,$badgetDataId);
	}

	/**
	 * 【action】
	 * spendDataEditValidation
	 * 支出データ編集する。
	 */
	public function spendDataEditValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$badgetDataId = $this->input->post("badgetDataID");

		if($this->partsSpendDataUpdate()){
			// 登録できた場合は、再表示
			redirect("Spend_data_list/index/" . $badgetDataId);
			return;
		}

		$this->m_items['editDialogID'] = $this->input->post("editDialogID");
		$userId = $this->session->userdata("user_id");

		$this->spendLoadView($userId,$badgetDataId);
	}

	/**
	 * 【action】
	 * badgetDataEditValidation
	 * 予算データを編集する。
	 */
	public function badgetDataEditValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$badgetDataId = $this->input->post("badgetDataID");

		if($this->partsBadgetDataEdit()){
			// 登録できた場合は、再表示
			redirect("Spend_data_list/index/" . $badgetDataId);
			return;
		}

		$this->m_items['editDialogID'] = $this->input->post("editDialogID");
		$userId = $this->session->userdata("user_id");

		$this->spendLoadView($userId,$badgetDataId);
	}

	/**************************************************
	 * private method
	 **************************************************/
	
	/**
	 * topLoadView
	 */
	private function spendLoadView($userId,$badgetDataId){
		$this->load->model(array("Spend_data_list_manager","system_manager","badget_manager"));

		// 通貨単位を取得
		$currencyUnit = $this->system_manager->getCurrencyUnit($userId);

		// 予算データを取得
		$badgetItems = $this->badget_manager->getBadgetData($userId,$badgetDataId);

		// 支出データ一覧を取得
		$findRecords = $this->Spend_data_list_manager->getSpendListData($userId,$badgetDataId);

		$prevNextItems = $this->badget_manager->getBadgetDataPrevNext($userId,$badgetItems["badget_id"],$badgetDataId);

		// 検索結果を格納
		$this->m_items["badgetDataId"] = $badgetDataId;
		$this->m_items["currencyUnit"] = $currencyUnit;
		
		$this->m_items["badgetFromTo"] = $badgetItems["badget_from_to"];
		$this->m_items["badgetTitle"] = $badgetItems["badget_title"];
		$this->m_items["badgetLabel"] = $badgetItems["badget_label"];
		$this->m_items["badgetMoney"] = $badgetItems["badget_money"];
		$this->m_items["spendSumMoney"] = $badgetItems["spend_sum_money"];
		$this->m_items["badgetBalanceMoney"] = $badgetItems["badget_balance_money"];
		
		$this->m_items["prevBadgetDataId"] = $prevNextItems["prev_id"];
		$this->m_items["nextBadgetDataId"] = $prevNextItems["next_id"];

		// 予算編集データ
		$this->m_items["badgetFromDate"] = $badgetItems["badget_from_date"];
		$this->m_items["badgetToDate"] = $badgetItems["badget_to_date"];
		$this->m_items["badgetMoney"] = $badgetItems["badget_money"];
		$this->m_items["badgetMemo"] = $badgetItems["memo"];

		$this->m_items["spendRecordCount"] = count($findRecords);
		$this->m_items["spendRecords"] = $findRecords;

		$this->m_items['spend_data_entry_items']['currencyUnit'] = $currencyUnit;
		$this->m_items['badget_data_edit_items']['currencyUnit'] = $currencyUnit;

		$this->loadView(self::ACTION_SPEND_DATA_LIST);
	}
}