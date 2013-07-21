<%@ page language="java" import="java.util.*" pageEncoding="UTF-8"%>
<%@ page import="iscas.nfs.itechs.ese.beans.User" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Insert title here</title>
<script type="text/javascript" src="js/customize.js"></script>
<link href="css/default.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/util.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>
<div class="div1">
<%
	User user = (User)session.getAttribute("user");
	if(user == null) {
	%>
		You are not eligible to customize data. 
		Please <a href="register.jsp">Register</a> or Login.
	<%
	} else {
		session.setAttribute("user", user);
 %>
<br/>
<br/>
<h3>Custom Options</h3>
<form name="customizeForm" method="post" action="customize">
    <table border="0" width="94%">
        <tr>
    		<td width="15%" align="right">Research Area:</td>
    		<td align="left">
    		<input type="checkbox" name="researchArea" value="Defect Prediction"/>Defect Prediction</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="Effort Prediction"/>Effort Prediction</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="General"/>General</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="Process Study"/>Process Study</td>
            <td align="left">
    		<input type="checkbox" name="researchArea" value="Requirement Study"/>Requirement Study
           	</td>
        </tr>
    	<tr>
		    <td width="15%" align="right">Scope:</td>
		    <td width="15%" align="left">
			<select size="1" id="scope" name="scope" onChange="showOptions()">
			    <option value="" selected="selected">Unknown</option>
				<option value="Organization">Organization</option>
				<option value="Project">Project</option>
			</select>
		    </td>
		    <td width="15%" align="left">
			<select size="1" id="scope_scale" name="scope_scale" onChange="showOptions()">
			    <option value="" selected="selected">Unknown</option>
				<option value="Multiple">Multiple</option>
				<option value="Single">Single</option>
			</select>
		    </td>
	    </tr>
    </table>

<br/>
    <div id="options_organization_single" style="display:none">
    Organization Single:
    <table border="0" width="94%">
    	<tr>
		<td align='right' width='15%'>Business Area:</td>
		<td align='left'>
			<select name='custom_ba'  style="width:200px;">
                <option value='' selected="selected">--None--</option>
                <option value='all'>-All-</option>
				<option value='Family (Community Service)'>Family (Community Service)</option>
				<option value='Construction'>Construction</option>
				<option value='Communication(Aerospace, Railway, Automotive)'>Communication(Aerospace, Railway, Automotive)</option>
				<option value='Transportation'>Transportation</option>
				<option value='Education'>Education</option>
				<option value='Finance, Insurance, Security'>Finance, Insurance, Security</option>
				<option value='Government'>Government</option>
				<option value='Public Administration(Taxing)'>Public Administration(Taxing)</option>
				<option value='Wholesale & Retail'>Wholesale & Retail</option>
				<option value='Storage'>Storage</option>
				<option value='Media'>Media</option>
				<option value='Energy (Oil, Electricity, Gas, Water supply, Mineral)'>Energy (Oil, Electricity, Gas, Water supply, Mineral)</option>
				<option value='Genral'>General</option>
				<option value='Health'>Health</option>
				<option value='Manufacturing'>Manufacturing</option>
				<option value='Accounting'>Accounting</option>
				<option value='Activity Tracking'>Activity Tracking</option>
				<option value='Actuarial System - calculate employer rates'>Actuarial System - calculate employer rates</option>
				<option value='Banking'>Banking</option>
				<option value='Customer Configuration Management'>Customer Configuration Management</option>
				<option value='Customs'>Customs</option>
				<option value='Defence'>Defence</option>
				<option value='Distribution & Transport'>Distribution & Transport</option>
				<option value='Engineering'>Engineering</option>
				<option value='EPOS'>EPOS</option>
				<option value='Financial (excluding Banking)'>Financial (excluding Banking)</option>
				<option value='General'>General</option>
				<option value='Inventory'>Inventory</option>
				<option value='Legal'>Legal</option>
				<option value='Loans'>Loans</option>
				<option value='Logistics'>Logistics</option>
				<option value='Mail Service'>Mail Service</option>
				<option value='Manufacturing'>Manufacturing</option>
				<option value='Marketing'>Marketing</option>
				<option value='Ocean Transportation'>Ocean Transportation</option>
				<option value='Pension Funds Management'>Pension Funds Management</option>
				<option value='Personnel'>Personnel</option>
				<option value='Computer services and IT consultation'>Computer services and IT consultation</option>
				<option value='Publich Health & Family Services'>Public Health & Family Services</option>
				<option value='Registration'>Registration</option>
				<option value='Racing'>Racing</option>
				<option value='Research & Development'>Research & Development</option>
				<option value='Sales'>Sales</option><option value='Social Services'>Social Services</option>
				<option value='Software house / Services'>Software house / Services</option>
				<option value='Telecommunications'>Telecommunications</option>
			</select>
		</td>
	</tr> 
	</table>
    </div>
    <div id="options_project_single" style="display:none">
    Project Single:
    <table border="0" width="94%">
    	<tr>
			<td align='right' width='15%'>Life Cycle Phase:</td>
			<td align='left'>
			<select size='1' name='custom_lcp' style="width:200px;">
			    <option value='' selected="selected">--None--</option>
			    <option value='all'>-ALL-</option>
				<option value='requirement'>requirement</option>
				<option value='design'>design</option>
				<option value='coding'>coding</option>
				<option value='testing'>testing</option>
				<option value='maintenance'>maintenance</option>
			</select>
			</td>
		</tr>
    </table>	
    </div>
    <br>
    
    <h4>Your email:</h4><input type="text" name="email" value="<%=user.getEmail() %>"/>
    <br>
    <input type="submit" name="customizeButton" value="Submit"/>
</form>
<%
	}
 %>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>