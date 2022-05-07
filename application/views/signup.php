<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
仮会員登録画面
------------------------------------------------------------------------->

<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include'); ?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>styles/signup.css?<?php echo date('YmdHHmmss'); ?>" type="text/css" />

	<script>
		/**
		 * アクションをキャンセルする
		 */
		function cancelTempUserEntry() {
			location.href = "<?php echo base_url() . "main/login" ?>";
		}
	</script>
</head>

<body>

	<div id="container">
		<div id="header">
			<?php $this->load->view('parts/parts_header_'); ?>
		</div>

		<div id="menu">
		</div> <!-- #menu -->

		<div id="main">
			<?php

			$attributes = array('id' => 'mainForm', 'autocomplete' => 'off');
			echo form_open("Main/signupValidation", $attributes);	//フォームを開く

			?>
			<div id="main_container">
				<div id="action_title_container_item">
					<div class="action_title">
						<h1>
							会員登録
						</h1>
						<a class="cancel_action" href="#" onClick="cancelTempUserEntry();return false;">
							<img class="btn_cancel" src="<?php echo base_url(); ?>images/ic_action_cancel.png" />
						</a>
					</div>
				</div>
				<div id="edit_container_item">
					<div class="input_item">
						<span>Email </span>
						<?php
						$data = array(
							'type'        => 'text',
							'id'          => 't_mail_address',
							'name'        => 't_mail_address',
							'class'       => 'form-control',
							'v-model'     	=> 't_mail_address'
						);
						echo form_input($data);
						echo form_error('t_mail_address');
						?>
					</div> <!-- input_item -->

					<div class="input_item">
						<span>パスワード </span>
						<?php
						$data = array(
							'type'        => 'password',
							'id'          => 't_password',
							'name'        => 't_password',
							'class'       => 'form-control',
							'v-model'     	=> 't_password'
						);
						echo form_input($data);
						echo form_error('t_password');
						?>
					</div> <!-- input_item -->

					<div class="input_item">
						<span>パスワード(確認)</span>
						<?php
						$data = array(
							'type'        => 'password',
							'id'          => 't_cpassword',
							'name'        => 't_cpassword',
							'class'       => 'form-control'
						);
						echo form_input($data);
						echo form_error('t_cpassword');
						?>
					</div> <!-- input_item -->
				</div>

				<div class="button_field_container_item">
					<div class="button_field ">
						<button type="submit" name="signup_submit" id="signup_submit" class="btn btn-primary btn_appeal">
							<i class='fas fa-play'></i>&nbsp;
							会員登録する
						</button>
						<?php
						echo form_error('signup_submit');
						?>
					</div>
				</div> <!-- button_field_container_item -->

			</div>
			<?php
			echo form_close();	//フォームを閉じる
			?>
		</div> <!-- main -->

		<div id="fotter">
			<?php $this->load->view('parts/parts_fotter'); ?>
		</div>
	</div>

	<script>
		var main_container = new Vue({
			el: "#main_container",
			data: {
				t_mail_address: '<?= $t_mail_address; ?>',
				t_password: '<?= $t_password; ?>'
			}
		});
	</script>

</body>

</html>