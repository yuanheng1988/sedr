<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Welcome to iTechs SEDR!</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/share.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="230"></iframe>

<div class="div1">
<br/>
<strong>Share Data - <font color="#000099">Step  2  of  4</font></strong>
<hr/>
<input type="button" value="Previous" onClick="javascript:history.go(-1);"  class="input5"/>
<form action="submit" method="POST">
<table  width="600" class="shareTable">
	<tr>
		<td align='right' width='25%'>Organization Type&nbsp;&nbsp;</td>
		<td align='left' width='75%'>
			<select name='org_type' style="width:200px;">
				<option value='Aerospace / Automotive'>Aerospace / Automotive</option>
				<option value='Billing'>Billing</option>
				<option value='Business Services'>Business Services</option>
				<option value='Chemicals'>Chemicals</option>
				<option value='Community'>Community</option>
				<option value='Services'>Services</option>
				<option value='Computers & Software'>Computers & Software</option>
				<option value='Construction'>Construction</option>
				<option value='Consumer Goods'>Consumer Goods</option>
				<option value='Credit Card Processor'>Credit Card Processor</option>
				<option value='Defence'>Defence</option>
				<option value='E-Business'>E-Business</option>
				<option value='Energy(Electricity, Gas, Water)'>Energy(Electricity, Gas, Water)</option>
				<option value='Electronics'>Electronics</option>
				<option value='Engineering'>Engineering</option>
				<option value='Research & Development'>Research & Development</option>
				<option value='Software Development'>Software Development</option>
				<option value='Client/Server architecture for Language Services'>Client/Server architecture for Language Services</option>
				<option value='Financial, Property & Business Services'>Financial, Property & Business Services</option>
				<option value='Food Processing'>Food Processing</option>
				<option value='Government'>Government</option>
				<option value='Community Services'>Community Services</option>
				<option value='Insurance'>Insurance</option>
				<option value='Transport & Storage'>Transport & Storage</option>
				<option value='Manufacturing'>Manufacturing</option>
				<option value='Wholesale & Retail Trade'>Wholesale & Retail Trade</option>
				<option value='Marketing'>Marketing</option><option value='Media'>Media</option>
				<option value='Medical and Health Care'>Medical and Health Care</option>
				<option value='Public Administration'>Public Administration</option>
				<option value='Oil Ordering'>Oil Ordering</option>
				<option value='Computers & Software'>Computers & Software</option>
				<option value='Insurance'>Insurance</option>
				<option value='Computers & Software'>Computers & Software</option>
				<option value='Insurance'>Insurance</option>
				<option value='Real Estate & Property Services'>Real Estate & Property Services</option>
				<option value='Recreation & Personnel Services'>Recreation & Personnel Services</option>
				<option value='Sales & Marketing'>Sales & Marketing</option>
				<option value='Service'>Service</option>
				<option value='Professional Services'>Professional Services</option>
				<option value='Voice Provisioning'>Voice Provisioning</option>
				<option value='Telecommunication'>Telecommunication</option>
				<option value='Family (Community Service)'>Family (Community Service)</option>
				<option value='Construction'>Construction</option>
				<option value='Communications(Aerospace, Railway, Automotive)'>Communications(Aerospace, Railway, Automotive)</option>
				<option value='Transportation'>Transportation</option>
				<option value='Education'>Education</option>
				<option value='Finance(Banking, Insurance, Security)'>Finance(Banking, Insurance, Security)</option>
				<option value='Hotel'>Hotel</option>
				<option value='Academy'>Academy</option>
				<option value='Energy(Oil, electricity, Gas, Water supply)'>Energy(Oil, electricity, Gas, Water supply)</option>
				<option value='Mineral'>Mineral</option>
				<option value='Manufacturing (Petroleum, Chemical, Steel))'>Manufacturing (Petroleum, Chemical, Steel))</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align='right' width='25%'>Business Area Type&nbsp;&nbsp;</td>
		<td align='left' width='75%'>
			<select name='ba_type'  style="width:200px;">
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
				<option value='Publich Health & Family Services'>Publich Health & Family Services</option>
				<option value='Registration'>Registration</option>
				<option value='Racing'>Racing</option>
				<option value='Research & Development'>Research & Development</option>
				<option value='Sales'>Sales</option><option value='Social Services'>Social Services</option>
				<option value='Software house / Services'>Software house / Services</option>
				<option value='Telecommunications'>Telecommunications</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align='right' width='25%'>Application Type&nbsp;&nbsp;</td>
		<td align='left' width='75%'>
			<input type='checkbox' name='app_type' value='Business, Customer billing/Relationship management'/>
			<span class="option">Business, Customer billing/Relationship management</span><br/>
			<input type='checkbox' name='app_type' value='Electronic Data Interchange'/>
			<span class="option">Electronic Data Interchange</span><br/>
			<input type='checkbox' name='app_type' value='Decision Support System'/>
			<span class="option">Decision Support System</span><br/>
			<input type='checkbox' name='app_type' value='E-Business'/>
			<span class="option">E-Business</span><br/>
			<input type='checkbox' name='app_type' value='Electronic Banking'/>
			<span class="option">Electronic Banking</span><br/>
			<input type='checkbox' name='app_type' value='Executive Information System'/>
			<span class="option">Executive Information System</span><br/>
			<input type='checkbox' name='app_type' value='Fault Tolerance'/>Fault Tolerance<br/>
			<input type='checkbox' name='app_type' value='Financial transaction process/accounting'/>
			<span class="option">Financial transaction process/accounting</span><br/>
			<input type='checkbox' name='app_type' value='Geographic or spatial information system'/>
			<span class="option">Geographic or spatial information system</span><br/>
			<input type='checkbox' name='app_type' value='GUI Interface Application'/>
			<span class="option">GUI Interface Application</span><br/>
			<input type='checkbox' name='app_type' value='Knowledge Based Multimedia'/>
			<span class="option">Knowledge Based Multimedia</span><br/>
			<input type='checkbox' name='app_type' value='Management Information System'/>
			<span class="option">Management Information System</span><br/>
			<input type='checkbox' name='app_type' value='Middleware'/>
			<span class="option">Middleware</span><br/>
			<input type='checkbox' name='app_type' value='Msg.Switch/cel phone'/>
			<span class="option">Msg.Switch/cel phone</span><br/>
			<input type='checkbox' name='app_type' value='Network Management'/>
			<span class="option">Network Management</span><br/>
			<input type='checkbox' name='app_type' value='Office Information System'/>
			<span class="option">Office Information System</span><br/>
			<input type='checkbox' name='app_type' value='Operating system or software utility'/>
			<span class="option">Operating system or software utility</span><br/>
			<input type='checkbox' name='app_type' value='(Re-usable component)'/>
			<span class="option">(Re-usable component)</span><br/>
			<input type='checkbox' name='app_type' value='Real-time System'/>
			<span class="option">Real-time System</span><br/>
			<input type='checkbox' name='app_type' value='Sensor Ctl. + presentation'/>
			<span class="option">Sensor Ctl. + presentation</span><br/>
			<input type='checkbox' name='app_type' value='Software development tool'/>
			<span class="option">Software development tool</span><br/>
			<input type='checkbox' name='app_type' value='Transaction/Production System'/>
			<span class="option">Transaction/Production System</span><br/>
			<input type='checkbox' name='app_type' value='Vehicle Systems Software'/>
			<span class="option">Vehicle Systems Software</span><br/>
			<input type='checkbox' name='app_type' value='EDA system'/>
			<span class="option">EDA system</span><br/>
			<input type='checkbox' name='app_type' value='HR management system'/>
			<span class="option">HR management system</span><br/>
			<input type='checkbox' name='app_type' value='process control'/>
			<span class="option">process control</span><br/>
			<input type='checkbox' name='app_type' value='Document management system'/>
			<span class="option">Document management system</span><br/>
			<input type='checkbox' name='app_type' value='Health software'/>
			<span class="option">Health software</span><br/>
			<input type='checkbox' name='app_type' value='OLAP'/>
			<span class="option">OLAP</span><br/>
			<input type='checkbox' name='app_type' value='Work Flow Support and Management'/>
			<span class="option">Work Flow Support and Management</span><br/>
		</td>
	</tr>
    <tr>
<td></td>
<td><div style="float:right"><input type="submit" value="Next"  class="input5"/></div></td>
</tr>
</table>

&nbsp;&nbsp;&nbsp;

</form>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="850" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>