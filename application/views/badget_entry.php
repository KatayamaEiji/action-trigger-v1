<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
定期予算登録画面
------------------------------------------------------------------------->
<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include');?>
	<link rel="stylesheet" href="<?php echo base_url();?>styles/top.css?<?php echo date('YmdHHmmss');?>" type="text/css" />
</head>
<body>

<div id="container">
<div id="header">
    <?php $this->load->view('parts/parts_header');?>
</div>

<div id="main">
<?php
echo form_open("Badget_entry/badgetEntryValidation");	//フォームを開く
?>

<div class="edit">
	<?php 
	$data = array(
		'type'         => 'hidden',
		'id'          => 'spendEditBadgetDataId',
		'name'          => 'spendEditBadgetDataId',
		'value'          => ''
	);
	echo form_input($data);
	$data = array(
		'type'         => 'hidden',
		'id'          => 'spendEditSpendDataId',
		'name'          => 'spendEditSpendDataId',
		'value'          => ''
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
					'value'       => '',
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
				<?php 
				$data = array(
					'type'        => 'number',
					'id'          => 'badgetMoney',
					'name'        => 'badgetMoney',
					'value'       => '0',
					'required'    => 'required'
				);
				echo form_input($data);
				echo form_error('badgetMoney');
				?><?php echo $currencyUnit;?>
			</td>
		</tr>
		
		<tr>
			<th>予算日</th>
			<td>
				<?php echo form_radio('日') ?>
				<?php echo form_radio('月') ?>
				<?php echo form_radio('年') ?>
				<?php 
					$data = array(
						'type'        => 'number',
						'id'          => 'badgetActivationDay',
						'name'        => 'badgetActivationDay',
						'value'       => '0',
						'required'    => 'required'
					);
					echo form_input($data);
					echo form_error('badgetActivationDay');
					?>
				<?php 
					$data = array(
						'type'        => 'number',
						'id'          => 'badgetActivationMonth',
						'name'        => 'badgetActivationMonth',
						'value'       => '0',
						'required'    => 'required'
					);
					echo form_input($data);
					echo form_error('badgetActivationMonth');
					?>

			</td>
		</tr>

		<tr>
			<th>表示単位</th>
			<td>
				<?php echo form_radio('すべて') ?>
				<?php echo form_radio('日') ?>
				<?php echo form_checkbox('予算の残高を次の予算に繰り越す。') ?>
			</td>
		</tr>

		<tr>
			<th>メモ</th>
			<td>
				<?php 
				$data = array(
					'id'          => 'spendEditMemo',
					'name'          => 'spendEditMemo',
					'rows'        => '5'
				);
				echo form_textarea($data);
				echo form_error('spendEditMemo');
				?>
			</td>
		</tr>
	</table>
</div>

<div class="button_field">
        <div>
            <?php 
            echo form_submit("entry_submit", "登録","class='btn btn-primary'");
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