<script>
function delete(id){
  var myAjax = new Ajax.Request( '/mail/delete/'+id, { method: 'get',  onComplete: function(originalRequest) {
  $('m'+id).remove();return false; }
}
</script>
<div class="content">
<div class="right"></div>
<div class="left"></div>
<div class="center">
<div id="content" >
<div class="title"><div class="h1"><img src="/img/r1.gif" align="right">trash</div></div>
<div class="page">
<?foreach ($mail as $k=>$v):?>
<div id="m<?=$v['id']?>">
<a href="/mail/view/<?=$v['id']?>"><?=$v['subject']?></a>
 <?=date('d.m.y H:i', $v['created_at'])?> <a href="/user/view/<?=$v['from_id']?>">
<?=$v['from']?></a> <a href="/user/view/<?=$v['to_id']?>">
<?=$v['to']?></a><a href="#" onclick="delete(<?=$v['id']?>)">del</a> 
</div>
<?endforeach;?>
<br />
<?if(isset($pager)):?>
Pages:<?foreach ($pager as $k=>$v):?> <a href="/mail/trash/page_<?=$v?>"> <?if($page==$v):?><strong><?=$v?></strong><?else:?><?=$v?><?endif;?></a><?endforeach;?>
<?endif;?>

</form>
	</div></div></div>
<div class="clear"><!-- --></div>