function deluser(id){
	 new Ajax.Request('/admin/deluser/', {
		method: 'post',
		parameters: 'id='+id,
	onSuccess: function(transport) {
		if (transport.responseText=='ok'){
			$('user'+id).remove();
	}else{
		alert('Error');
	}
}});
	return false;
}

function show_img(img,width,height)
{
   var a
   var b
   var url
   vidWindowWidth=width;
   vidWindowHeight=height;
   a=(screen.height-vidWindowHeight)/5;
   b=(screen.width-vidWindowWidth)/2;
   features="top="+a+",left="+b+",width="+vidWindowWidth+",height="+vidWindowHeight+",toolbar=no,menubar=no,location=no,directories=no,scrollbars=no,resizable=no";
   url=img;

   window.open(url,'',features,true);
}
