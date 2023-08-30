//****************************************************************************************************
//****************************************************************************************************
// THNXS TO BURKHARD KRAUPA
//****************************************************************************************************
//****************************************************************************************************
// pixel-dusche.de  - 2007
//****************************************************************************************************
//****************************************************************************************************

function getObjWidth (o) {
	return (o)?parseInt(o.offsetWidth):0;
}

function getObjHeight (o) {
	return (o)?parseInt(o.offsetHeight):0;
}

function addEvent (obj, evType, fn) {
	var o=(obj.id)?obj.id:obj.nodeName;
	if (obj.addEventListener) {
		obj.addEventListener(evType, fn, false);return true;
	} else if (obj.attachEvent) {
		var r = obj.attachEvent("on"+evType, fn);return r;
	} else return false;
}

function getEvTarget (e) {
    return (e.target)?e.target:e.srcElement;
}

var closedMenuHeight = 18;
var openItem = null;
var currentItem = null;
var active = null;
var speed = 2;
var itemHeight = 420;
var start = 0;

function initMenu () {
	var menu=document.getElementById('menu'),menuHeight=getObjHeight(menu),arrItems=menu.getElementsByTagName('LI'),countItems=arrItems.length;
	//itemHeight=menuHeight-(countItems-1)*closedMenuHeight;
	openItem = arrItems[0];

	for (var i=0; i<countItems; i++) {
		if (parseInt(i) === start) {
			mh = document.getElementById('sw1').scrollHeight;
			arrItems[i].style.height=itemHeight+'px';
		 	document.getElementById('sw'+i).style.background='url("images/table-header.gif")'; 
		} else {
			mH=getObjHeight(arrItems[i]);
			arrItems[i].style.height=closedMenuHeight+'px';
		}
		addEvent(arrItems[i],'click',showMenuPoint);
	}
}

function showMenuPoint (e) {
	var item = getEvTarget(e);while(item.nodeName.toLowerCase()!='li')item=item.parentNode;
	if (item !== openItem && active==null) {
		currentItem = item;
		active = window.setInterval(doExpand,1);
	}
}

function doExpand () {
	currentItem.style.height = parseInt(currentItem.style.height) + parseInt(speed) + 'px';
	openItem.style.height = parseInt(openItem.style.height) - parseInt(speed) + 'px';
	
	if (parseInt(speed)>=50) {
		speed = 50;
	} else {
		speed++;
	}
	
	if (parseInt(currentItem.style.height)>=itemHeight) {
		currentItem.style.height = itemHeight+'px';
		openItem.style.height = closedMenuHeight+'px';
		cnameO=openItem.className;
		cnameC=currentItem.className;
 		document.getElementById(cnameO).style.background='url("images/table-header-cl.gif")'; 
 		document.getElementById(cnameC).style.background='url("images/table-header.gif")'; 
		openItem = currentItem;
		window.clearInterval(active);
		active = null;
		speed = 2;
	}
}

//****************************************************************************************************
