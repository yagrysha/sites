<div class="red"><?=@$text?></div>
<div  id="addform"><a name="form"></a>
<form id="item" action="/admin/<?if(@$item['id']):?>editnews<?else:?>addnews<?endif;?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?=@$item['id']?>" id="item_id">
<table width="100%"  class="block">
<tr><td width="50">Заголовок:</td><td> <input type="text" name="add[title]" id="item_title" size="50" value="<?=@$item['title']?>"></td></tr>
<tr><td colspan="2"> Text 
<?php
include_once(ROOT_DIR.'/editor/fckeditor.php') ;
$oFCKeditor = new FCKeditor('add[text]') ;
$oFCKeditor->Height = 400;
$oFCKeditor->BasePath	= '/editor/';
$oFCKeditor->Value		=  @$item['text'];
$oFCKeditor->Create() ;
?>
</td></tr>
<tr><td></td><td><input type="submit" value="Сохранить" id="submit_btn"></td></tr>
</table>
</from><div id="notice"><?=@$notice?></div>
<div class="clr"></div> 
</div>