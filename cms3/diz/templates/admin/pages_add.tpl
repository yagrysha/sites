
<?if (isset($trail)):?>
<?foreach ($trail as $k=>$v):?>
<?if($v['id']!=$_REQUEST['pid']):?>
<a href="/admin/pages/?pid=<?=$v['id']?>"><?=$v['name']?></a> /
<?else:?><?=$v['name']?><?endif;?>
<?endforeach;?><br />
<?endif;?>

<div class="red"><?=@$text?></div>
<div  id="addform"><a name="form"></a>
<form id="item" action="/admin/<?if(@$page['id']):?>editpage<?else:?>addpage<?endif;?>" method="post" enctype="multipart/form-data"><!-- onsubmit="item();return false;" -->
<input type="hidden" name="pid" value="<?=$_REQUEST['pid']?>">
<input type="hidden" name="id" value="<?=@$page['id']?>" id="item_id">
<table width="100%"  class="block">
<tr><td width="50">Название:</td><td> <input type="text" name="add[name]" id="item_name" size="50" value="<?=@$page['name']?>"></td></tr>
<tr><td>alias:</td><td> <input type="text" name="add[alias]" id="item_alias" size="50" value="<?=@$page['alias']?>"></td></tr>
<tr><td>Заголовок:</td><td> <input type="text" name="add[title]" id="item_title" size="50" value="<?=@$page['title']?>"></td></tr>
<tr><td>keywords:</td><td><input type="text" name="add[keywords]" id="item_keywords"  size="50" value="<?=@$page['keywords']?>"></td></tr>
<tr><td>description:</td><td><input type="text" name="add[description]" id="item_description"  size="50" value="<?=@$page['description']?>"></td></tr>
<tr><td>Скрытая:</td><td><input type="checkbox" name="add[hidden]" id="item_hidden" value="1" <?if(@$page['hidden']):?> checked<?endif;?>></td></tr>
<?/*<tr><td>image:</td><td><input type="file" name="image"  id="item_img">
<?if (@$page['image']):?>
<img  id="item_file" src="/images/<?=$page['image']?>" width="120">Delete image:<input type="checkbox" name="delimg" value="1">
<?endif;?>
</td></tr>*/?>
<tr><td colspan="2"> Text 
<?php
include_once(ROOT_DIR.'/editor/fckeditor.php') ;
$oFCKeditor = new FCKeditor('add[text]') ;
$oFCKeditor->Height = 400;
$oFCKeditor->BasePath	= '/editor/';
$oFCKeditor->Value		=  @$page['text'];
$oFCKeditor->Create() ;
?>
</td></tr>

<tr><td></td><td><input type="submit" value="Сохранить" id="submit_btn"></td></tr>
</table>
</from><div id="notice"><?=@$notice?></div>
<div class="clr"></div> 
</div>