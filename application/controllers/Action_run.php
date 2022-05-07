<?php

/**
 * アクション実行画面
 */
class Action_run extends MY_Controller
{

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct()
	{
		parent::__construct(self::ACTION_ACTION_RUN);

		$this->m_items["actionFlg"] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index - アクション実行
	 * 
	 * ※URLアクセス
	 */
	public function index($reDispId, $actionId, $actionTriggerId, $actionFlg)
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}
		$this->load->model("actionrun_manager");

		$userId = $this->session->userdata("user_id");

		$actionRunInfo = $this->actionrun_manager->getActionRunInfo($userId, $actionId, $actionTriggerId);

		$this->actionRunLoadView($reDispId,$actionRunInfo,$actionFlg);
	}

	/**
	 * 【action】
	 * index - 実行中アクション表示
	 * 
	 * ※URLアクセス
	 */
	public function runAction()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}
		$this->load->model("actionrun_manager");

		$userId = $this->session->userdata("user_id");

		$actionRunInfo = $this->actionrun_manager->getActionRunInfoFromUserId($userId);
		if(!$actionRunInfo){
			// 取得出来なかった場合は、アクションが終了しているのでトップを表示
			redirect("top/index");
			return;
		}

		$this->actionRunLoadView("",$actionRunInfo,true);
	}

	/**
	 * 【action】
	 * cancel - アクションログキャンセル
	 */
	public function cancelActionRunLog()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}
		$this->load->model("actionrun_manager");

		$reDispId = $this->input->post("reDispId");

		$userId = $this->session->userdata("user_id");

		try {
			$this->db->trans_begin();

			if (!$this->actionrun_manager->cancelAction($userId)) {
				throw new Exception("アクションキャンセル処理エラー");
			}

			// コミット
			$result = $this->db->trans_status();
			if ($result === true) {
				$this->db->trans_commit();
			} else {
				$this->db->trans_rollback();
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			//var_dump($this->getActionView($reDispId));
			redirect($this->getActionViewReturnIndex($reDispId));
		}
	}

	/**
	 * 【action】
	 * cancel - アクションキャンセル
	 */
	public function cancelActionRunNormal()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}

		$reDispId = $this->input->post("reDispId");

		redirect($this->getActionViewReturnIndex($reDispId));
	}

	/**
	 * 【action】
	 * actionRunStartValidation
	 * アクション開始
	 */
	public function actionRunStartValidation()
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("action_manager");

		$userId = $this->session->userdata("user_id");
		$actionId = $this->input->post("actionId");
		$actionTriggerId = $this->input->post("actionTriggerId");

		$reDispId = $this->input->post("reDispId");
		$actionFlg = $this->m_items["actionFlg"];

		$actionRunInfo = $this->actionrun_manager->getActionRunInfo($userId, $actionId, $actionTriggerId);

		try {
			$this->db->trans_begin();

			if (!$this->actionrun_manager->startActionLog($userId, $actionRunInfo)) {
				throw new Exception("アクション開始処理エラー");
			}

			// コミット
			$result = $this->db->trans_status();
			if ($result === true) {
				$this->db->trans_commit();
			} else {
				$this->db->trans_rollback();
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			$actionRunInfo = $this->actionrun_manager->getActionRunInfo($userId, $actionId, $actionTriggerId);
			$this->actionRunLoadView($reDispId,$actionRunInfo,$actionFlg);
		}
	}

	/**
	 * 【action】
	 * actionRunCompleteValidation
	 * アクション達成
	 */
