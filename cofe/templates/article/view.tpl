<?if($_SESSION['user']['name']=='admin'):?>
<script type="text/javascript">
<!--
function delcom(id){
  if(!confirm('Да?')) return false;
  var myAjax = new Ajax.Request( '/comments/delete/'+id, { method: 'get',  onComplete: function(originalRequest) {
  $('comment'+id).remove();return false;}
}
function addcom(id){
  if(!confirm('Да?')) return false;
  var myAjax = new Ajax.Request( '/comments/add/'+id, { method: 'get',  onComplete: function(originalRequest) {
 return false;}
}
//-->
</script>
<?endif;?>
<div class="content">
	<div class="right"></div>
	<div class="left"></div>
<div class="center">
<div id="content" >

<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"></div></div>
<div class="page">

<?=$article['title']?>

<?if(!@$_SESSION['user']):?>
aaa
<?else:?>
<div id="commentform">
<form>
<textarea rows="4" cols="60" id="comment_text"></textarea>
<input type="button" value="lj,fdbmn" onclick="return addcomment();">
</form>
</div>
<?endif;?>
<div id="commentslist">
<?foreach ($comments as $v):?>
<div id="comment<?=$v['id'?>">
<a href="/user/view"><?=$v['login']?></a><br/>
<?=data("d.m.y H:i", $v['created_at'])?>
<?=$v['message']?>
<?if($_SESSION['user']['name']=='admin'):?>
<a href="#" onclick="delcom(<?=$v['id']?>)">del</a>
<?endif;?>
</div>
<?endforeach;?>
</div>
	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
