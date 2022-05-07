<?php

/**
 * ユーザー設定画面
 */
class User_edit extends MY_Controller
{

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct()
	{
		parent::__construct(self::ACTION_USER_EDIT);
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index($reDispId)
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->model("user_manager");

		$userId = $this->session->userdata("user_id");
		$userInfo = $this->user_manager->getUser($userId);

		// 検索結果を格納
		$this->m_items["usName"] = $userInfo["user_name"];
		$this->m_items["loginId"] = $userInfo["login_id"];
		$this->m_items["mailAddress"] = $userInfo["mail_address"];
		$this->m_items["password"] = "";
		$this->m_items["passconf"] = "";

		$this->m_items["values01"] = $userInfo["values01"];
		$this->m_items["values02"] = $userInfo["values02"];
		$this->m_items["values03"] = $userInfo["values03"];
		$this->m_items["mission"] = $userInfo["mission"];

		$this->setReDispId($reDispId);

		$this->loadView(self::ACTION_USER_EDIT);
	}

	/*
	 * 【action】
	 * updUserValidation
	 * ユーザー情報更新
	 */
	public function updUserValidation()
	{
		// ログインチェック
		if (!$this->isLogged(false)) {
			return;
		}

		$this->load->library("form_validation");	//フォームバリデーションのライブラリを読み込む
		$this->load->model("user_manager");

		$userId = $this->session->userdata("user_id");

		$this->form_validation->set_rules("loginId", "ログインID", "required|trim");
		$this->form_validation->set_rules("usName", "ユーザー名", "required|trim");
		$this->form_validation->set_rules("mailAddress", "メールアドレス", "required|valid_email");

		if ($this->input->post("passconf") != "") {
			$this->form_validation->set_rules("password", "パスワード", "required|trim");
			$this->form_validation->set_rules("passconf", "パスワード(確認）", "required|trim|matches[password]");
		}

		// POSTデータの読み込み
		$this->readPostData();

		if ($this->runFormValidation()) {
			try {
				$this->db->trans_begin();

				$userInfo = array(
					"login_id" => $this->input->post("loginId"),
					"user_name" => $this->input->post("usName"),
					"mail_address" => $this->input->post("mailAddress"),
					"password" => $this->input->post("password"),
					"values01" => $this->input->post("values01"),
					"values02" => $this->input->post("values02"),
					"values03" => $this->input->post("values03"),
					"mission" => $this->input->post("mission")
				);

				if (!$this->user_manager->updUser($userId, $userInfo)) {
					throw new Exception("ユーザー更新エラー");
				}

				// コミット
				$result = $this->db->trans_status();
				if ($result === true) {
					$this->db->trans_commit();
				} else {
					$this->db->trans_rollback();
				}
				$this->m_items["editCompleteFlg"] = true;
			} catch (Exception $e) {
				$this->db->trans_rollback();

				throw new Exception($e);
			} finally {

				// EDIT画面を呼び出し
				$this->loadView(self::ACTION_USER_EDIT);
			}
		} else {
			$this->loadView(self::ACTION_USER_EDIT);
		}
	}

	/**
	 * 【action】
	 * updMission
	 */
	public function updMission()
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->model("user_manager");

		$userId = $this->session->userdata("user_id");
		$userInfo = $this->user_manager->getUser($userId);

		if (isset($_POST['mission'])) {
			$mission = $_POST['mission'];

			try {
				if (!$this->user_manager->updUserMission($userId, $mission)) {
					throw new Exception("ユーザー更新エラー");
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

				echo "updMission Exception";
			} finally {
				echo $mission;
			}
		} else {
			echo 'FAIL TO AJAX REQUEST';
		}
	}


	/**
	 * 【action】
	 * updValues
	 */
	public function updValues()
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->model("user_manager");

		$userId = $this->session->userdata("user_id");
		$userInfo = $this->user_manager->getUser($userId);

		if (isset($_POST['values01'])) {
			$values01 = $_POST['values01'];
			$values02 = $_POST['values02'];
			$values03 = $_POST['values03'];

			try {
				if (!$this->user_manager->updUserValues($userId, $values01, $values02, $values03)) {
					throw new Exception("ユーザー更新エラー");
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

				echo "updMission Exception";
			} finally {
				$list = array("values01" => $values01, "values02" => $values02, "values03" => $values03);

				// 明示的に指定しない場合は、text/html型と判断される
				header("Content-type: application/json; charset=UTF-8");
				//JSONデータを出力
				echo json_encode($list);
			}
		} else {
			echo 'FAIL TO AJAX REQUEST';
		}
	}
	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * POST情報の読み込み
	 */
	private function readPostData()
	{
		$this->m_items["loginId"] = $this->input->post("loginId");
		$this->m_items["usName"] = $this->input->post("usName");
		$this->m_items["mailAddress"] = $this->input->post("mailAddress");
		$this->m_items["password"] = $this->input->post("password");
		$this->m_items["passconf"] = "";
		$this->m_items["values01"] = $this->input->post("values01");
		$this->m_items["values02"] = $this->input->post("values02");
		$this->m_items["values03"] = $this->input->post("values03");
		$this->m_items["mission"] = $this->input->post("mission");
	}
}
