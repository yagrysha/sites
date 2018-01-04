<div class="content">
<div class="right"></div>
<div class="left"></div>
<div class="center">
<div id="content" >
<div class="title"><div class="h1"><img src="/img/r1.gif" align="right">view</div></div>
<div class="page">

<?=$mail['subject']?></br>
<?=date('d.m.y H:i', $mail['created_at'])?></br>
<?if($mail['from_id']!=$_SESSION['user']['id']):?> <a href="user/reply/<?=$mail['id']?>">reply</a></br><?endif;?>
<?if($mail['from']):?>from <a href="user/view/<?=$mail['from_id']?>"><?=$mail['from']?></a></br><?endif;?>
<?if($mail['to']):?>from <a href="user/view/<?=$mail['to_id']?>"><?=$mail['to']?></a></br><?endif;?>
<?=$mail['message']?></br>

</form>
	</div></div></div>
<div class="clear"><!-- --></div>