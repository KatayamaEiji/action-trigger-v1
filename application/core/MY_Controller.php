<?php

/**
 * コントローラ基本クラス
 */
class MY_Controller extends CI_Controller
{

	/**************************************************
	 * const
	 * 10000台：システム
	 * 20000台：アクション関連
	 * 30000台：ログ関連
	 **************************************************/
	// ACTION TYPE
	const ACTION_LOGIN = 10001; //ログイン
	const ACTION_TOP = 10002; //トップ（メニュー）
	const ACTION_RESTRICTED = 10003; //ログインされていない
	const ACTION_SIGNUP = 10004; //仮会員登録
	const ACTION_SIGNUP_RESULT = 10005; // 仮会員登録完了

	const ACTION_ACTION_RUN = 20001; //アクション実行
	const ACTION_ACTION_LIST = 20003; //アクションリスト
	const ACTION_ACTION_ADD = 20010; // アクション新規作成
	const ACTION_ACTION_EDIT = 20011; // アクション編集
	const ACTION_ACTION_TRIGGER_EDIT = 20021; // アクショントリガー編集

	const ACTION_ACTIONCOM_LIST = 20004; // 共有アクションリスト

	const ACTION_ACTIONLOG = 30001; // アクションログリスト
	const ACTION_ACTIONLOG_LIST = 30003; // アクションログリスト（月）
	const ACTION_ACTIONLOG_CALENDAR = 30004; // アクションログカレンダー（月）
	const ACTION_ACTIONLOG_GRAPH = 30005; // アクションロググラフ（月）
	const ACTION_ACTIONLOG_YEAR_LIST = 30006; // アクションログリスト(年)
	const ACTION_ACTIONLOG_YEAR_CALENDAR = 30007; // アクションログカレンダー(年)
	const ACTION_ACTIONLOG_YEAR_GRAPH = 30008; // アクションロググラフ(年)
	const ACTION_ACTIONLOG_EDIT = 30011; // アクション編集

	const ACTION_COMMUNITY_LIST = 40001; // コミュニティリスト

	const ACTION_USER_EDIT = 90001; // ユーザー編集

	/**************************************************
	 * member
	 **************************************************/
	protected $m_items;

	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct($dispId)
	{
		parent::__construct();

		$this->load->database();

		// Webページの有効期限が切れてます」となる時の傾向と対策(library('session')前に実行する必要あり！
		//session_cache_limiter('none');
		//$this->load->library('session');

		//$this->load->model(array('user_maneger'));// モデルのロード
		//$this->lang->load('greeting', 'japanese');

		$this->m_items['title'] = "";
		$this->m_items['info'] = "";
		$this->m_items['dispId'] = $dispId;
		$this->m_items['reDispId'] = $dispId;
		$this->m_items['nextReDispId'] = $dispId;

		$actionView = $this->getActionView($dispId);

		$partsMenu = array(
			"dispId" => $dispId,
			"dispView" => $actionView
		);
		$this->m_items['partsMenu'] = $partsMenu;

		$this->config->load('client');
		$this->m_items['vuejsUrl'] = $this->config->item('vuejsUrl');
		$this->m_items['axiosUrl'] = $this->config->item('axiosUrl');
		$this->m_items['chartjsUrl'] = $this->config->item('chartjsUrl');
		$this->m_items['fontawesomeAllUrl'] = $this->config->item('fontawesomeAllUrl');
		$this->m_items['fontawesomeSolidUrl'] = $this->config->item('fontawesomeSolidUrl');
		$this->m_items['fontAwesomeAnimationUrl'] = $this->config->item('fontAwesomeAnimationUrl');

		$this->m_items['bootstrapCssUrl'] = $this->config->item('bootstrapCssUrl');
		$this->m_items['bootstrapJsUrl'] = $this->config->item('bootstrapJsUrl');
		$this->m_items['jqueryJsUrl'] = $this->config->item('jqueryJsUrl');
		$this->m_items['bootstrapPopperJsUrl'] = $this->config->item('bootstrapPopperJsUrl');
	}

