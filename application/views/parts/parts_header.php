<!-- ヘッダー -->
<div id="header_area" >
	<div id="title_item">
        <h1 onClick="location.href='<?php echo base_url() . "top/index" ?>';return false">
        ActionTrigger - <?php echo $title ?>
        </h1>
	</div>

	<div id="logout_item">
		<button class='btn btn-secondary' onClick="location.href='<?php echo base_url() . "main/logout" ?>';return false">
		ﾛｸﾞｱｳﾄ
		</button>
	</div>

	<div id="header_user">
		<span class="userName">
			<a href="#" style="color:var(--black);" onClick="location.href='<?= base_url();?>user_edit/index/<?= $dispId ?>';return false;" >
				<?= $userName; ?>
			</a>
		</span>
		
	</div>

</div>
