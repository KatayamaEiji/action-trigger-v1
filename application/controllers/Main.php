<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{
	/**************************************************
	 * constructor / destructor
	 **************************************************/
	function __construct()
	{
		parent::__construct(self::ACTION_LOGIN);

		$this->load->model(array("Community_manager"));

		$this->m_items['infoItems'] = $this->Community_manager->getInfoList();
	}

	/**************************************************
	 * action - login
	 **************************************************/
	/**
	 * 【action】- login
	 * index
	 */
	public function index()
	{
		$this->login();
	}

	/**
	 * 【action】- login
	 * login
	 */
	public function login()
	{
		$this->load->model(array("community_manager"));
		$this->load->helper('cookie');

		//
		if(isset($_COOKIE["isloggedin"])){
			$data = array(
				"user_id" => $_COOKIE["userid"],
				"login_id" => $_COOKIE["loginid"],
				"user_name" => $_COOKIE["username"],
				"is_logged_in" => 1
			);
			$this->session->set_userdata($data);

			redirect("Top/index");
			return;
		}

		$this->m_items['infoItems'] = $this->community_manager->getInfoList();

		$this->loadView(self::ACTION_LOGIN);
	}

	/**
	 * 【action】- login
	 * signup
	 */
	public function signup()
	{
		$this->m_items["t_mail_address"] = "";
		$this->m_items["t_password"] = "";

		$this->loadView(self::ACTION_SIGNUP);
	}

	/**
	 * 【action】
	 * restricted - ログインされていない画面。
	 */
	public function restricted()
	{
		//
		if(isset($_COOKIE["isloggedin"])){
			$data = array(
				"user_id" => $_COOKIE["userid"],
				"login_id" => $_COOKIE["loginid"],
				"user_name" => $_COOKIE["username"],
				"is_logged_in" => 1
			);
			$this->session->set_userdata($data);

			redirect("Top/index");
			return;
		}

		$this->loadView(self::ACTION_RESTRICTED);
	}

	/**
	 * 【action】
	 * loginValidation
	 */
	public function loginValidation()
	{
		$this->load->model(array('user_manager'));
		$this->load->helper('cookie');
		$this->load->library("form_validation");	//フォームバリデーションライブラリを読み込む。
		//利用頻度の高いライブラリ（HTMLヘルパー、URLヘルパーなど）はオートロード設定をしますが、
		//フォームバリデーションライブラリはログインバリデーションライブラリ内のみで読み込みます。

		$this->form_validation->set_rules("login_id", "ログインID", "required|trim");	//login_id入力欄のバリデーション設定
		$this->form_validation->set_rules("password", "パスワード", "required|md5|trim");	//パスワード入力欄のバリデーション設定
		$this->form_validation->set_rules("login_submit", "パスワード", "callback_validateCredentials");

		if ($this->runFormValidation()) {		//バリデーションエラーがなかった場合の処理
			$user = $this->user_manager->getUserFromLoginid($this->input->post("login_id"));

			$data = array(
				"user_id" => $user["user_id"],
				"login_id" => $user["login_id"],
				"user_name" => $user["user_name"],
				"is_logged_in" => 1
			);
			$this->session->set_userdata($data);

			//session_start();

			$cookieTime = time()+60*60*24*7;
			setcookie("userid",$user["user_id"],$cookieTime, "/");
			setcookie("loginid",$user["login_id"],$cookieTime, "/");
			setcookie("username",$user["user_name"],$cookieTime, "/");
			setcookie("isloggedin",1,$cookieTime, "/");

			redirect("Top/index");
		} else {						//バリデーションエラーがあった場合の処理

			$this->loadView(self::ACTION_LOGIN);
		}
	}

	/**************************************************
	 * action - signup
	 **************************************************/
	/**
	 * 【action】- signup
	 * signupValidation
	 * ユーザーの仮登録
	 */
	public function signupValidation(){
		$this->load->library(array("form_validation"));	//フォームバリデーションのライブラリを読み込む
	
		$this->form_validation->set_rules("t_mail_address", "メールアドレス", "required|trim|valid_email|is_unique[m_user.mail_address]");
		$this->form_validation->set_rules("t_password", "パスワード", "required|trim");
		$this->form_validation->set_rules("t_cpassword", "パスワードの確認", "required|trim|matches[t_password]");

		//$this->form_validation->set_message("is_unique", "入力したメールアドレスはすでに登録されています");

		if($this->runFormValidation()){
			// echo "Success!!";

			//ランダムキーを生成する
			$key=md5(uniqid());

			//Emailライブラリを読み込む。メールタイプをHTMLに設定（デフォルトはテキストです）
			$this->load->library("email", array("mailtype"=>"html"));
			$this->load->model("user_manager");

			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'imadakeha.sakura.ne.jp'; // SMTPサーバアドレス
			$config['smtp_port'] = 587; // SMTPサーバTCPポート番号
			$config['smtp_user'] = 'dev-support@imadakeha.sakura.ne.jp'; // SMTP認証ユーザ名
			$config['smtp_pass'] = '9VmdwYgp'; // SMTP認証パスワード
			$config['smtp_timeout'] = 7; // SMTP接続のタイムアウト(秒)
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
			$config['mailtype'] = 'text'; // or html
			$config['validation'] = TRUE; // bool whether to validate email or not      

			$this->email->initialize($config);

			$this->email->from("dev-support@imadakeha.sakura.ne.jp", "送信元");		//送信元の情報
			$this->email->to($this->input->post("t_mail_address"));	//送信先の設定
			$this->email->subject("仮の会員登録が完了しました。");	//タイトルの設定

			//メッセージの本文
			$message = "ActionTrigger 登録ありがとうございます。\n";

			// 各ユーザーにランダムキーをパーマリンクに含むURLを送信する
			$message .= "メールアドレスを確認してください。";
			$message .= "\n";
			$message .= "\n";
			$message .= "ActionTriggerにご登録ありがとうございます！アカウントを設定するため、下にあるボタンをクリックしてメールアドレスを確定してください。";
			$message .= "\n";

			$message .= base_url(). "main/resisterUser/" . $key;
			$message .= "\n";
			$message .= "※このメールは、ActionTriggerアカウントの作成をリクエストしたお客様にお送りしています。";
			$message .= "\n";

			$this->email->message($message);

			//addTempUsersファンクションがTrueを返したら、メール送信を実行
			if($this->user_manager->addTempUsers($key)){
				if($this->email->send()){
					//echo $message;
				}
				else{
					echo $this->email->print_debugger();
				}
			}

			$this->loadView(self::ACTION_SIGNUP_RESULT);
		}else{
			$this->m_items["t_mail_address"] = $this->input->post("t_mail_address");
			$this->m_items["t_password"] = $this->input->post("t_password");

			$this->loadView(self::ACTION_SIGNUP);
		}
	}

	/**
	 * 【action】
	 * resisterUser
	 * ユーザーを登録する。
	 */
	public function resisterUser($key){
		//add_temp_usersモデルが書かれている、model_uses.phpをロードする
		$this->load->model(array('user_manager','system_manager'));

		$ret = false;
		if($this->user_manager->isValidKey($key)){	//キーが正しい場合は、以下を実行
			$this->db->trans_begin();

			$ret = $this->user_manager->addUser($key);	

			if ($ret && $this->db->trans_status()){
				// コミット
				$this->db->trans_commit();

				$this->m_items['info'] = "ありがとうございます。ユーザーが登録されました。";

				// ログイン画面を開く
				$this->loadView(self::ACTION_LOGIN);
			}else{
				$this->db->trans_rollback();

				// ログイン画面を開く
				$this->m_items['info'] = $this->lang->line('error_user_entry');

				$this->loadView(self::ACTION_LOGIN);
			}		
		}
	}
	
	/**************************************************
	 * callback
	 **************************************************/
	/**
	 * 【callback】
	 * validateCredentials
	 */
	public function validateCredentials()
	{		//login_id情報がPOSTされたときに呼び出されるコールバック機能
		$this->load->model("user_manager");

		if ($_POST["login_id"] === "" || $_POST["password"] === "") {
			// 未入力の場合はチェックしない。
			return true;
		}

		$loginId = $this->input->post("login_id");	//POSTされたlogin_idデータとDB情報を照合する
		$password = md5($this->input->post("password"));	//POSTされたパスワードデータとDB情報を照合する

		if ($this->user_manager->chkLogIn($loginId, $password)) {	//ユーザーがログインできたあとに実行する処理
			return true;
		} else {					//ユーザーがログインできなかったときに実行する処理
			//login_id、またはパスワードが異なります。
			return false;
		}
	}

	/**************************************************
	 * private method
	 **************************************************/
	/**
	 * 初期登録
	 */
	private function isResisterInit($userId)
	{
		$this->load->model(array("action_manager"));

		$ret = false;

		// アクションデータが存在しない場合は初期登録と判断する。
		if (!$this->action_manager->chkInitData($userId)) {
			$ret = true;
		}

		return $ret;
	}
}