	/**
	 * 呼び出し元のDispIDをセット
	 */
	protected function setReDispId($reDispId){
		$this->m_items['reDispId'] = $reDispId;
		// 次画面に渡す前画面情報
		$this->m_items['nextReDispId'] = $reDispId . "-" . $this->m_items['dispId'];
	}

	/**************************************************
	 * action - top
	 **************************************************/
	/**
	 * 【action】-top
	 * logout
	 */
	public function logout()
	{
		$this->session->sess_destroy();

		$cookieTime = time()-1;
		setcookie("userid","",$cookieTime, "/");
		setcookie("loginid","",$cookieTime, "/");
		setcookie("username","",$cookieTime, "/");
		setcookie("isloggedin",0,$cookieTime, "/");

		redirect("Main/login");
	}

	/**************************************************
	 * protected method
	 **************************************************/
	/**
	 * ログインチェック
	 */
	protected function isLogged($chkActionFlg = true)
	{
		$this->load->model(array("actionrun_manager"));

		if ($this->session->userdata("is_logged_in")) {		//ログインしている場合の処理
			$userId = $this->session->userdata("user_id");

			// 実行中のアクションがある場合はアクション画面に遷移
			if ($chkActionFlg) {
				if ($this->actionrun_manager->isActionRun($userId)) {
					redirect("Action_run/runAction");
					return false;
				}
			}
			return true;
		}


		//ログインしていない場合の処理
		redirect("Main/restricted");

		return false;
	}

	/**
	 * フォーム検証を行う
	 */
	protected function runFormValidation()
	{
		//ライブラリ呼び出し
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		if ($this->form_validation->run() == false) {
			return false;
		}
		return true;
	}

	/**
	 * ビューを表示
	 * @param $actionType
	 */
	protected function loadView($actionType)
	{
		$title = $this->getTitle($actionType);
		$actionView = $this->getActionView($actionType);

		$this->m_items['title'] = $title;
		$this->m_items['userName'] = $this->session->userdata("user_name");;

		$this->load->view($actionView, $this->m_items);
	}


	/**
	 * タイトル取得
	 * @param unknown_type $actionType
	 */
	protected function getTitle($actionType)
	{
		$title = "";
		switch ($actionType) {
				//-----------------------------------------------------
			case self::ACTION_LOGIN: //ログイン
				$title = 'ログイン';
				break;
			case self::ACTION_TOP: //トップ画面
				$title = 'トップ';
				break;
			case self::ACTION_SIGNUP: //仮会員登録
				$title = '会員登録';
				break;
			case self::ACTION_SIGNUP_RESULT:
				$title = '登録完了';
				break;
			case self::ACTION_RESTRICTED:
				$title = '';
				break;
			case self::ACTION_ACTION_RUN: //アクション実行
				$title = 'アクション実行';
				break;
			case self::ACTION_ACTION_LIST:
				$title = 'アクションリスト';
				break;
			case self::ACTION_ACTION_ADD:
				$title = 'アクション新規作成';
				break;
			case self::ACTION_ACTION_EDIT:
				$title = 'アクション編集';
				break;

			case self::ACTION_ACTIONCOM_LIST:
				$title = '共有アクション一覧';
				break;

			case self::ACTION_ACTIONLOG_LIST: // アクションログリスト
				$title = 'アクションログ';
				break;
			case self::ACTION_ACTIONLOG_CALENDAR: // アクションログカレンダー
				$title = 'アクションログカレンダー';
				break;
			case self::ACTION_ACTIONLOG_GRAPH: // アクションロググラフ
				$title = 'アクションロググラフ';
				break;

				case self::ACTION_ACTIONLOG_YEAR_LIST: // アクションログリスト
					$title = 'アクションログ';
					break;
				case self::ACTION_ACTIONLOG_YEAR_CALENDAR: // アクションログカレンダー
					$title = 'アクションログカレンダー';
					break;
				case self::ACTION_ACTIONLOG_YEAR_GRAPH: // アクションロググラフ
				$title = 'アクションロググラフ';
				break;
	
			case self::ACTION_ACTIONLOG_EDIT: // アクション記録編集
				$title = 'アクションログ編集';
				break;

			case self::ACTION_ACTION_TRIGGER_EDIT:
				$title = 'アクショントリガー編集';
				break;

			case self::ACTION_COMMUNITY_LIST:
				$title = 'コミュニティ';
				break;


			case self::ACTION_USER_EDIT:
				$title = 'ユーザー編集';
				break;
		}

		return $title;
	}

