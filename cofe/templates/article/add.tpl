<div class="content">
	<div class="right"></div>
	<div class="left"></div>
<div class="center">
<div id="content" >

<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"></div></div>
<div class="page">

<?if($error):?><?=$error?><?endif;?>
<?if($ok):?>
<?else:?>
<form action="" method=post>

<select name="category_id">
<?foreach ($categories as $id=>$name):?>
<option value="<?=$id?>"><?=$name?></option>
<?endforeach;?>
</select>
<input type="hidden" name="send" value="1">
<input type="text" name="to" value="<?=@$_POST['to']?>">
<input type="text" name="subject" value="<?=@$_POST['subject']?>">
<textarea rows="4" cols="60"><?=$_POST['message']?></textarea>
<?php
include_once(PREFIX.'/editor/fckeditor.php') ;
$oFCKeditor = new FCKeditor('text') ;
$oFCKeditor->Height = 400;
$oFCKeditor->BasePath   = '/editor/';
$oFCKeditor->Value      =  @$_POST['text'];
$oFCKeditor->Create() ;
?>
<input type="submit" value="send">
</form>
<?endif;?>
</div>
	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
