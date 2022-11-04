	/*
		Owen Kalungwe
		25-07-19
	*/
	const APPLICATION_ID= "ALINAFE-ONLINE";



	function getValue(el){
		return $(el).val();
	}
	
	function putValue(el,value){
		return $(el).val(value);
	}
	
	function empty(value){
		if(value=="" || typeof(value)==null) return true;
		return false;
	}
	
	/*
		Convert json Obj to string
	*/
	function jsonString(String){
		return JSON.stringify(String);
	}
	
	/*
		Convert string to json
	*/
	function toJson(String){
		return $.parseJSON(String);
	}

	function putInSession(key,val){
		return window.sessionStorage.setItem(APPLICATION_ID+key,val);
	}

    function getInSession(key){
        let v= window.sessionStorage.getItem(APPLICATION_ID+key);
        if(typeof v!=null || v!=null) return v;
        return null;
    }

    function removeInSession(key){
        window.sessionStorage.removeItem(APPLICATION_ID+key);
        return true;
    }
	
	function flashInputError(el){
		var bgcolor = $(el).css("background");
		$(el).css("background","#999");
		setTimeout(function cc(){
			$(el).css("background",bgcolor);
		},1000);
	}
	
	/*
		@return true if is valid phone number
		@return false if is not valid phone number
	*/
	function isPhoneNumber(phone){
		var check = /^\d{10}$/;
		if(phone.length<10) return false;
		if(phone.match(check))
			return true
		return false
	}
	
	
	/*Validate Email
	*/
	function isEmail(email) {
		var format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if(email.match(format)) return true;
		return false;
	}
	/*
		Redirect
		@after int time in millseconds
	*/
	function redirect(URL,after,target){
		if(target != null || after !="undefined"){
			//return window.open(URL,target);
		}
		if(after == null || after =="undefined"){
			return window.location.href = URL;
		}
		setTimeout( function rd(){
			window.location.href = URL;
		},after);
	}

	function livePopUp(url='livechat.php'){
		return window.open(
			url,
			'_blank',
			'toolbar=no,scrollbars=yes,resizable=yes,top=50,left=200,width=650,height=600'
		);
	}
	
	function replaceAll(str,src,_with){
		var array = str.trim().split(src), newStr='';
		for (var i = 0; i < array.length; i++) {
			if(typeof(array[i]) != "undefined"){
				if(i+1 == array.length){
					newStr += array[i];
				}
				else
					newStr += array[i]+_with;
			}
		}
		if(newStr != '')return (newStr);
		return (str);
	}

	function isNumeric(number){
      var numbers = /^[0-9]+$/;
      if(number.match(numbers)){
      	return true;
      }
	  return false;
	}

	function getCharAt(string,position){
        return string.substring(0,position);
    }

    function urldecode(str) {
        return decodeURIComponent((str+'').replace(/\+/g, '%20'));
    }

    function onlyNums(string){
        string = string.replace(/\D/g,'');
        return string;
    }

    function getParam(ParamID){
        var myAddress = window.location.href, url=null,p=null;
        url = new URL(myAddress);
        p = url.searchParams.get(ParamID);
        if(p==null || p=="") return null;
        return p;
    }

    $(document).ready(function () {
		/*
			Search items
		*/
		$('.findItems').keyup(function (e){
			var keywords = $(this).val();
			if(keywords.length<3 || e.which==13) return;
			if($('.search-view').css('display')=='none'){
				$('.search-view').css('display','block');
			}
			searchItems(keywords,true);
		});
		$('.doSearchNow').click(function (e){
			var keywords = $('.s22').val();
			redirect('search.php?find='+keywords);
		});
		$('.findItems').keypress(function(e) {
			if(e.which==13){
				var keywords = $(this).val();
				redirect('search.php?q='+keywords);
			}
		});
		$('.table-responsive').css('overflow','hidden');
		$('.table-responsive > table').mouseenter(function(){
			let parent = $(this).parent('.table-responsive');
			$(parent).css('overflow','auto');
		});

		$('.table-responsive').mouseleave(function(){
			$('.table-responsive').css('overflow','hidden');
		});

		$('.myModalOpener').click(function(){
			$('.myModal').css('display','block');
		});

		$('.myClose').click(function(){
			$('.myModal').css('display','none');
		});

	});

	function searchItems(keyWord,view){
		console.log(keyWord+view);
	}
	/*
	*
	* */
	function isURL(url) {
		var urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
		return urlregex.test(url);
	}

	/*
	Object.prototype.removeItem = function (key) {
		if (!this.hasOwnProperty(key))
			return
		if (isNaN(parseInt(key)) || !(this instanceof Array))
			delete this[key]
		else
			this.splice(key, 1)
	};*/

	function move(arr, val) {
		var j = 0;
		for (var i = 0, l = arr.length; i < l; i++) {
			if (arr[i] !== val) {
				arr[j++] = arr[i];
			}
		}
		arr.length = j;
	}

	function dbPut(k,v){
		return localStorage.setItem(APPLICATION_ID+'.'+k,v);
	}

	function dbGet(k){
		var v= localStorage.getItem(APPLICATION_ID+'.'+k);
		if(v==null) return null;
		return v;
	}

	function dbRemove(k){
		return localStorage.removeItem(APPLICATION_ID+'.'+k);
	}

	function dbClear(){
		return localStorage.clear();
	}

	function strHas(str,has){
		var pattern = new RegExp(has);
		if(pattern.test(str)==true) return true;
		return false;
	}