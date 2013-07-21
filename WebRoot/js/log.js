function validateLogin() {
	var name = document.getElementsByName("username")[0].value;
	var pwd = document.getElementsByName("password")[0].value;
	var reg = "{\s}*";
	if(name.test(reg) || pwd.test(reg)) return false;
	return true;
}