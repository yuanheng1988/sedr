function showOptions()
{
	var scope = document.getElementById("scope");
	var scope_scale = document.getElementById("scope_scale");
	var div1 = document.getElementById("options_organization_single");
	var div2 = document.getElementById("options_project_single");
	div1.style.display = "none";
	div2.style.display = "none";
	
	var scopevalue = scope.options[scope.selectedIndex].value;
	var scope_scalevalue = scope_scale.options[scope_scale.selectedIndex].value;
	
	if(scopevalue == "Organization" && scope_scalevalue == "Single"){
		div1.style.display = "block";
	}
	
	if(scopevalue == "Project" && scope_scalevalue == "Single")
		div2.style.display = "block";	
}