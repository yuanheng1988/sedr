<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.utils.Utilities" %>
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

<div class="div4">
<br/>
<strong>Share Data - <font color="#000099">Step  2  of  4</font></strong>
<hr/>
<form action="submit" method="POST" onSubmit="return validateSingleProject()"> 
<input type="button" value="Previous" onClick="javascript:history.go(-1);"  class="input5"/> 
<table width="700" class="shareTable">
		<tr>
			<td align='right' width='19%'>Life Cycle Phase&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<select size='1' name='life_cycle'>
				<option value='requirement'>requirement</option>
				<option value='design'>design</option>
				<option value='coding'>coding</option>
				<option value='testing'>testing</option>
				<option value='maintenance'>maintenance</option>
			</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Architecture&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<select size='1' name='architecture'>
				<option value='client server'>client server</option>
				<option value='multi-tier'>multi-tier</option>
				<option value='Multi-tier with web public interface'>Multi-tier with web public interface</option>
				<option value='Stand alone'>Stand alone</option>
				<option value='browse server'>browse server
				</option><option value='client|browser server'>client|browser server</option>
			</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Sizing Technique&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<select size='1' name='sizingTech'>
				<option value='IFPUG(International Function Point Users Group)'>IFPUG(International Function Point Users Group)</option>
				<option value='COSMIC-FPP(COSMIC Full Function Points)'>COSMIC-FPP(COSMIC Full Function Points)</option>
				<option value='LOC(Lines of Code)'>LOC(Lines of Code)</option>
				<option value='Mark II (Symons Mark II Function Point)'>Mark II (Symons Mark II Function Point)</option>
				<option value='NESMA (Netherlands Software Metrics Association)'>NESMA (Netherlands Software Metrics Association)</option>
			</select>
			</td>
		</tr>
		<tr>
			<td align="right" width="19%">Design Technique&nbsp;&nbsp;</td>
			<td align="left" width="81%">
			<select size='1' name="designTech">
				<option value='Design Review/Inspection'>Design Review/Inspection</option>
				<option value='Rational Rose'>Rational Rose</option>
				<option value='Usability Review/Testing'>Usability Review/Testing</option>
				<option value='Structured Design'>Structured Design</option>
				<option value='UML (Unified Modeling Language)'>UML (Unified Modeling Language)</option>
				<option value='Usability Review/Testing'>Usability Review/Testing</option>
				<option value='High Level Design/Low Level Design'>High Level Design/Low Level Design</option>
				<option value='EA'>EA</option>
				<option value='StarUML'>StarUML</option>
				<option value='BugZero'>BugZero</option>
				<option value='Microsoft Office'>Microsoft Office</option>
				<option value='CaliberRM'>CaliberRM</option>
				<option value='ClearQuest'>ClearQuest</option>
				<option value='Visual Source Safe'>Visual Source Safe</option>
				<option value='Rapid SQL'>Rapid SQL</option>
				<option value='Jude UML Modeling Tool'>Jude UML Modeling Tool</option>
				<option value='Power Designer'>Power Designer</option>
				<option value='RTM(Requirement Traceability Matrix)'>RTM(Requirement Traceability Matrix)</option>
				<option value='Template'>Template</option>
				<option value='SQMS (Software Quality Manage System)'>SQMS (Software Quality Manage System)</option>
				<option value='None'>None</option>
				<option value='Mockup'>Mockup</option>
				<option value='Crystal reports'>Crystal reports</option>
			</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Total Effort&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<input type='text' name='totalEffort' />&nbsp;&nbsp;Person-Hours</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Total Time&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<input type='text' name='totalTime' />&nbsp;&nbsp;Days</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Total Member&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<input type='text' name='totalMember'>&nbsp;&nbsp;Persons</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Total Size&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<input type='text' name='totalSize' />
			<select name='sizeMetric' size='1' style="width:90px;">
				<option value='LOC'>LOC</option>
				<option value='FP'>FP</option>
			</select>
			</td>
		</tr>
		<tr>
			<td align='right' width='19%'>Total Defect&nbsp;&nbsp;</td>
			<td align='left' width='81%'>
			<input type='text' name='totalDefect' /></td>
		</tr>
		<tr><td align='right' width='19%'>Primary&nbsp;&nbsp; Programming&nbsp;&nbsp; Language&nbsp;&nbsp;</td><td align='left' width='81%'><select size='1' name='language'><option value='2GL'>2GL</option><option value='3GL'>3GL</option><option value='4GL'>4GL</option><option value='APG'>APG</option></select></td></tr><tr>
		  <td align='right' width='19%'>Operating System&nbsp;&nbsp;</td><td align='left' width='81%'><select size='1' name='os'><option value='Widows'>Widows</option><option value='Unix'>Unix</option><option value='Linux'>Linux</option><option value='Mac OS'>Mac OS</option></select></td></tr><tr><td align='right' width='19%'>Database System&nbsp;&nbsp;</td><td align='left' width='81%'><select size='1' name='dbsystem'><option value='Access'>Access</option><option value='MySQL'>MySQL</option><option value='Informix'>Informix</option><option value='MS SQL'>MS SQL</option><option value='Oracle'>Oracle</option><option value='Sybase'>Sybase</option><option value='Toad'>Toad</option><option value='Lotus Domino'>Lotus Domino</option><option value='Erstudio'>Erstudio</option><option value='DB 2'>DB 2</option><option value='HL-SQL'>HL-SQL</option><option value='NONE'>NONE</option></select></td></tr><tr><td align='right' width='19%'>Development&nbsp;&nbsp; Techniques&nbsp;&nbsp;</td><td align='left' width='81%'><select size='1' name='devTechs'><option value='Business Area Modeling'>Business Area Modeling</option><option value='Data Modeling'>Data Modeling</option><option value='Event Modeling'>Event Modeling</option><option value='Process Modeling'>Process Modeling</option><option value='Joint Application Development'>Joint Application Development</option><option value='Prototyping'>Prototyping</option><option value='Regression Testing'>Regression Testing</option><option value='Testing-oriented design'>Testing-oriented design</option><option value='Gane/Sarson structured Analysis'>Gane/Sarson structured Analysis</option><option value='Rapid Application Development'>Rapid Application Development</option><option value='Multifunctional Teams'>Multifunctional Teams</option><option value='Trial & Error'>Trial & Error</option><option value='Object Oriented Design'>Object Oriented Design</option><option value='Code inspections'>Code inspections</option></select></td></tr><tr><td align='right' width='19%'>Development Tools&nbsp;&nbsp;</td><td align='left' width='81%'><select size='1' name='devTools'><option value='ABEND-AID'>ABEND-AID</option><option value='BTS'>BTS</option><option value='ISPF'>ISPF</option><option value='Idea Debugger'>Idea Debugger</option><option value='LINC LITE'>LINC LITE</option><option value='MS CODEVIEW'>MS CODEVIEW</option><option value='SQL PLUS'>SQL PLUS</option><option value='TRACE'>TRACE</option><option value='Xpeditor'>Xpeditor</option><option value='Eclipse'>Eclipse</option><option value='JBuilder'>JBuilder</option><option value='MS Visual Studio'>MS Visual Studio</option><option value='Weblogic'>Weblogic</option><option value='Websphere'>Websphere</option><option value='IntelliJ IDEA'>IntelliJ IDEA</option><option value='NetBeans'>NetBeans</option><option value='Purify'>Purify</option></select></td></tr><tr><td align='right' width='19%'>Development&nbsp;&nbsp; Platforms&nbsp;&nbsp;</td><td align='left' width='81%'><select size='1' name='devPlatform'><option value='PC'>PC</option><option value='Workstation'>Workstation</option><option value='Mainframe'>Mainframe</option><option value='Embeded'>Embeded</option><option value='VAX'>VAX</option></select></td></tr> 
			
