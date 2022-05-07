<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
定期予算編集画面
------------------------------------------------------------------------->
<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include');?>
	<link rel="stylesheet" href="<?php echo base_url();?>styles/top.css?<?php echo date('YmdHHmmss');?>" type="text/css" />
</head>
<body>

<div id="container" class="container-fluid">
<div id="header">
    <?php $this->load->view('parts/parts_header');?>
</div>

<div id="main">
<?php
echo form_open("Badget_edit/badgetEditValidation");	//フォームを開く
?>

	<div class="edit">
		<?php 
		$data = array(
			'type'         => 'hidden',
			'id'          => 'badgetId',
			'name'          => 'badgetId',
			'value'          => $badgetId
		);
		echo form_input($data);
		?>

		<table class="input_table">
			<tr>
				<th>タイトル</th>
				<td>
					<?php 
					$data = array(
						'type'        => 'text',
						'id'          => 'badgetTitle',
						'name'        => 'badgetTitle',
						'class'       => 'form-control',
						'value'       => $badgetTitle,
						'required'    => 'required'
					);
					echo form_input($data);
					echo form_error('badgetTitle');
					?>
				</td>
			</tr>

			<tr>
				<th>予算額</th>
				<td>
					<div class="input-group mb-3">
						<?php 
						$data = array(
							'type'        => 'number',
							'id'          => 'badgetMoney',
							'name'        => 'badgetMoney',
							'class'       => 'form-control input_money',
							'placeholder' => "Recipient's username",
							'aria-label'  => "Recipient's username",
							'aria-describedby' => "badgetMoney-label",
							'value'       => $badgetMoney,
							'required'    => 'required'
						);
						echo form_input($data);
						?>
						<div class="input-group-append">
							<span class="input-group-text" id="badgetMoney-label">
								<?php echo $currencyUnit;?>
							</span>
						</div>
					</div>
					<?php echo form_error('badgetMoney'); ?>
				</td>
			</tr>
			
			<tr>
				<th>予算日</th>
				<td>

					<?php 
					$data = array (
						'id' => 'periodUnitDay',
						'name' => 'periodUnit',
						'value' => 1,
						'checked' => $periodUnit == 1,
					);
					echo form_radio($data);
					?>
					<label for='periodUnitDay'>日</label>

					<?php 
					$data = array (
						'id' => 'periodUnitMonth',
						'name' => 'periodUnit',
						'value' => 2,
						'checked' => $periodUnit == 2,
					);
					echo form_radio($data);
					?>
					<label for='periodUnit'>月</label>

					<?php 
					$data = array (
						'id' => 'periodUnitYear',
						'name' => 'periodUnit',
						'value' => 3,
						'checked' => $periodUnit == 3,
					);
					echo form_radio($data);
					?>
					<label for='periodUnitYear'>年</label><br />

					<div class="input-group mb-3">
						<?php 
							$data = array(
								'type'        => 'number',
								'id'          => 'badgetActivationMonth',
								'name'        => 'badgetActivationMonth',
								'value'       => $badgetActivationMonth,
								'class'       => 'form-control input_month',
								'placeholder' => "Recipient's username",
								'aria-label'  => "Recipient's username",
								'aria-describedby' => "badgetActivationMonth-label",
								'required'    => 'required'
							);
							echo form_input($data);
						?>
						<div class="input-group-append">
							<span class="input-group-text" id="badgetActivationMonth-label">
							月
							</span>
						</div>
					</div>
					<?php echo form_error('badgetActivationMonth'); ?>

					<div class="input-group mb-3">
						<?php 
						$data = array(
							'type'        => 'number',
							'id'          => 'badgetActivationDay',
							'name'        => 'badgetActivationDay',
							'value'       => $badgetActivationDay,
							'class'       => 'form-control input_day',
							'placeholder' => "Recipient's username",
							'aria-label'  => "Recipient's username",
							'aria-describedby' => "badgetActivationDay-label",
							'required'    => 'required'
						);
						echo form_input($data);
						?>
						<div class="input-group-append">
							<span class="input-group-text" id="badgetActivationDay-label">
							日
							</span>
						</div>
					</div>
					<?php echo form_error('badgetActivationDay'); ?>

				</td>
			</tr>

			<tr>
				<th>表示単位</th>
				<td>
					<?php 
						$data = array (
							'id' => 'badgetDispUnitAll',
							'name' => 'badgetDispUnit',
							'value' => 0,
							'checked' => $badgetDispUnit == 0,
						);
						echo form_radio($data);
						?>
					<label for='badgetDispUnitAll'>すべて</label>

					<?php 
						$data = array (
							'id' => 'badgetDispUnitDay',
							'name' => 'badgetDispUnit',
							'value' => 0,
							'checked' => $badgetDispUnit == 0,
						);
						echo form_radio($data);
						?>
					<label for='badgetDispUnitDay'>日</label>
					<br />

					<?php 
						$data = array (
							'id' => 'carryForwardKbn',
							'name' => 'carryForwardKbn',
							'checked' => $carryForwardKbn == 1,
						);
						echo form_checkbox($data);
						?>
					<label for='carryForwardKbn'>予算の残高を次の予算に繰り越す。</label>
				</td>
			</tr>

			<tr>
				<th>メモ</th>
				<td>
					<?php 
					$data = array(
						'id'          => 'badgetEditMemo',
						'name'        => 'badgetEditMemo',
						'type'        => 'text',
						'class'       => 'form-control',
						'value'       => $memo,
						'rows'        => '5'
					);
					echo form_textarea($data);
					echo form_error('badgetEditMemo');
					?>
				</td>
			</tr>
		</table>
	</div> <!-- edit -->

	<div class="button_field">
        <div>
            <?php 
            echo form_submit("entry_submit", "更新","class='btn btn-primary'");
            echo form_error('entry_submit');
            ?>
        </div>
    </div> <!-- button_field -->
<?php
	echo form_close();	//フォームを閉じる
?>
</div> <!-- main -->

<div id="fotter">
	<?php $this->load->view('parts/parts_fotter');?>
</div>
</div>

</body>
</html>