<?php
/**
 * 共有アクション一覧
 */
class Actioncom_list extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTIONCOM_LIST);

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

		$this->actionComListLoadView($userId,$findActionInfo);
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

		$this->actionComListLoadView($userId,$findActionInfo);
	}

	/**
	 * 【action】
	 * addActionComValidation　：アクション追加処理
	 */
	public function addActionComValidation(){
		// ログインチェック
		if(!$this->isLogged(false)){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model(array("common","action_manager","actiontrigger_manager","actionrun_manager"));

		$userId = $this->session->userdata("user_id");
		$reDispId = $this->input->post("reDispId");
		$addActionId = $this->input->post("addActionId");

		try {
			$this->db->trans_begin();

			if(!$this->action_manager->addUserIdAction($userId,$addActionId)){
				throw new Exception('addUserIdAction エラー');
			}

			// トップ画面から呼ばれた場合、今日のアクションに追加
			if($reDispId == self::ACTION_TOP){
				if(!$this->actiontrigger_manager->addActionTrigger($userId,$addActionId)){
					throw new Exception('addActionTrigger エラー');
				}
				$actionTriggerId = $this->common->getInsertId();
			}

			// コミット
			$result = $this->db->trans_status();
			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();
			}
		} catch(Exception $e) {
			$this->db->trans_rollback();

			throw new Exception($e);
		} finally {
			// 元画面を呼び出し
			redirect($this->getActionViewReturnIndex($reDispId));
		}
	}

	/**************************************************
	 * private method
	 **************************************************/

	/**
	 * actionComListLoadView
	 */
	private function actionComListLoadView($userId,$findActionInfo){
		$this->load->model(array("actioncom_list_manager"));

		// 共有アクションデータを取得
		$actionRecords = $this->actioncom_list_manager->getActionComList($userId,$findActionInfo);

		// 検索条件
		$this->m_items["findActionInfo"] = $findActionInfo;
		
		// 検索結果
		$this->m_items["actionRecordCount"] = arrayCount($actionRecords);
		$this->m_items["actionRecords"] = $actionRecords;

		$this->m_items["actionMenuItems"] = array("nowActionAdd" => false);

		$this->loadView(self::ACTION_ACTIONCOM_LIST);
	}
}