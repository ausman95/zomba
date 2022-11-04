// JavaScript Document
/*
	Author OWEN KALUNGWE
	01-07-19
	Credits: Kondwani Bosco;
	Copyright Zachangu.net
	Updated on 04-08-2018 Added Bootstrap alert support - Owen Kalungwe
	Removed options and added type's of bootstrap
	Applied Mobile responsive
*/

function toast(message,position,duration,type){
	if(isNaN(duration) || duration==null || duration==false){
		duration = 2000;
	}
  	var x = document.createElement('div');
  	x.id = "toast";
  	x.style.visibility='visible';
  	x.innerHTML = message;
	if(position == null || position =="undefined") position = "right";
	position = position.toLowerCase();
	if(position === 'left'){
		x.style.top='30px';
	}
	else if(position === 'left-bottom'){
		x.style.bottom='30px';
	}
	else if(position === 'right'){
		x.style.right='30px';
		x.style.top='30px';
	}
	else if(position === 'right-bottom'){
		x.style.right='30px';
		x.style.bottom='30px';
	}
	else if(position === 'centre-top'){
		x.style.top = '30px';
		x.style.left= '50%';
		x.className = 'translate';
	}
	else if(position === 'centre-bottom'){
		x.style.bottom = '30px';
		x.style.left= '50%';
		x.className = 'translate';
	}
	else{
		x.style.top = '40%';
		x.style.left= '50%';
		x.className = 'translate';
	}
	if(type == null || type =="undefined"){

	}else{
		x.className += ' alert-'+type;
		x.style.color= 'white !important';
	}
	var BrowserWidth = window.innerWidth;
	if(BrowserWidth<768) {
		x.style.bottom = '30px';
		x.style.width='90%';
		x.style.left= '5%';
		x.style.right= '5%';
		x.height = 'auto';
		x.style.height= 'auto';
		x.style.maxHeight="80px";
		x.style.textAlign="left";
	}
	document.body.appendChild(x);
  	// Hide after xxx duration
	setTimeout(
			function(){
			x.style.visibility='hidden';
		},
	duration);
}

$(document).ready(function(e) {
	$("body").on('click', "#toast", function(e) {
	   $(this).css('visibility','hidden');
	});
});

function notice(message,type){
	if(type=="" || type =='undefined') type ='dark'
	new PNotify({
    	title: type,
        text: message,
        type: type
	});
}

function doblink(el){
	$(el).fadeTo('slow',500).fadeTo('slow',500);
}

function _alert(message,type,destination,prepend){
	if(prepend=="" || prepend =='prepend') prepend =false;
	if(type=="" || type =='undefined') type ='info';
	var html ='<div class="col-md-12"><div class="alert alert-'+type+'">'+
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">'+
	 '×</button>'+
   	 ''+message+''+
     '</div></div>';
	if(prepend==false)$(destination).empty().html(html);
	if(prepend==true)$(destination).prepend(html);
}
