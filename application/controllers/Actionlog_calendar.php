<?php
/**
 * アクションログカレンダー
 */
class Actionlog_calendar extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTIONLOG_CALENDAR);

		$this->m_items['editDialogID'] = "";
		$this->m_items["deleteActionLogFlg"] = false;

	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【Direct:action】
	 * index
	 */
	public function index($reDispId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("action_manager","actiontrigger_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$userId = $this->session->userdata("user_id");

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_now_month();

		$this->setReDispId($reDispId);

		$this->actionLogCalendarLoadView($userId,$actionlogfilterParams);
	}

	/**
	 * 【Direct:action】
	 * actionIdFilter
	 */
	public function actionIdFilter($reDispId,$actionId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("action_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$userId = $this->session->userdata("user_id");

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_now_month();
		$actionlogfilterParams->set_action_id($this->action_manager,$actionId);
		$this->setReDispId($reDispId);

		$this->actionLogCalendarLoadView($userId,$actionlogfilterParams);
	}

	
	/**
	 * 【Direct:action】
	 * actionTriggerIdFilter
	 */
	public function actionTriggerIdFilter($reDispId,$actionTriggerId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("action_manager","actiontrigger_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$userId = $this->session->userdata("user_id");

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_now_month();
		$actionlogfilterParams->set_action_trigger_id($this->actiontrigger_manager,$actionTriggerId);
		$this->setReDispId($reDispId);

		$this->actionLogCalendarLoadView($userId,$actionlogfilterParams);
	}
	
	/**
	 * 【Post:action】
	 * updFilter　：　フィルター更新
	 */
	public function updFilter(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("action_manager","actiontrigger_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$this->setReDispId($this->input->post("ReDispId"));
		$userId = $this->session->userdata("user_id");

		$date = convDateTime($this->input->post("ymd"));
		$yearMonth = $date->format('Y/m');

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_ymd($yearMonth);
		$actionlogfilterParams->set_action_id($this->action_manager,$this->input->post("actionId"));
		$actionlogfilterParams->set_action_trigger_id($this->actiontrigger_manager,$this->input->post("actionTriggerId"));
		$this->actionLogCalendarLoadView($userId,$actionlogfilterParams);
	}

	/**************************************************
	 * private method
	 **************************************************/

	
	/**
	 * actionLogCalendarLoadView
	 */
	private function actionLogCalendarLoadView($userId,$actionlogfilterParams){
		$this->load->model(array("actionlogcalendar_manager","actionlog_manager"));

		// ログタイプ設定
		$actionlogfilterParams->set_log_type(Actionlogfilter_params_entity::LOG_TYPE_CALENDAR);

		// アクションログデータを取得
		$actionLogRecords = $this->actionlogcalendar_manager->getActionLogCalender($userId,$actionlogfilterParams);

		// 検索結果
		$this->m_items["actionLogRecords"] = $actionLogRecords;

		// アクションログフィルターのパラメータを取得
		$this->m_items["partsActionLogFilterParams"] = $actionlogfilterParams->get_params();

		// 日別アクションログリストのパラメータを取得
		$this->m_items["partsDayActionLogListParams"] = $this->actionlog_manager->getPartsDayActionLogListParams(
			$this->m_items["nextReDispId"],
			$actionlogfilterParams->ymd,
			$actionlogfilterParams->actionId,
			$actionlogfilterParams->actionTriggerId);

		$this->loadView(self::ACTION_ACTIONLOG_CALENDAR);
	}
}