<tr><td align="right" width="19%">Development Sites&nbsp;&nbsp;</td><td align="left" width="81%"> 
<select size="1" name="devCountry" onChange="showProvinces(this);"> 
<option value="" selected="selected">-- Select One --</option>
<option value="AF">Afghanistan</option> 
<option value="AL">Albania</option> 
<option value="DZ">Algeria</option> 
<option value="AS">American Samoa</option> 
<option value="AD">Andorra</option> 
<option value="AI">Anguilla</option> 
<option value="AQ">Antarctica</option> 
<option value="AG">Antigua And Barbuda</option> 
<option value="AR">Argentina</option> 
<option value="AM">Armenia</option> 
<option value="AW">Aruba</option> 
<option value="AU">Australia</option> 
<option value="AT">Austria</option> 
<option value="AZ">Ayerbaijan</option> 
<option value="BS">Bahamas, The</option> 
<option value="BH">Bahrain</option> 
<option value="BD">Bangladesh</option> 
<option value="BB">Barbados</option> 
<option value="BY">Belarus</option> 
<option value="BZ">Belize</option> 
<option value="BE">Belgium</option> 
<option value="BJ">Benin</option> 
<option value="BM">Bermuda</option> 
<option value="BT">Bhutan</option> 
<option value="BO">Bolivia</option> 
<option value="BV">Bouvet Is</option> 
<option value="BA">Bosnia and Herzegovina</option> 
<option value="BW">Botswana</option> 
<option value="BR">Brazil</option> 
<option value="IO">British Indian Ocean Territory</option> 
<option value="BN">Brunei</option> 
<option value="BG">Bulgaria</option> 
<option value="BF">Burkina Faso</option> 
<option value="BI">Burundi</option> 
<option value="KH">Cambodia</option> 
<option value="CM">Cameroon</option> 
<option value="CA">Canada</option> 
<option value="CV">Cape Verde</option> 
<option value="KY">Cayman Is</option> 
<option value="CF">Central African Republic</option> 
<option value="TD">Chad</option> 
<option value="CL">Chile</option> 
<option value="CN">China</option> 
<option value="HK">China (Hong Kong S.A.R.)</option> 
<option value="MO">China (Macau S.A.R.)</option> 
<option value="TW">China (Taiwan T.W)</option> 
<option value="CX">Christmas Is</option> 
<option value="CC">Cocos (Keeling) Is</option> 
<option value="CO">Colombia</option> 
<option value="KM">Comoros</option> 
<option value="CK">Cook Islands</option> 
<option value="CR">Costa Rica</option> 
<option value="CI">Cote D'Ivoire (Ivory Coast)</option> 
<option value="HR">Croatia (Hrvatska)</option> 
<option value="CY">Cyprus</option> 
<option value="CZ">Czech Republic</option> 
<option value="CD">Democratic Republic of the Congo</option> 
<option value="DK">Denmark</option> 
<option value="DM">Dominica</option> 
<option value="DO">Dominican Republic</option> 
<option value="DJ">Djibouti</option> 
<option value="TP">East Timor</option> 
<option value="EC">Ecuador</option> 
<option value="EG">Egypt</option> 
<option value="SV">El Salvador</option> 
<option value="GQ">Equatorial Guinea</option> 
<option value="ER">Eritrea</option> 
<option value="EE">Estonia</option> 
<option value="ET">Ethiopia</option> 
<option value="FK">Falkland Is (Is Malvinas)</option> 
<option value="FO">Faroe Islands</option> 
<option value="FJ">Fiji Islands</option> 
<option value="FI">Finland</option> 
<option value="FR">France</option> 
<option value="GF">French Guiana</option> 
<option value="PF">French Polynesia</option> 
<option value="TF">French Southern Territories</option> 
<option value="MK">F.Y.R.O. Macedonia</option> 
<option value="GA">Gabon</option> 
<option value="GM">Gambia, The</option> 
<option value="GE">Georgia</option> 
<option value="DE">Germany</option> 
<option value="GH">Ghana</option> 
<option value="GI">Gibraltar</option> 
<option value="GR">Greece</option> 
<option value="GL">Greenland</option> 
<option value="GD">Grenada</option> 
<option value="GP">Guadeloupe</option> 
<option value="GU">Guam</option> 
<option value="GT">Guatemala</option> 
<option value="GN">Guinea</option> 
<option value="GW">Guinea-Bissau</option> 
<option value="GY">Guyana</option> 
<option value="HT">Haiti</option> 
<option value="HM">Heard and McDonald Is</option> 
<option value="HN">Honduras</option> 
<option value="HU">Hungary</option> 
<option value="IS">Iceland</option> 
<option value="IN">India</option> 
<option value="ID">Indonesia</option> 
<option value="IE">Ireland</option> 
<option value="IL">Israel</option> 
<option value="IT">Italy</option> 
<option value="JM">Jamaica</option> 
<option value="JP">Japan</option> 
<option value="JO">Jordan</option> 
<option value="KZ">Kayakhstan</option> 
<option value="KE">Kenya</option> 
<option value="KI">Kiribati</option> 
<option value="KR">Korea, South</option> 
<option value="KW">Kuwait</option> 
<option value="KG">Kyrgyzstan</option> 
<option value="LA">Laos</option> 
<option value="LV">Latvia</option> 
<option value="LB">Lebanon</option> 
<option value="LS">Lesotho</option> 
<option value="LR">Liberia</option> 
<option value="LI">Liechtenstein</option> 
<option value="LT">Lithuania</option> 
<option value="LU">Luxembourg</option> 
<option value="MG">Madagascar</option> 
<option value="MW">Malawi</option> 
<option value="MY">Malaysia</option> 
<option value="MV">Maldives</option> 
<option value="ML">Mali</option> 
<option value="MT">Malta</option> 
<option value="MH">Marshall Is</option> 
<option value="MR">Mauritania</option> 
<option value="MU">Mauritius</option> 
<option value="MQ">Martinique</option> 
<option value="YT">Mayotte</option> 
<option value="MX">Mexico</option> 
<option value="FM">Micronesia</option> 
<option value="MD">Moldova</option> 
<option value="MC">Monaco</option> 
<option value="MN">Mongolia</option> 
<option value="MS">Montserrat</option> 
<option value="MA">Morocco</option> 
<option value="MZ">Mozambique</option> 
<option value="MM">Myanmar</option> 
<option value="NA">Namibia</option> 
<option value="NR">Nauru</option> 
<option value="NP">Nepal</option> 
<option value="NL">Netherlands, The</option> 
<option value="AN">Netherlands Antilles</option> 
<option value="NC">New Caledonia</option> 
<option value="NZ">New Zealand</option> 
<option value="NI">Nicaragua</option> 
<option value="NE">Niger</option> 
<option value="NG">Nigeria</option> 
<option value="NU">Niue</option> 
<option value="NO">Norway</option> 
<option value="NF">Norfolk Island</option> 
<option value="MP">Northern Mariana Is</option> 
<option value="OM">Oman</option> 
<option value="PK">Pakistan</option> 
<option value="PW">Palau</option> 
<option value="PA">Panama</option> 
<option value="PG">Papua new Guinea</option> 
<option value="PY">Paraguay</option> 
<option value="PE">Peru</option> 
<option value="PH">Philippines</option> 
<option value="PN">Pitcairn Island</option> 
<option value="PL">Poland</option> 
<option value="PT">Portugal</option> 
<option value="PR">Puerto Rico</option> 
<option value="QA">Qatar</option> 
<option value="CG">Republic of the Congo</option> 
<option value="RE">Reunion</option> 
<option value="RO">Romania</option> 
<option value="RU">Russia</option> 
<option value="SH">Saint Helena</option> 
<option value="KN">Saint Kitts And Nevis</option> 
<option value="LC">Saint Lucia</option> 
<option value="PM">Saint Pierre and Miquelon</option> 
<option value="VC">Saint Vincent And The Grenadines</option> 
<option value="WS">Samoa</option> 
<option value="WM">San Marino</option> 
<option value="ST">Sao Tome and Principe</option> 
<option value="SA">Saudi Arabia</option> 
<option value="SN">Senegal</option> 
<option value="SC">Seychelles</option> 
<option value="SL">Sierra Leone</option> 
<option value="SG">Singapore</option> 
<option value="SK">Slovakia</option> 
<option value="SI">Slovenia</option> 
<option value="SB">Solomon Islands</option> 
<option value="SO">Somalia</option> 
<option value="ZA">South Africa</option> 
<option value="GS">South Georgia & The S. Sandwich Is</option> 
<option value="ES">Spain</option> 
<option value="LK">Sri Lanka</option> 
<option value="SR">Suriname</option> 
<option value="SJ">Svalbard And Jan Mayen Is</option> 
<option value="SZ">Swaziland</option> 
<option value="SE">Sweden</option> 
<option value="CH">Switzerland</option> 
<option value="SY">Syria</option> 
<option value="TJ">Tajikistan</option> 
<option value="TZ">Tanzania</option> 
<option value="TH">Thailand</option> 
<option value="TL">Timor-Leste</option> 
<option value="TG">Togo</option> 
<option value="TK">Tokelau</option> 
<option value="TO">Tonga</option> 
<option value="TT">Trinidad And Tobago</option> 
<option value="TN">Tunisia</option> 
<option value="TR">Turkey</option> 
<option value="TC">Turks And Caicos Is</option> 
<option value="TM">Turkmenistan</option> 
<option value="TV">Tuvalu</option> 
<option value="UG">Uganda</option> 
<option value="UA">Ukraine</option> 
<option value="AE">United Arab Emirates</option> 
<option value="GB">United Kingdom</option> 
<option value="US">United States</option> 
<option value="UM">United States Minor Outlying Is</option> 
<option value="UY">Uruguay</option> 
<option value="UZ">Uzbekistan</option> 
<option value="VU">Vanuatu</option> 
<option value="VA">Vatican City State (Holy See)</option> 
<option value="VE">Venezuela</option> 
<option value="VN">Vietnam</option> 
<option value="VG">Virgin Islands (British)</option> 
<option value="VI">Virgin Islands (US)</option> 
<option value="WF">Wallis And Futuna Islands</option> 
<option value="EH">Western Sahara</option> 
<option value="YE">Yemen</option> 
<option value="ZM">Zambia</option> 
<option value="ZW">Zimbabwe</option> 
</select>
<select name="devProvince" size="1" style="display:none">
<OPTION value="" selected>----Choose One----</OPTION> <OPTION value=anhui>Anhui</OPTION> 
                    <OPTION value=Beijing>Beijing</OPTION> <OPTION value=Chongqing>Chongqing</OPTION> 
                    <OPTION value=Fujian>Fujian</OPTION> <OPTION value=Gansu>Gansu</OPTION> 
                    <OPTION value=Guangdong>Guangdong</OPTION> <OPTION value=Guangxi>Guangxi</OPTION> 
                    <OPTION value=Guizhou>Guizhou</OPTION> <OPTION value=Hainan>Hainan</OPTION> 
                    <OPTION value=Hebei>Hebei</OPTION> <OPTION value=Heilongjiang>Heilongjiang</OPTION> 
                    <OPTION value=Henan>Henan</OPTION> <OPTION value=Hongkong>Hongkong</OPTION> 
                    <OPTION value=Hubei>Hubei</OPTION> <OPTION value=Hunan>Hunan</OPTION> 
                    <OPTION value=Jiangsu>Jiangsu</OPTION> <OPTION value=Jiangxi>Jiangxi</OPTION> 
                    <OPTION value=Jilin>Jilin</OPTION> <OPTION value=Liaoning>Liaoning</OPTION> 
                    <OPTION value=Macao>Macao</OPTION> <OPTION value=Neimenggu>Neimenggu</OPTION> 
                    <OPTION value=Ningxia>Ningxia</OPTION> <OPTION value=Qinghai>Qinghai</OPTION> 
                    <OPTION value=Shandong>Shandong</OPTION> <OPTION value=Shanghai>Shanghai</OPTION> 
                    <OPTION value=Shanxi>Shanxi</OPTION> <OPTION value=Shannxi>Shannxi</OPTION> 
                    <OPTION value=Sichuan>Sichuan</OPTION> <OPTION value=Taiwan>Taiwan</OPTION> 
                    <OPTION value=Tianjin>Tianjin</OPTION> <OPTION value=Xinjiang>Xinjiang</OPTION> 
                    <OPTION value=Xizang>Xizang</OPTION> <OPTION value=Yunnan>Yunnan</OPTION> 
                    <OPTION value=Zhejiang>Zhejiang</OPTION>
</select>
</td></tr> 
<tr>
<td></td>
<td><div style="float:right"><input type="submit" value="Next"  class="input5"/></div></td>
</tr>
</table> 

&nbsp;&nbsp;&nbsp;

</form>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="650" width="195"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>