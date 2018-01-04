<?
if($_action=='settings'):?>
<strong><a href="/admin/settings">настройки</a></strong>
<?else:?>
<a href="/admin/settings">настройки</a>
<?endif;?>

<?if($_action=='pages' || $_action=='pages_add'):?>
<strong><a href="/admin/pages">страницы</a></strong>
<?else:?>
<a href="/admin/pages">страницы</a>
<?endif;?>

<?if($_action=='admins'):?>
<strong><a href="/admin/admins">админы</a></strong>
<?else:?>
<a href="/admin/admins">админы</a>
<?endif;?>

<a href="/user/logout">выход</a>

<a href="/">главная</a>