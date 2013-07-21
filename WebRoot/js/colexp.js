function colexpDiv(sDivName) {
	var d, s;
	d = document.getElementsByName(sDivName)[0];
	s = d.style.display;
	if(s == 'none')
		s = 'block';
	else if(s == 'block')
		s = 'none';
	d.style.display = s;
}