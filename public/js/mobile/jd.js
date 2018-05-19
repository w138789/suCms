window.onload = function ()
{
	var a = 0;
	var back = document.getElementById('back');
	$(window).scroll(function(){
		var t = $(document).scrollTop();
		if(t<160)
		{
			a -= 0.2;
			if (a<0) 
			{
				a = 0;
				back.style.opacity = a;
			}
		}
		if (t>160) 
		{
			a += 0.2;
			if (a>0.85) 
			{
				back.style.opacity = a;
				a = 0.85;
				back.style.opacity = a;
			}
		}
	});

}