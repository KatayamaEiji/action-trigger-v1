<?php
/**
 * アクションログ共通
 */
class Actionlog extends CI_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	/*function __construct(){
		parent::__construct(self::ACTION_ACTIONLOG);
	}*/

	/**************************************************
	 * action
	 **************************************************/
    public function test($ymd,$actionId) {
		// https://qiita.com/horikeso/items/69b5329d87b30aa35d68

		$this->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode([
			'ymd' => $ymd,
			'actionId' => $actionId,
		]));
	}

	/**
	 * GET
	 */
	public function getDayActionList($ymd,$actionId) {
		$this->load->model(array("actionlog_manager"));

		$userId = $this->session->userdata("user_id");

		// アクションログデータを取得
		$findRecords = $this->actionlog_manager->getDayActionLogList($userId,$ymd,$actionId);

		$items = [
			"findRecords" => $findRecords
		];


		$this->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode($items));
	}

	/**
	 * 【Post: action】
	 * アクションログデータを削除
	 */
	public function delActionLog(){
		$this->load->model(array("actionlog_manager","action_manager","actiontrigger_manager"));

		$userId = $this->session->userdata("user_id");
		$actionLogId = $this->input->post("actionLogId");
		
		try{
			$this->db->trans_begin();

			$result = $this->actionlog_manager->delActionLog($userId,$actionLogId);
			if($result === false){
				throw new Exception("error delActionLog");
			}

			// コミット
			$result = $this->db->trans_status();
			if($result === true){
				$this->db->trans_commit();
			}else{
				$this->db->trans_rollback();

				throw new Exception("error trans_rollback");
			}

			$items = [
				"result" => "OK"
			];
		} catch(Exception $e) {
			$this->db->trans_rollback();

			$items = [
				"result" => "NG",
				"error" => $e
			];
		} finally {
		}

		$this->output
		->set_content_type('application/json')
		->set_status_header(200)
		->set_output(json_encode($items));
	}


}