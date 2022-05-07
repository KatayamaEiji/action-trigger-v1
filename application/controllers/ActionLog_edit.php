<?php
/**
 * アクションログ編集画面
 */
class ActionLog_edit extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_USER_EDIT);

		$this->m_items["editCompleteFlg"] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index($actionLogId,$reDispId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model("actionlog_manager");

		$userId = $this->session->userdata("user_id");
		$actionLog = $this->actionlog_manager->getActionLog($userId,$actionLogId);

		// 検索結果を格納
		$this->m_items["reDispId"] = $reDispId;
		$this->m_items["actionLogId"] = $actionLogId;
		$this->m_items["actionTitle"] = $actionLog["action_title"];
		$this->m_items["actionType"] = $actionLog["action_type"];
		
		$this->m_items["actionTimeFrom"] = $actionLog["action_time_from"];

		$this->m_items["actionTimeFromDay"] = convDateStrFromDBDateTime2($actionLog["action_time_from"]);
		$this->m_items["actionTimeFromTime"] = convTimeStrFromDBDateTime($actionLog["action_time_from"]);
		$this->m_items["actionTimeToDay"] = convDateStrFromDBDateTime($actionLog["action_time_to"]);
		$this->m_items["actionTimeToTime"] = convTimeStrFromDBDateTime($actionLog["action_time_to"]);

		$this->loadView(self::ACTION_ACTIONLOG_EDIT);
	}

	/*
	 * 【action】
	 * updActionLogValidation
	 * アクションログ情報更新
	 */
	public function updActionLogValidation(){
		// ログインチェック
		if(!$this->isLogged(false)){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("actionlog_manager");

		$userId = $this->session->userdata("user_id");

		$this->form_validation->set_rules("actionTimeToDay", "終了日", "required|callback_chkDateValidate");
		$this->form_validation->set_rules("actionTimeToTime", "終了時間", "required|callback_chkTimeValidate|callback_validateActionTime");

		// POSTデータの読み込み
		$this->readPostData();

		if($this->runFormValidation()){
			try {
				$this->db->trans_begin();

				$actionTimeTo = $this->input->post("actionTimeToDay") . " " . $this->input->post("actionTimeToTime");

				$actionLogInfo = array(
				"action_log_id" => $this->input->post("actionLogId"),
				"action_time_to" => $actionTimeTo
				);

				if(!$this->actionlog_manager->updActionLog($userId,$actionLogInfo)){
					throw new Exception("アクションログ更新エラー");
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
				// EDIT画面を呼び出し
				$this->loadView(self::ACTION_ACTIONLOG_EDIT);
			}
		}
		else{
			$this->loadView(self::ACTION_ACTIONLOG_EDIT);
		}
	}

	/**
	 * 【action】
	 * cancel - アクションログ編集キャンセル
	 */
	public function cancelActionLogEdit(){
		// ログインチェック
		if(!$this->isLogged(false)){
			return;
		}

		$reDispId = $this->input->post("reDispId");

		redirect($this->getActionView($reDispId) . "/index");
	}

	/**************************************************
	 * 【callback】
	 **************************************************/
	/**
	 * 【callback】
	 * validateActionTime
	 */
	public function validateActionTime(){		//Email情報がPOSTされたときに呼び出されるコールバック機能
		$actionTimeFrom =  $this->input->post("actionTimeFrom");
		$actionTimeTo =  $this->input->post("actionTimeToDay") . " " . $this->input->post("actionTimeToTime");

		if($actionTimeFrom === "" || $actionTimeTo === " "){
			// 未入力の場合はチェックしない。
			return true;
		}

		$dtFrom = new DateTime($actionTimeFrom);
		$dtTo = new DateTime($actionTimeTo);

		if($dtFrom < $dtTo){
			return true;
		}else{
			return false;
		}
	}

	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * POST情報の読み込み
	 */
	private function readPostData(){
		// 検索結果を格納
		$this->m_items["reDispId"] = $this->input->post("reDispId");
		$this->m_items["actionLogId"] = $this->input->post("actionLogId");
		$this->m_items["actionTitle"] = $this->input->post("actionTitle");
		$this->m_items["actionType"] = $this->input->post("actionType");
		
		$this->m_items["actionTimeFrom"] = $this->input->post("actionTimeFrom");

		$actionTimeFrom = $this->input->post("actionTimeFrom");
		$actionTimeTo = $this->input->post("actionTimeTo");

		$this->m_items["actionTimeFromDay"] = convDateStrFromDBDateTime2($actionTimeFrom);
		$this->m_items["actionTimeFromTime"] = convTimeStrFromDBDateTime($actionTimeFrom);
		$this->m_items["actionTimeToDay"] = $this->input->post("actionTimeToDay");
		$this->m_items["actionTimeToTime"] = $this->input->post("actionTimeToTime");
	}

	
}