<?if($_SESSION['user']['name']=='admin'):?>
<script type="text/javascript">
<!--
function delete(id){
  if(!confirm('Да?')) return false;
  var myAjax = new Ajax.Request( '/articles/delete/'+id, { method: 'get',  onComplete: function(originalRequest) {
  $('a'+id).remove();return false;}
}
function show(id){
if(!confirm('Да?')) return false;
var myAjax = new Ajax.Request( '/articles/show/'+id, { method: 'get',  onComplete: function(originalRequest) {
  $('showhide'+id).innerHTML='показывается';return false;}
}  
function hide(id){
if(!confirm('Да?')) return false;
var myAjax = new Ajax.Request( '/articles/hide/'+id, { method: 'get',  onComplete: function(originalRequest) {
  $('showhide'+id).innerHTML='скрыт';return false;}
}  
//-->
</script>
<?endif;?>
<div class="content">
	<div class="right"></div>
	<div class="left"></div>
<div class="center">
<div id="content" >

<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"><?=$page['name']?></div></div>
<div class="page">
<?=$page['text']?>
<?if(@$_SESSION['user']):?>
<a href="/article/add">add</a><br />
<a href="/article/my">my</a><br />
<?endif;?>

<table width="100%" class="block">
<?foreach ($articles as $v):?>
<tr id="a<?=$v['id']?>">

<td><?if($v['image']):?>
<a href="/article/view/<?=$v['id']?>"><img src="<?=IMAGES.'/a'.$v['image']?>" border="0"></a>
<?endif;?><a href="/article/view/<?=$v['id']?>"><?=$v['title']?></a>
<a href="/article/index/<?=$v['category_id']?>"><?=$categories[$v['category_id']]?></a>
<br><?=$v['description']?>

<?if($_SESSION['user']['id']==$v['user_id'] || $_SESSION['user']['name']=='admin'):?>
<a href="/article/edit/<?=$v['id']?>">
<img src="/img/b_edit.png" border="0" alt="edit"></a>
<a href="" onclick="delete(<?=$v['id']?>)">
<img src="/img/b_drop.png" border="0" alt="edit"></a>
<?endif;?>

<?if($_SESSION['user']['name']=='admin'):?>
<span id="showhide<?=$v['id']?>"><a href="#" onclick="<?if($v['hidden']):?>show(<?=$v['id']?>);">показать<?else:?>hide(<?=$v['id']?>);">скрыть<?endif;?></a></span>
<?endif;?>

 </td><td> </td>
</tr>
<?endforeach;?>
</table>
<br />
<?if(isset($pager)):?>
Pages:<?foreach ($pager as $k=>$v):?> <a href="/user/list/page_<?=$v?>"> <?if($page==$v):?><strong><?=$v?></strong><?else:?><?=$v?><?endif;?></a><?endforeach;?>
<?endif;?>

	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
