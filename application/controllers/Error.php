<?php
/**
 * エラー画面
 */
class Error extends CI_Controller {

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct(){
		parent::__construct();
	}

	/**************************************************
	 * action
	 **************************************************/
	/**
	 * 【action】
	 * エラー画面を表示
	 */
	function error_404() {
        // 404を返さないと検索エンジンに404画面が登録されてしまう。
        $this->output->set_status_header('404');

		$this->load->view('errors/cli/404');
	}
}