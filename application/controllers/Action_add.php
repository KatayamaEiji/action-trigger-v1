<?php
/**
 * アクション新規追加画面
 */
class Action_add extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTION_ADD);

		$this->m_items["editCompleteFlg"] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index - アクション新規追加画面
	 */
	public function addActionData($reDispId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}
		$this->load->model("action_manager");

		$userId = $this->session->userdata("user_id");

		// 初期値
		$this->m_items["actionTitle"] = "";
		$this->m_items["actionDescription"] = "";
		$this->m_items["actionMessage"] = "";
		$this->m_items["actionType"] = 3;
		$this->m_items["basicCompleteTimeUp"] = null;
		$this->m_items["basicCompleteTimeDown"] = null;
		$this->m_items["dayActionFlg"] = true;
		$this->m_items["authType"] = 0;
		$this->setReDispId($reDispId);

		$this->actionAddLoadView();
	}

	/*
	 * 【action】
	 * addActionValidation
	 * アクション追加
	 */
	public function addActionValidation(){
		// ログインチェック
		if(!$this->isLogged(false)){
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model(array("common","action_manager","actiontrigger_manager","actionrun_manager"));

		$userId = $this->session->userdata("user_id");
		$actionType = $this->input->post("actionType");
		$reDispId = $this->input->post("reDispId");

		$this->form_validation->set_rules("actionTitle", "アクション名", "required|trim|callback_checkUserActionTitle|callback_checkReleaseActionTitle");
		$this->form_validation->set_rules("actionMessage", "アクション効果", "required|trim");
		$this->form_validation->set_rules("actionDescription", "実行方法", "required|trim");

		if($actionType == action_manager::ACTION_TYPE_COUNT_UP){
			$this->form_validation->set_rules("basicCompleteTimeUp", "目標時間", "required|trim");
		}
		if($actionType == action_manager::ACTION_TYPE_COUNT_DOWN){
			$this->form_validation->set_rules("basicCompleteTimeDown", "目標時間", "required|trim");
		}

		// POSTデータの読み込み
		$this->readPostData();

		$userId = $this->session->userdata("user_id");

		if($this->runFormValidation()){
			try {
				$this->db->trans_begin();

				$actionType = $this->input->post("actionType");

				$basicCompleteTime = "";
				if($actionType == action_manager::ACTION_TYPE_COUNT_UP){
					$basicCompleteTime = $this->input->post("basicCompleteTimeUp");
				}
				if($actionType == action_manager::ACTION_TYPE_COUNT_DOWN){
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

				if(!$this->action_manager->addAction($userId,$actionInfo)){
					throw new Exception('addAction エラー');
				}

				$actionId = $this->common->getInsertId();

				if(!$this->action_manager->addUserIdAction($userId,$actionId)){
					throw new Exception('addUserIdAction エラー');
				}

				// 今日のアクションに追加
				$dayActionFlg = $this->input->post("dayActionFlg");
				if($dayActionFlg){
					if(!$this->actiontrigger_manager->addActionTrigger($userId,$actionId)){
						throw new Exception('addActionTrigger エラー');
					}
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
				// LIST画面を呼び出し
				redirect($this->getActionViewReturnIndex($reDispId));
			}
		}
		else{
			$this->actionAddLoadView();
		}
	}

	/**
	 * 【action】
	 * 画像アップロード
	 */
	/*public function updateImage()
	{
		if(!$this->runInit()){return;}

		$this->load->model("product_image");

		// 標準アイテム情報を読み込む
		$this->m_items = array_merge($this->m_items, $this->defaultItems());

		// ポスト情報の読み込み
		$this->read_post();

		// ログインID取得
		$userId = $this->session->userdata("user_id");

		$pictureFileName = $this->Product_image->do_upload($loginID);

		$pictureUrl = $this->Product_image->get_thumbnailUrl($pictureFileName);

		$pictureRecords = $this->m_items['pictureRecords'];

		$newFlg = true;
		$maxid = 0;
		foreach($pictureRecords as &$item){
			$pictureID = $item['pictureID'];

			if($curPictureID == $pictureID){

				$item['pictureFileName'] = $pictureFileName;
				$item['pictureUrl'] = $pictureUrl;
				$newFlg = false;
				break;
			}
			if($maxid < $pictureID){
				$maxid = $pictureID;
			}
		}

		if ($newFlg){
			$maxid = $maxid + 1;
			$pictureRecords[] = array(
				"pictureID" =>$maxid,
				"pictureFileName" =>$pictureFileName,
				"pictureUrl" =>$pictureUrl
			);
		}

		$this->m_items['pictureRecords'] = $pictureRecords;

		$this->actionAddLoadView();
	}*/

	/**
	 * 【action】
	 * 画像削除
	 */
	/*public function deleteImage()
	{
		if(!$this->runInit()){return;}

		$this->load->model("product_image");

		// 標準アイテム情報を読み込む
		$this->m_items = array_merge($this->m_items, $this->defaultItems());

		// ポスト情報の読み込み
		$this->read_post();

		// ログインID取得
		$loginID = $this->session->userdata('loginID');

		$curPictureID = $this->input->post("pictureID");
		$pictureRecords = $this->m_items['pictureRecords'];

		$maxid = 0;

		foreach($pictureRecords as $key => $item){
			$pictureID = $item['pictureID'];

			if($pictureID == $curPictureID){
				$pictureFileName = $item['pictureFileName'];

				// 画像ファイルとサムネイルの画像を削除
				$this->Product_image->do_remove($loginID,$pictureFileName);

				// 配列から削除
				unset($pictureRecords[$key]);
				break;
			}
		}
		$this->m_items['pictureRecords'] = $pictureRecords;

		// ビューを読み込む
		$this->actionAddLoadView();
	}*/

	/**
	 * 新規作成画面をキャンセルする。
	 */
	public function cancelAdd(){
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

		if ($this->action_manager->isUserActionTitle($userId,"",$actionTitle))
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
			$this->form_validation->set_message('checkUserActionTitle', $actionTitle . "は既に登録されています。");
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
	 * アクション新規追加画面を表示する
	 */
	private function actionAddLoadView(){
		$this->loadView(self::ACTION_ACTION_ADD);
	}
	
}