<?php
/**
 * コミュニティリスト
 */
class Community_list extends MY_Controller {

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
	public function index($reDispId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$userId = $this->session->userdata("user_id");
		$actionId = $this->input->post("findActionId");

		$date = new DateTime();
		$ymd = $date->format('Y/m/d');

		$this->setReDispId($reDispId);

		$this->actionLogListLoadView($userId,$ymd);
	}



	/**************************************************
	 * private method
	 **************************************************/

	
	/**
	 * actionLogListLoadView
	 */
	private function actionLogListLoadView($userId,$ymd){
		$this->load->model(array("community_manager"));

		// アクションログデータを取得
		$communityRecords = $this->community_manager->getCommunityNowList($userId);

		// 検索結果
		$this->m_items["communityRecordCount"] = arrayCount($communityRecords);
		$this->m_items["communityRecords"] = $communityRecords;

		$this->loadView(self::ACTION_COMMUNITY_LIST);
	}
}