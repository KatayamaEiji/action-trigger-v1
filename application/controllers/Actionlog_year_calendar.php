<?php
/**
 * アクションログリスト
 */
class Actionlog_year_calendar extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTIONLOG_YEAR_CALENDAR);
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【Direct: action】
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

		$this->actionLogYearCalendarLoadView($userId,$actionlogfilterParams);
	}

	/**
	 * 【Direct: action】
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

		$this->actionLogYearCalendarLoadView($userId,$actionlogfilterParams);
	}

	
	/**
	 * 【Direct: action】
	 * actionTriggerIdFilter
	 */
	public function actionTriggerIdFilter($reDispId,$actionTriggerId){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("actiontrigger_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$userId = $this->session->userdata("user_id");

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_now_month();
		$actionlogfilterParams->set_action_trigger_id($this->actiontrigger_manager,$actionTriggerId);

		$this->setReDispId($reDispId);

		$this->actionLogYearCalendarLoadView($userId,$actionlogfilterParams);
	}
	
	/**
	 * 【Post: action】
	 * updFilter　：　フィルター更新
	 */
	public function updFilter(){
		// ログインチェック
		if(!$this->isLogged()){
			return;
		}

		$this->load->model(array("actionlog_manager","action_manager","actiontrigger_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$this->setReDispId($this->input->post("ReDispId"));
		$userId = $this->session->userdata("user_id");

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_ym($this->input->post("ymd"));
		$actionlogfilterParams->set_action_id($this->action_manager,$this->input->post("actionId"));
		$actionlogfilterParams->set_action_trigger_id($this->actiontrigger_manager,$this->input->post("actionTriggerId"));

		$this->actionLogYearCalendarLoadView($userId,$actionlogfilterParams);
	}

	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * actionLogYearCalendarLoadView
	 */
	private function actionLogYearCalendarLoadView($userId,$actionlogfilterParams){
		$this->load->model(array("actionlog_year_calendar_manager"));

		// ログタイプ設定
		$actionlogfilterParams->set_log_type(Actionlogfilter_params_entity::LOG_TYPE_CALENDAR);

		// アクションログフィルターのパラメータ設定
		$actionlogfilterParams->set_disp_id($this->m_items['dispId'],$this->m_items['reDispId']);

		// アクションログデータを取得
		$actionlogRecords = $this->actionlog_year_calendar_manager->getActionYearLogList($userId,$actionlogfilterParams);
		
		// 検索結果
		$this->m_items["actionlogRecordsCount"] = arrayCount($actionlogRecords);
		$this->m_items["actionlogRecords"] = $actionlogRecords;

		$this->m_items["partsActionLogFilterParams"] = $actionlogfilterParams->get_params();

		$this->loadView(self::ACTION_ACTIONLOG_YEAR_CALENDAR);
	}
}