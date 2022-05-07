<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Top extends MY_Controller
{
	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct()
	{
		parent::__construct(self::ACTION_TOP);

		$this->m_items['nowDate'] = date("Y-m-d");
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * index
	 */
	public function index()
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->model("top_manager");

		$userId = $this->session->userdata("user_id");

		$nowActionRecords = $this->top_manager->getNowActionListData($userId);
		$usedActionRecords = $this->top_manager->getUsedActionListData($userId);

		$this->topLoadView($userId, $nowActionRecords, $usedActionRecords);
	}

	/**
	 * アクショントリガーを継続する
	 */
	public function Continuation($actionTriggerId)
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->model(array("actiontrigger_manager", "top_manager"));

		// 継続状態が「０：未継続」以外の場合は終了
		if ($this->actiontrigger_manager->getContinueKbn($actionTriggerId) != ActionTrigger_manager::CONTINUE_KBN_MI) {
			$userId = $this->session->userdata("user_id");

			$nowActionRecords = $this->top_manager->getNowActionListData($userId);
			$usedActionRecords = $this->top_manager->getUsedActionListData($userId);

			$this->topLoadView($userId, $nowActionRecords, $usedActionRecords);
			return;
		}

		try {
			$this->db->trans_begin();

			$userId = $this->session->userdata("user_id");

			// 元のアクショントリガーから新しいアクショントリガーを作る。
			$result = $this->actiontrigger_manager->continuationActionTrigger($userId, $actionTriggerId);
			if ($result === false) {
				throw new Exception("error continuationActionTrigger");
			}

			// 元のアクショントリガーを継続済みにする。
			$result = $this->actiontrigger_manager->updActionTriggerContinueKbn($userId, $actionTriggerId, ActionTrigger_manager::CONTINUE_KBN_KEIZOKU);
			if ($result === false) {
				throw new Exception("error updActionTriggerContinueKbn");
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
			$userId = $this->session->userdata("user_id");

			$nowActionRecords = $this->top_manager->getNowActionListData($userId);
			$usedActionRecords = $this->top_manager->getUsedActionListData($userId);

			$this->topLoadView($userId, $nowActionRecords, $usedActionRecords);
		}
	}

	/**
	 * アクショントリガーを継続しない
	 */
	public function NotContinuation($actionTriggerId)
	{
		// ログインチェック
		if (!$this->isLogged()) {
			return;
		}

		$this->load->model(array("actiontrigger_manager", "top_manager"));

		try {
			$this->db->trans_begin();

			$userId = $this->session->userdata("user_id");

			// 元のアクショントリガーを継続終了にする。
			$result = $this->actiontrigger_manager->updActionTriggerContinueKbn($userId, $actionTriggerId, ActionTrigger_manager::CONTINUE_KBN_KEIZOKU_END);
			if ($result === false) {
				throw new Exception("error updActionTriggerContinueKbn");
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
			$userId = $this->session->userdata("user_id");

			$nowActionRecords = $this->top_manager->getNowActionListData($userId);
			$usedActionRecords = $this->top_manager->getUsedActionListData($userId);

			$this->topLoadView($userId, $nowActionRecords, $usedActionRecords);
		}
	}

	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * topLoadView
	 */
	private function topLoadView($userId, $nowActionRecords, $usedActionRecords)
	{
		$this->load->model(array("community_manager", "user_manager"));

		//$this->m_items['infoItems'] = $this->community_manager->getUserInfoList($userId);
		$this->m_items['userItems'] = $this->user_manager->getUser($userId);

		// 検索結果を格納
		$this->m_items["nowActionRecordCount"] = arrayCount($nowActionRecords);
		$this->m_items["nowActionRecords"] = $nowActionRecords;

		$this->m_items["usedActionRecordCount"] = arrayCount($usedActionRecords);
		$this->m_items["usedActionRecords"] = $usedActionRecords;


		$this->m_items["actionMenuItems"] = array("nowActionAdd" => false);
		$this->m_items["actionAddMenuItems"] = array("nowActionAdd" => false);

		$this->loadView(self::ACTION_TOP);
	}
}
