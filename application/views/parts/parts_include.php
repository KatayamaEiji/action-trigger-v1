<meta charset="utf-8">
<title>ActionTrigger - <?= $title ?></title>

<!-- PWA対応 -->
<link rel="manifest" href="<?= base_url();?>/manifest.json">

<!------------------------------------
 bootstrap 4.1.3 ,Jquery
-------------------------------------->
<link rel="stylesheet" href="<?= base_url() . $bootstrapCssUrl?>" >
<script src="<?= base_url() . $jqueryJsUrl?>"> </script>
<!-- <script src="<?= base_url() . $bootstrapPopperJsUrl?>" ></script> -->
<script src="<?= base_url() . $bootstrapJsUrl?>"></script>

<!------------------------------------
 その他 
-------------------------------------->
<?php 
/*
【fortawesome】
npmにてインストール ( コマンド：　npm install --save @fortawesome/fontawesome-free　)
https://fontawesome.com/how-to-use/on-the-web/setup/using-package-managers

参考：https://saruwakakun.com/html-css/basic/font-awesome
*/
?>
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"> -->
<link rel="stylesheet" href="<?= base_url() . $fontawesomeAllUrl ?>"> 

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" /> -->
<link rel="stylesheet" href="<?= base_url() . $fontAwesomeAnimationUrl ?>" type="text/css" media="all" />

<!------------------------------------
 vue.js 関連
-------------------------------------->
<!--<script src="https://unpkg.com/vue/dist/vue.js"></script>-->
<script src="<?= base_url() . $vuejsUrl ?>"></script>
<script src="<?= base_url() . $axiosUrl ?>"></script> <!-- Ajax用 -->

<!------------------------------------
 共通 
-------------------------------------->
<link rel="stylesheet" href="<?= base_url();?>styles/com_base.css?<?= date('YmdHHmmss');?>" type="text/css" />

<link rel="stylesheet" href="<?= base_url();?>styles/com_base_0480.css?<?= date('YmdHHmmss');?>" media="screen and (max-width:480px)" /> <!--　画面サイズが480pxまでこのファイルのスタイルが適用される。 -->
<link rel="stylesheet" href="<?= base_url();?>styles/com_base_0768.css?<?= date('YmdHHmmss');?>" media="screen and (min-width:480px) and (max-width:1024px)" /> <!-- 画面サイズ480pxから1024pxまではこのファイルのスタイルが適用される。 -->
<link rel="stylesheet" href="<?= base_url();?>styles/com_base_1024.css?<?= date('YmdHHmmss');?>" media="screen and (min-width:1024px)" /><!--画面サイズ1024px以上はこのファイルはスタイルが適用される -->
 
<script type="text/javascript" src="<?= base_url();?>script/action_trigger.js?<?= date('YmdHHmmss');?>"></script>



<link rel="icon" href="<?=base_url()?>images/favicon.ico" type="image/x-icon"> <!-- 64×64px -->
<link rel="apple-touch-icon" href="<?=base_url()?>images/apple-touch-icon-180x180.png" sizes="180x180"> <!-- 180x180px -->


