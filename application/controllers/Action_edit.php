<?php
/**
 * アクション編集画面
 */
class Action_edit extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTION_EDIT);

		$this->m_items["editCompleteFlg"] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index - アクション編集画面
	 */
	public function editActionData($reDispId,$actionId,$actionTriggerId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
		$this->load->model("actionrun_manager");

		$userId = $this->session->userdata("user_id");
		$this->setReDispId($reDispId);

		$actionInfo = $this->actionrun_manager->getActionRunInfo($userId,$actionId,$actionTriggerId);
		
		$this->actionEditLoadView($actionInfo);
	}

	/*
	 * 【action】
	 * updActionValidation
	 * アクション更新
	 */
	public function updActionValidation(){
		// ログインチェック
		if(!$this->isLogged(false)){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model(array("action_manager","actionrun_manager"));

		$userId = $this->session->userdata("user_id");

		$actionType = $this->input->post("actionType");

		$this->form_validation->set_rules("actionTitle", "アクション名", "required|trim|callback_checkUserActionTitle|callback_checkReleaseActionTitle");
		$this->form_validation->set_rules("actionMessage", "アクション効果", "required|trim");
		$this->form_validation->set_rules("actionDescription", "実行方法", "required|trim");
		if($actionType == action_manager::ACTION_TYPE_COUNT_UP){
			$this->form_validation->set_rules("basicCompleteTimeUp", "目標時間", "required|trim");
		}
		else if($actionType == action_manager::ACTION_TYPE_COUNT_DOWN){
			$this->form_validation->set_rules("basicCompleteTimeDown", "目標時間", "required|trim");
		}

		// POSTデータの読み込み
		$this->readPostData();

		$userId = $this->session->userdata("user_id");
		$actionId = $this->input->post("actionId");
		$actionTriggerId = $this->input->post("actionTriggerId");

		$actionInfo = array();

		if($this->runFormValidation()){
			try {
				$this->db->trans_begin();

				$actionType = $this->input->post("actionType");
				$basicCompleteTime = null;
				if($actionType == action_manager::ACTION_TYPE_COUNT_UP){
					$basicCompleteTime = $this->input->post("basicCompleteTimeUp");
				}
				else if($actionType == action_manager::ACTION_TYPE_COUNT_DOWN){
					$basicCompleteTime = $this->input->post("basicCompleteTimeDown");
				}
				$actionInfo = array(
				"action_id" => $this->input->post("actionId"),
				"action_title" => $this->input->post("actionTitle"),
				"action_description" => $this->input->post("actionDescription"),
				"action_message" => $this->input->post("actionMessage"),
				"action_type" => $actionType,
				"basic_complete_time" => $basicCompleteTime,
				"auth_type" => $this->input->post("authType")
				);

				if(!$this->action_manager->updAction($userId,$actionInfo)){
					throw new Exception("アクション更新");
				}

				// コミット
				$result = $this->db->trans_status();
				if($result === true){
					$this->db->trans_commit();
				}else{
					$this->db->trans_rollback();
				}
				$this->m_items["editCompleteFlg"] = true;

				// EDIT画面を呼び出し
				$actionInfo = $this->actionrun_manager->getActionRunInfo($userId,$actionId,$actionTriggerId);

			} catch(Exception $e) {
				$this->db->trans_rollback();

				throw new Exception($e);
			} finally {
				
				$this->actionEditLoadView($actionInfo);
			}
		}
		else{
			$this->actionEditLoadView($actionInfo);
		}
	}
	/**
	 * 【action】
	 * cancel - アクションキャンセル
	 */
	public function cancelEdit()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}

		$reDispId = $this->input->post("reDispId");

		redirect($this->getActionViewReturnIndex($reDispId));
	}

	
	/**************************************************
	 * public method : callback
	 **************************************************/
	public function checkUserActionTitle($actionTitle)
	{
		$this->load->model(array("action_manager"));

		$userId = $this->session->userdata("user_id");
		$authType = $this->input->post("authType");
		$actionId = $this->input->post("actionId");

		if ($this->action_manager->isUserActionTitle($userId,$actionId,$actionTitle))
		{
			$this->form_validation->set_message('checkUserActionTitle', $actionTitle . "は既に登録されています。");
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	/**
	 * 公開アクションのタイトルが重複していないかチェックする。
	 */
	public function checkReleaseActionTitle($actionTitle)
	{
		$this->load->model(array("action_manager"));

		$userId = $this->session->userdata("user_id");
		$authType = $this->input->post("authType");
		if($authType != action_manager::AUTH_TYPE_PUBLIC){
			return true;
		}

		if ($this->action_manager->isReleaseActionTitle($userId,$actionTitle))
		{
			$this->form_validation->set_message('checkReleaseActionTitle', $actionTitle . "は既に登録されています。");
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * POST情報の読み込み
	 */
	private function readPostData(){
		$this->setReDispId($this->input->post("reDispId"));
		$this->m_items["actionId"] = $this->input->post("actionId");
		$this->m_items["actionTriggerId"] = $this->input->post("actionTriggerId");
		$this->m_items["actionTitle"] = $this->input->post("actionTitle");
		$this->m_items["actionDescription"] = $this->input->post("actionDescription");
		$this->m_items["actionMessage"] = $this->input->post("actionMessage");
		$this->m_items["actionType"] = $this->input->post("actionType");
		$this->m_items["basicCompleteTimeUp"] = $this->input->post("basicCompleteTimeUp");
		$this->m_items["basicCompleteTimeDown"] = $this->input->post("basicCompleteTimeDown");
		$this->m_items["dayActionFlg"] = $this->input->post("dayActionFlg");
		$this->m_items["authType"] = $this->input->post("authType");
	}


	/**
	 * アクション編集画面を表示する
	 */
	private function actionEditLoadView($actionInfo){
		if(count($actionInfo) != 0){
			$actionType = $actionInfo["action_type"];

			$this->m_items["actionId"] = $actionInfo["action_id"];
			$this->m_items["actionTriggerId"] = $actionInfo["action_trigger_id"];
			$this->m_items["actionTitle"] = $actionInfo["action_title"];
			$this->m_items["actionDescription"] = $actionInfo["action_description"];
			$this->m_items["actionMessage"] = $actionInfo["action_message"];
			$this->m_items["actionType"] = $actionInfo["action_type"];
			$this->m_items["authType"] = $actionInfo["auth_type"];

			$this->m_items["basicCompleteTimeUp"] = "";
			$this->m_items["basicCompleteTimeDown"] = "";
			if($actionType == action_manager::ACTION_TYPE_COUNT_UP){
				$this->m_items["basicCompleteTimeUp"] = convTimeStrFromSec( $actionInfo["basic_complete_time"]);
			}
			if($actionType == action_manager::ACTION_TYPE_COUNT_DOWN){
				$this->m_items["basicCompleteTimeDown"] = convTimeStrFromSec( $actionInfo["basic_complete_time"]);
			}
		}

		
		

		$this->loadView(self::ACTION_ACTION_EDIT);
	}
	
}