	/**
	 * アクションに対するViewの情報を取得する
	 * @param unknown_type $actionType
	 */
	protected function getActionView($actionType)
	{
		$view = "top"; // デフォルト画面

		switch ($actionType) {
				//-----------------------------------------------------
			case self::ACTION_LOGIN: //ログイン
				$view = 'login';
				break;
			case self::ACTION_TOP: //トップ（メニュー）
				$view = 'top';
				break;
			case self::ACTION_SIGNUP: //仮会員登録ページ
				$view = 'signup';
				break;
			case self::ACTION_SIGNUP_RESULT:
				$view = 'signup_result';
				break;
			case self::ACTION_RESTRICTED:
				$view = 'restricted';
				break;

			case self::ACTION_ACTION_RUN: //アクション実行
				$view = 'action_run';
				break;
			case self::ACTION_ACTION_LIST: // アクションリスト
				$view = 'action_list';
				break;
			case self::ACTION_ACTION_ADD: // アクション新規追加
				$view = 'action_add';
				break;
			case self::ACTION_ACTION_EDIT: // アクション編集
				$view = 'action_edit';
				break;

			case self::ACTION_ACTIONCOM_LIST: // 共有アクションリスト
				$view = 'actioncom_list';
				break;

			case self::ACTION_ACTIONLOG_LIST: // アクションログリスト
				$view = 'actionlog_list';
				break;
			case self::ACTION_ACTIONLOG_CALENDAR: // アクションログカレンダー
				$view = 'actionlog_calendar';
				break;
			case self::ACTION_ACTIONLOG_GRAPH: // アクションロググラフ
				$view = 'actionlog_graph';
				break;


			case self::ACTION_ACTIONLOG_YEAR_LIST: // アクションログリスト
				$view = 'actionlog_year_list';
				break;
			case self::ACTION_ACTIONLOG_YEAR_CALENDAR: // アクションログカレンダー
				$view = 'actionlog_year_calendar';
				break;
			case self::ACTION_ACTIONLOG_YEAR_GRAPH: // アクションロググラフ
				$view = 'actionlog_year_graph';
				break;
			case self::ACTION_ACTIONLOG_EDIT: // アクションログ編集
				$view = 'actionlog_edit';
				break;


			case self::ACTION_ACTION_TRIGGER_EDIT: // アクショントリガー編集
				$view = 'action_trigger_edit';
				break;

			case self::ACTION_COMMUNITY_LIST:
				$view = 'community_list';
				break;


			case self::ACTION_USER_EDIT:
				$view = 'user_edit';
				break;
		}

		return $view;
	}

	/**
	 * 戻る処理
	 * 
	 * @property string|array $dispIds
	 */
	public function getActionViewReturnIndex(string $reDispId)
	{
		$dispIds = (array) explode("-", $reDispId);
		$actionType = array_pop($dispIds);

		$url = $this->getActionView($actionType);
		$url .= "/index/";
		$url .= implode("-", $dispIds);

		return $url;
	}

	/**
	 * 日付型チェック
	 */
	public function chkDateValidate($date)
	{
		$formats = [
			'Y-m-d',
			'Y/m/d',
			'Ymd',
		];
		foreach ($formats as $format) {
			DateTime::createFromFormat($format, $date);
			$result = DateTime::getLastErrors();
			if (!$result['warning_count'] && !$result['error_count']) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * 時刻型チェック
	 */
	public function chkTimeValidate($time)
	{
		$formats = [
			'H:i',
		];
		foreach ($formats as $format) {
			DateTime::createFromFormat($format, $time);
			$result = DateTime::getLastErrors();
			if (!$result['warning_count'] && !$result['error_count']) {
				return TRUE;
			}
		}
		return FALSE;
	}
}
