<?php
/**
 * アクショントリガー編集画面
 */
class ActionTrigger_Edit extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTION_TRIGGER_EDIT);

		$this->m_items["editCompleteFlg"] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index - アクション新規追加画面
	 */
	public function index($actionTriggerId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
		$this->load->model("actiontrigger_manager");

		
		$userId = $this->session->userdata("user_id");

		$actionTriggerInfo = $this->actiontrigger_manager->getActionTriggerInfo($actionTriggerId);

		$this->m_items["actionTriggerId"] = $actionTriggerInfo["action_trigger_id"];
		$this->m_items["actionTitle"] = $actionTriggerInfo["action_title"];
		
		$this->m_items["kigenFrom"] = convDateStrFromDBDateTime($actionTriggerInfo["kigen_from"]);
		$this->m_items["kigenTo"] = convDateStrFromDBDateTime($actionTriggerInfo["kigen_to"]);

		$this->actionTriggerLoadView();
	}

	/*
	 * 【action】
	 * updActionTriggerValidation
	 * アクション編集更新
	 */
	public function updActionTriggerValidation(){
		// ログインチェック
		if(!$this->isLogged(false)){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("actiontrigger_manager");

		$userId = $this->session->userdata("user_id");

		// POSTデータの読み込み
		$this->readPostData();

		$this->form_validation->set_rules('kigenTo', 'date', 'required|callback_chkDateValidate');

		if($this->runFormValidation()){
			try {
				$this->db->trans_begin();

				$actionTriggerInfo = array(
				"action_trigger_id" => $this->input->post("actionTriggerId"),
				"kigen_to" => $this->input->post("kigenTo")
				);

				if(!$this->actiontrigger_manager->updActionTrigger($userId,$actionTriggerInfo)){
					throw new Exception("アクショントリガー更新　失敗");
				}

				// コミット
				$result = $this->db->trans_status();
				if($result === true){
					$this->db->trans_commit();
				}else{
					$this->db->trans_rollback();
				}
				$this->m_items["editCompleteFlg"] = true;
			} catch(Exception $e) {
				$this->db->trans_rollback();

				throw new Exception($e);
			} finally {
				$this->actionTriggerLoadView();
			}
		}
		else{
			$this->actionTriggerLoadView();
		}
	}

	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * POST情報の読み込み
	 */
	private function readPostData(){
		$this->m_items["actionTriggerId"] = $this->input->post("actionTriggerId");
		$this->m_items["actionTitle"] = $this->input->post("actionTitle");
		$this->m_items["kigenFrom"] = $this->input->post("kigenFrom");
		$this->m_items["kigenTo"] = $this->input->post("kigenTo");
	}

	/**
	 * アクショントリガー編集画面を表示する
	 */
	private function actionTriggerLoadView(){
		$this->loadView(self::ACTION_ACTION_TRIGGER_EDIT);
	}
	
}