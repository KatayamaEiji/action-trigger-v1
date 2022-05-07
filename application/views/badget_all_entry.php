<!DOCTYPE html>
<html lang="ja">
<!-------------------------------------------------------------------------
予算一括登録画面
------------------------------------------------------------------------->
<head>
	<!-- 共通Link,Scriptの読み込み -->
	<?php $this->load->view('parts/parts_include');?>
	<link rel="stylesheet" href="<?php echo base_url();?>styles/top.css?<?php echo date('YmdHHmmss');?>" type="text/css" />

	<script>

	</script>
</head>
<body>

<div id="container">
<div id="header">
	<?php $this->load->view('parts/parts_header');?>
</div>

<div id="main">

<?php
echo form_open("Badget_all_entry/badgetAllValidation");	//フォームを開く
?>
<?php echo form_hidden('recordCount', $recordCount);?>

    <h2>検索結果</h2>
    <div id="edit_body">
    <div id="input_table">
        <table>
        <thead>
            <tr>
                <th class="appKbnName">予約分類名</th>
                <th class="badget">予算額</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rowno=1;
            ?>
            <?php foreach ($findRecords as $row): ?>
            <tr>
                <td><?php 
                    echo form_hidden('appKbnId_'.$rowno, $row['app_kbn_id']); 
                    echo form_hidden('appKbnName_'.$rowno, $row['app_kbn_name']);
                    echo $row['app_kbn_name']?></td>
                <td><?php 
                    $data = array(
                        'type'         => 'number',
                        'name'          => 'badget_'.$rowno,
                        'value'         => $row['badget'],
                    );
                    echo form_input($data);
                ?>
                </td>
                <td>
                <?php
                    echo $currencyUnit;
                ?></td>
            </tr>
            <?php
                $bagetError = form_error('badget_'.$rowno);
                if($bagetError):
            ?>
            <tr>
                <td colspan=3><?php echo $bagetError ?></td>
            </tr>
                <?php endif;?>
            <?php 
                    $rowno++;
                endforeach;
            ?>
        </tbody>
        </table>
    </div> <!-- input_table -->
    </div> <!-- edit_body -->

    <div class="button_field">
        <div>
            <?php 
            echo form_submit("entry_submit", "一括登録","class='btn btn-primary'");
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
</div> <!-- fotter -->

</div> <!-- container -->

</body>
</html>