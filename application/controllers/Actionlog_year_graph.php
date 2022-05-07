<?php
/**
 * アクションロググラフ(年単位)
 */
class Actionlog_year_graph extends MY_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct(self::ACTION_ACTIONLOG_YEAR_GRAPH);

		$this->m_items['editDialogID'] = "";
		$this->m_items["deleteActionLogFlg"] = false;
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【Direct action】
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
		$actionlogfilterParams->set_now_year();
		$this->setReDispId($reDispId);

		$this->actionLogGraphLoadView($userId,$actionlogfilterParams);
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
		$actionlogfilterParams->set_now_year();
		$actionlogfilterParams->set_action_id($this->action_manager,$actionId);
		$this->setReDispId($reDispId);

		$this->actionLogGraphLoadView($userId,$actionlogfilterParams);
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
		$actionlogfilterParams->set_now_year();
		$actionlogfilterParams->set_action_trigger_id($this->actiontrigger_manager,$actionTriggerId);
		$this->setReDispId($reDispId);

		$this->actionLogGraphLoadView($userId,$actionlogfilterParams);
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
		$this->load->model(array("action_manager","actiontrigger_manager"));
		$this->load->library('entity/Actionlogfilter_params_entity');

		$this->setReDispId($this->input->post("reDispId"));

		$userId = $this->session->userdata("user_id");

		$actionlogfilterParams = new Actionlogfilter_params_entity();
		$actionlogfilterParams->set_year($this->input->post("ymd"));
		$actionlogfilterParams->set_action_id($this->action_manager,$this->input->post("actionId"));
		$actionlogfilterParams->set_action_trigger_id($this->actiontrigger_manager,$this->input->post("actionTriggerId"));

		$this->actionLogGraphLoadView($userId,$actionlogfilterParams);
	}

	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * actionLogGraphLoadView
	 */
	private function actionLogGraphLoadView($userId,$actionlogfilterParams){
		$this->load->model(array("Actionlog_year_graph_manager","actionlog_manager"));

		// ログタイプ設定
		$actionlogfilterParams->set_log_type(Actionlogfilter_params_entity::LOG_TYPE_GRAPH);

		// アクションログデータを取得
		$actionLogRecords = $this->Actionlog_year_graph_manager->getActionLogYearGraph($userId,$actionlogfilterParams);
		
		// 検索結果
		$this->m_items["actionLogRecords"] = $actionLogRecords;

		// アクションログフィルターのパラメータを取得
		$this->m_items["partsActionLogFilterParams"] = $actionlogfilterParams->get_params();


		$this->loadView(self::ACTION_ACTIONLOG_YEAR_GRAPH);
	}
}