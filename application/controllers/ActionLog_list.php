<?php
/**
 * アクションログリスト
 */
class ActionLog_list extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTIONLOG_LIST);

		$this->m_items['editDialogID'] = "";
		$this->m_items["deleteActionLogFlg"] = false;

	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index(){
		$this->load->model(array("action_manager","actiontrigger_manager"));

		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");
		$actionId = $this->input->post("findActionId");
		$actionTriggerId = $this->input->post("findActionTriggerId");

		$date = new DateTime();
		$yearMonth = $date->format('Y/m');
		$yearMonthDisp = $date->format('Y年n月');

		$findActionInfo = array(
			"actionId" => $actionId,
			"actionTitle" => $this->action_manager->getActionTitle($actionId),
			"actionTriggerId" => $actionTriggerId,
			"actionTriggerTitle" => $this->actiontrigger_manager->getActionTriggerTitle($actionTriggerId),
			"yearMonth" => $yearMonth,
			"yearMonthDisp" => $yearMonthDisp,
			"keyword" => $this->input->post("findKeyword")
		);
		$this->actionLogListLoadView($userId,$findActionInfo);
	}

	/**
	 * 【action】
	 * actionIdFilter
	 */
	public function actionIdFilter($actionId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("action_manager"));

		$userId = $this->session->userdata("user_id");

		$date = new DateTime();
		$yearMonth = $date->format('Y/m');
		$yearMonthDisp = $date->format('Y年n月');

		$findActionInfo = array(
			"actionId" => $actionId,
			"actionTitle" => $this->action_manager->getActionTitle($actionId),
			"actionTriggerId" => "",
			"actionTriggerTitle" => "",
			"yearMonth" => $yearMonth,
			"yearMonthDisp" => $yearMonthDisp,
			"keyword" => ""
		);
		$this->actionLogListLoadView($userId,$findActionInfo);
	}

	
	/**
	 * 【action】
	 * actionTriggerIdFilter
	 */
	public function actionTriggerIdFilter($actionTriggerId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");

		$date = new DateTime();
		$yearMonth = $date->format('Y/m');
		$yearMonthDisp = $date->format('Y年n月');

		$findActionInfo = array(
			"actionId" => "",
			"actionTitle" => "",
			"actionTriggerId" => $actionTriggerId,
			"actionTriggerTitle" => $this->actiontrigger_manager->getActionTriggerTitle($actionTriggerId),
			"yearMonth" => $yearMonth,
			"yearMonthDisp" => $yearMonthDisp,
			"keyword" => ""
		);
		$this->actionLogListLoadView($userId,$findActionInfo);
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

		$this->load->model(array("actionlog_manager","action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");
		$actionId = $this->input->post("findActionId");
		$actionTriggerId = $this->input->post("findActionTriggerId");

		$date = new DateTime($this->input->post("findYearMonth") . "/1");
		$yearMonth = $date->format('Y/m');
		$yearMonthDisp = $date->format('Y年n月');

		$findActionInfo = array(
			"actionId" => $actionId,
			"actionTitle" => $this->action_manager->getActionTitle($actionId),
			"actionTriggerId" => $actionTriggerId,
			"actionTriggerTitle" => $this->actiontrigger_manager->getActionTriggerTitle($actionTriggerId),
			"yearMonth" => $yearMonth,
			"yearMonthDisp" => $yearMonthDisp,
			"keyword" => $this->input->post("findKeyword")
		);
		$this->actionLogListLoadView($userId,$findActionInfo);
	}

	/**
	 * アクションログデータを削除
	 */
	public function actionLogDeleteValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("actionlog_manager","action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");
		$actionLogId = $this->input->post("deleteActionLogId");
		
		try{
			$this->db->trans_begin();

			$result = $this->actionlog_manager->delActionLog($userId,$actionLogId);
			if($result === false){
				throw new Exception("error delActionLog");
			}

			// コミット
			$result = $this->db->trans_status();
			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}

			$this->m_items["deleteActionLogFlg"] = true;
		} catch(Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			$actionId = $this->input->post("findActionId");
			$actionTriggerId = $this->input->post("findActionTriggerId");
			$date = new DateTime($this->input->post("findYearMonth") . "/1");
			$yearMonth = $date->format('Y/m');
			$yearMonthDisp = $date->format('Y年n月');
	
			$findActionInfo = array(
				"actionId" => $actionId,
				"actionTitle" => $this->action_manager->getActionTitle($actionId),
				"actionTriggerId" => $actionTriggerId,
				"actionTriggerTitle" => $this->actiontrigger_manager->getActionTriggerTitle($actionTriggerId),	
				"yearMonth" => $yearMonth,
				"yearMonthDisp" => $yearMonthDisp,
				"keyword" => $this->input->post("findKeyword")
			);
	
			$this->actionLogListLoadView($userId,$findActionInfo);
		}
	}


	/**************************************************
	 * private method
	 **************************************************/

	
	/**
	 * actionLogListLoadView
	 */
	private function actionLogListLoadView($userId,$findActionInfo){
		$this->load->model(array("actionlog_manager"));

		// アクションログデータを取得
		$findRecords = $this->actionlog_manager->getActionLogList($userId,$findActionInfo);

		// 検索条件
		$this->m_items["findActionInfo"] = $findActionInfo;
		
		// 検索結果
		$this->m_items["actionlogRecordsCount"] = arrayCount($findRecords);
		$this->m_items["actionlogRecords"] = $findRecords;

		$this->loadView(self::ACTION_ACTIONLOG_LIST);
	}
}