/*	public function actionRunCompleteValidation()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("actionrun_manager");

		$userId = $this->session->userdata("user_id");
		$actionLogId = $this->input->post("actionLogId");

		$reDispId = $this->input->post("reDispId");
		$actionFlg = $this->m_items["actionFlg"];

		$actionRunInfo = $this->actionrun_manager->getActionRunInfoFromLogId($actionLogId);

		try {
			$this->db->trans_begin();

			if (!$this->actionrun_manager->completeActionLog($userId, $actionRunInfo)) {
				throw new Exception("error completeActionLog");
			}

			// コミット
			$result = $this->db->trans_status();
			if ($result === true) {
				$this->db->trans_commit();
			} else {
				$this->db->trans_rollback();
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			$actionRunInfo = $this->actionrun_manager->getActionRunInfoFromLogId($actionLogId);
			$this->actionRunLoadView($reDispId,$actionRunInfo,$actionFlg);
		}
	}*/

	/**************************************************
	 * action ajax
	 **************************************************/
	/**
	 * 【action】
	 * actionRunStart
	 */
	public function actionRunStart()
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("action_manager");

		$userId = $this->session->userdata("user_id");
		$actionId = $_POST['actionId'];
		$actionTriggerId = $_POST['actionTriggerId'];

		$actionRunInfo = $this->actionrun_manager->getActionRunInfo($userId, $actionId, $actionTriggerId);

		try {
			$this->db->trans_begin();

			if (!$this->actionrun_manager->startActionLog($userId, $actionRunInfo)) {
				throw new Exception("アクション開始処理エラー");
			}

			// コミット
			$result = $this->db->trans_status();
			if ($result === true) {
				$this->db->trans_commit();
			} else {
				$this->db->trans_rollback();
			}

			$actionRunInfo = $this->actionrun_manager->getActionRunInfoFromUserId($userId);
			
			header('Content-type: application/json; charset=utf-8');
			echo json_encode([
				'actionStatus' => $actionRunInfo["action_status"],
				'actionTimeFrom' => $actionRunInfo["action_time_from"],
				'basicCompleteTime' => $actionRunInfo["basic_complete_time"],
				'actionLogId' => $actionRunInfo["action_log_id"]
				]);
		} catch (Exception $e) {
			$this->db->trans_rollback();

			echo "actionRunStart Exception";
		} 
	}

	/**
	 * 【action】
	 * actionRunComplete
	 * アクション達成
	 */
	public function actionRunComplete()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("actionrun_manager");

		$userId = $this->session->userdata("user_id");
		$actionLogId = $_POST['actionLogId'];
		$actionTimeTo = $_POST['actionTimeTo'];

		$actionRunInfo = $this->actionrun_manager->getActionRunInfoFromLogId($actionLogId);

		try {
			$this->db->trans_begin();

			if (!$this->actionrun_manager->completeActionLog($userId, $actionRunInfo,$actionTimeTo)) {
				throw new Exception("error actionRunComplete");
			}

			// コミット
			$result = $this->db->trans_status();
			if ($result === true) {
				$this->db->trans_commit();
			} else {
				$this->db->trans_rollback();
			}
			header('Content-type: application/json; charset=utf-8');
			$actionState =  ActionLog_manager::ACTION_STATUS_COMPLETE;
			echo json_encode([
				'actionStatus' => $actionState
				]);
		} catch (Exception $e) {
			$this->db->trans_rollback();

			var_dump($e);
			
			throw new Exception($e);
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
		$reDispId = $this->input->post("reDispId");
		$actionLogId = $this->input->post("actionLogId");
		$actionRunInfo = $this->actionrun_manager->getActionRunInfoFromLogId($actionLogId);

		$this->m_items["actionRunInfo"] = $actionRunInfo;
	}

	/**
	 * アクション実行画面を表示する
	 */
	private function actionRunLoadView($reDispId,$actionRunInfo,$actionFlg)
	{
		$this->load->helper('Util_helper');
		$this->load->model("actionrun_manager");

		$this->setReDispId($reDispId);
		if($actionFlg === "1"){
			$this->m_items["actionFlg"] = true;
			$actionRunInfo["action_status"] = Actionlog_manager::ACTION_STATUS_READY;
			$actionRunInfo["action_status_name"] = $this->actionlog_manager->getActionStatusName($actionRunInfo["action_status"]);
		}
		else{
			$this->m_items["actionFlg"] = false;
		}
		
		$this->m_items["actionRunInfo"] = $actionRunInfo;

		$this->loadView(self::ACTION_ACTION_RUN);
	}
}
