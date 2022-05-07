<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
ログイン画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/login.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('./sw.js').then(registration => {
      console.log('ServiceWorker registration successful.');
    }).catch(err => {
      console.log('ServiceWorker registration failed.');
    });
  }
</script>

</head>

<body>

	<div id="container">
		<div id="header">
			<?php $this->load->view('parts/parts_header_'); ?>
		</div>
		<div id="menu">
		</div>
		<div id="main">
			<?php
			echo form_open("Main/loginValidation");	//フォームを開く
			?>

			<div id="body_area">
				<div id="info_area">
					<div class="info_messege">
						<?php $this->load->view('parts/parts_info'); ?>
					</div>
				</div>

				<div id="body_area_main">
					<div id="edit_body">
						<h3>笑顔で繋がるアクションフレンズ</h3>
						<p>↓Facebookグループはこちらから♪</p>
						<a target="_blank" href="https://www.facebook.com/groups/323643691866455/">
							<img id="fb_title" class="banner" src="<?php echo base_url(); ?>images/fb_title.png" />
						</a>
					</div>

					<div id="edit_body">
						<h3>笑顔の達成報告</h3>
						<p>↓ニコって笑顔になったら、下をクリックして、みんなに報告してね♪</p>
						<a target="_blank" href="https://www.facebook.com/groups/323643691866455/permalink/323661308531360/">
							<img id="smile_report" class="banner" src="<?php echo base_url(); ?>images/smile_report.png" />
						</a>
					</div>
				</div> <!-- body_area_main -->

				<div id="body_area_side">
					<div class="side_user_info">
						<table>
							<tr>
								<th>ログインID</th>
							</tr>
							<tr>
								<td>
									<?php echo form_input("login_id", $this->input->post("login_id"), "class='form-control loginid'");	//Emailの入力フィールドを出力 
									?>
									<?php echo form_error('login_id'); ?>
								</td>
							</tr>
							<tr>
								<th>パスワード</th>
							</tr>
							<tr>
								<td>
									<?php echo form_password("password", "", "class='form-control'");	//パスワードの入力フィールドを出力 
									?>
									<?php echo form_error('password'); ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php
									echo form_submit("login_submit", "login", "class='btn btn-primary'");	//ログインボタンを出力	
									echo form_error('login_submit');
									?>
								</td>
							</tr>
							<tr>
								<td>
									<?php

									$data = array(
										'name'          => 'chkLoginCookie',
										'id'            => 'chkLoginCookie',
										'value'         => true,
										'checked'       => true
									);
									echo form_checkbox($data);
									echo form_label('ログイン状態を保持', 'chkLoginCookie');
									?>
								</td>
							</tr>
							<tr>
								<td>
									<a href="<?php echo base_url() . "main/signup" ?>">会員登録する</a>
								</td>
							</tr>
						</table>
					</div> <!-- side_user_info -->
				</div> <!-- body_area_side -->
			</div>
			<?php
			echo form_close();	//フォームを閉じる
			?>

		</div>

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>

	</div>

</body>

</html>