<?php
/**
 * アクションリスト
 */
class Action_list extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTION_LIST);

		$this->m_items['editDialogID'] = "";
		$this->m_items['deleteActionFlg'] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index($reDispId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");

		$findActionInfo = array(
			"actionTitle" => $this->input->post("findActionTitle")
		);
		$this->setReDispId($reDispId);

		$this->actionListLoadView($userId,$findActionInfo);
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

		$findActionInfo = array(
			"actionTitle" => $this->input->post("findActionTitle")
		);
		$this->setReDispId($this->input->post("reDispId"));

		$this->actionListLoadView($userId,$findActionInfo);
	}

	/**
	 * アクションデータを削除
	 */
	public function actionDeleteValidation(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
		$this->setReDispId($this->input->post("reDispId"));

		$this->load->model(array("action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");
		$actionId = $this->input->post("deleteActionId");
		
		try{
			$this->db->trans_begin();

			// アクショントリガー削除（論理削除）
			$result = $this->actiontrigger_manager->delActionTriggerFromActionId($userId,$actionId);
			if($result === false){
				throw new Exception("error delActionTrigger");
			}
			// ユーザー関連アクションマスタ削除（論理削除）
			$result = $this->action_manager->delUserInAction($userId,$actionId);
			if($result === false){
				throw new Exception("error delUserInAction");
			}
			// アクション削除（論理削除）
			$result = $this->action_manager->delAction($userId,$actionId);
			if($result === false){
				throw new Exception("error delAction");
			}

			// コミット
			$result = $this->db->trans_status();
			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}

			$this->m_items['deleteActionFlg'] = true;
		} catch(Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			$findActionInfo = array(
				"actionTitle" => $this->input->post("findActionTitle")
			);
	
			$this->actionListLoadView($userId,$findActionInfo);
		}
	}

	/**
	 * 今日のアクションを削除
	 */
	public function delNowActionTrigger($dispId,$actionTriggerId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
		$reDispId= $this->input->post("reDispId");
		$this->setReDispId($reDispId);

		$this->load->model(array("action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");

		try{
			$this->db->trans_begin();

			// アクショントリガー削除（論理削除）
			$result = $this->actiontrigger_manager->delActionTrigger($userId,$actionTriggerId);
			if($result === false){
				throw new Exception("error delActionTrigger");
			}
		
			// コミット
			$result = $this->db->trans_status();
			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}

			$this->m_items['deleteActionTriggerFlg'] = true;
		} catch(Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			redirect($this->getActionViewReturnIndex($reDispId));
		}
	}

	/**
	 * 今日のアクションに追加
	 */
	public function addNowActionTrigger($actionId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
		$this->setReDispId($this->input->post("reDispId"));

		$this->load->model(array("action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");

		try{
			$this->db->trans_begin();

			// アクショントリガー削除（論理削除）
			$result = $this->actiontrigger_manager->addActionTrigger($userId,$actionId);
			if($result === false){
				throw new Exception("error addActionTrigger");
			}
		
			// コミット
			$result = $this->db->trans_status();
			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}

			$this->m_items['deleteActionTriggerFlg'] = true;
		} catch(Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			$findActionInfo = array(
				"actionTitle" => $this->input->post("findActionTitle")
			);
	
			$this->actionListLoadView($userId,$findActionInfo);
		}
	}

	/**************************************************
	 * private method
	 **************************************************/

	/**
	 * actionListLoadView
	 */
	private function actionListLoadView($userId,$findActionInfo){
		$this->load->model(array("actionlist_manager"));

		// アクションログデータを取得
		$actionRecords = $this->actionlist_manager->getActionList($userId,$findActionInfo);

		// 検索条件
		$this->m_items["findActionInfo"] = $findActionInfo;
		
		// 検索結果
		$this->m_items["actionRecordCount"] = arrayCount($actionRecords);
		$this->m_items["actionRecords"] = $actionRecords;

		$this->m_items["actionMenuItems"] = array("nowActionAdd" => false);
		$this->m_items["actionAddMenuItems"] = array("nowActionAdd" => false);

		$this->loadView(self::ACTION_ACTION_LIST);
	}
}