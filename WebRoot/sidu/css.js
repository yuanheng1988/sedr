function xwin(URL,w,h){
	var pop; if(w==null) w=600; if(h==null) h=500;
	if (pop!=null && !pop.closed) pop.close();
	var L=(screen.width-w)/2; T=(screen.height-h)/2;
	pop = window.open(URL,'sidu','scrollbars=yes,resizable=yes,left='+L+',top='+T+',width='+w+',height='+h);
	pop.focus();
}
checked = false;
function checkedAll(){
	checked=(checked==false ? true : false);
	for(i=0;i<document.getElementById('dataTab').elements.length;i++)
		document.getElementById('dataTab').elements[i].checked = checked;
}
function setv(id,v){
	document.getElementById(id).value=v;
}
function getv(id){
	return document.getElementById(id).value;
}
function showHideTree(id,showAll){
	if (showAll==1) disp = 'none';
	else if (showAll==-1) disp = '';
	else disp=document.getElementById('p'+id).style.display;
	document.getElementById('p'+id).style.display=(disp=='none' ? '' : 'none');
	var nlast=document.getElementById('t'+id).src.indexOf('last');
	document.getElementById('t'+id).src='img/tr'+(disp=='' ? '' : 'open')+(nlast==-1 ? '' : 'last')+'.gif';
}
function showHide(id,mode){
	if (mode==1) disp = 'none';
	else if (mode==-1) disp = '';
	else disp=document.getElementById(id).style.display;
	document.getElementById(id).style.display=(disp=='none' ? '' : 'none');
}
function openClose(frame){
	var open = document.getElementById(frame+'open').src.indexOf('open');
	if (frame=='menu'){
		parent.document.body.cols = (open==-1 ? '200,*' : '0,*');
		document.getElementById(frame+'open').src='img/'+frame+'-'+(open==-1 ? 'open' : 'close')+'.png';
	}
	if (frame=='tool'){
		parent.document.getElementById('sqlsmain').rows = (open==-1 ? '*,30' : '50,*');
		document.getElementById(frame+'open').src='img/'+frame+'-'+(open==-1 ? 'open' : 'close')+'.png';
	}
}
function replaceTxt(text,textarea){
	if (typeof(textarea.selectionStart) != "undefined"){
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + text + end;
		if (textarea.setSelectionRange){
			textarea.focus();
			textarea.setSelectionRange(begin.length + text.length, begin.length + text.length);
		}
		textarea.scrollTop = scrollPos;
	}else{// Just put it on the end.
		textarea.value += text;
		textarea.focus(textarea.value.length - 1);
	}
}
function Goto(win,url,xx){
	if (!url) return;
	if (!xx){
		if (!win) window.location=url;
		else if (win=='top') top.location=url;
		else if (win=='menu') parent.menu.location=url;
		else if (win=='main') parent.main.location=url;
		else if (win=='sqls') parent.sqls.location=url;
	}else{
		if (!win) window.opener.location=url;
		else if (win=='top') window.opener.top.location=url;
		else if (win=='menu') window.opener.parent.menu.location=url;
		else if (win=='main') window.opener.parent.main.location=url;
		else if (win=='sqls') window.opener.parent.sqls.location=url;
	}
}
//tab.php and sql.php
function submitForm(id1,v1)
{
	eval('document.dataTab.'+id1).value=v1;
	if (id1=='cmd' && (v1=='data_save' || v1=='data_del')) document.dataTab.target='hiddenfr';
	document.dataTab.sidu8.value=(id1=='sidu7' ? 0 : getv('sidu8'));
	document.dataTab.sidu9.value=getv('sidu9');
	document.dataTab.submit();
	document.dataTab.target='main';
	document.dataTab.cmd.value='';
}
function editBlob(id){
	showHide('blobDiv',1);
	setv('blobTxt',getv(id));
	setv('blobTxtID',id);
}
function editBlobSave(){
	var id=getv('blobTxtID');
	var v=getv('blobTxt');
	showHide('blobDiv',-1);
	if (getv(id)!=v){
		setv(id,v);
		setv('blob'+id,v.substr(0,30));
		rid=id.substr(0,id.lastIndexOf('_'));
		eval('document.dataTab.cbox_'+rid).checked='checked';
	}
}
function dbexp(id,o){
	var tabs='';
	var len = document.dataTab[o].length;
	if (len==null) return xwin('exp.php?id='+id+'&tab='+document.dataTab[o].value);
	for (i=0;i<len;i++){
		if (document.dataTab[o][i].checked) tabs = tabs +','+document.dataTab[o][i].value;
	}
	xwin('exp.php?id='+id+'&tab='+tabs.substr(1));
}
