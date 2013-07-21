<%@ page language="java" import="java.util.*" pageEncoding="ISO-8859-1"%>
<%@ page import="iscas.nfs.itechs.ese.utils.Utilities" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Welcome to iTechs SEDR!</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/share.js"></script>
</head>

<body>
<iframe src="top.jsp" scrolling="no" frameborder="0" width="100%" height="225"></iframe>

<div class="div4">
<form action="register" name="registerForm" method="POST" onsubmit="return validateRegister()">
	<table border="1">
		<tr><td colspan="2" align="center">Register('*' items are necessary)</td></tr>
		<tr>
			<td align="right">Username(*):</td>
			<td align="left">
				<input type="text" name="username" />
				<%
				String registered = request.getParameter("registered");
				if(registered != null && registered.equals("true")) {
				%>
				<font color="red">User has existed!</font>
				<%
				}
				 %>
			</td>
		</tr>
		<tr>
			<td align="right">Password(*):</td>
			<td align="left">
				<input type="password" name="password" />
			</td>
		</tr>
		<tr>
			<td align="right">Confirm Password(*):</td>
			<td align="left">
				<input type="password" name="pwd" />
			</td>
		</tr>
		<tr>
			<td align="right">Affiliation:</td>
			<td align="left">
				<input type="text" name="affiliation" />
			</td>
		</tr>
		<tr>
			<td align="right">Title:</td>
			<td align="left">
				<select size="1" name="title">
					<option value="">-- Select One --</option>
					<option value="Prof.">Prof.</option>
					<option value="Dr.">Dr.</option>
					<option value="Ms.">Ms.</option>
					<option value="Mr.">Mr.</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">Email(*):</td>
			<td align="left">
				<input type="text" name="email" />
			</td>
		</tr>
		<tr>
			<td align="right">Research Interest:</td>
			<td align="left">
				<input type="text" name="interest" />
			</td>
		</tr>
		<tr>
			<td align="right">Country:</td>
			<td align="left">
				<select name="country" size="1">
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
			</td>
		</tr>
	</table>
	<input type="submit" value="Register" />&nbsp;&nbsp;&nbsp;<input type="reset" value="Reset" />
</form>
</div>

<div class="div2"><iframe src="left_slide.html" scrolling="no" frameborder="0" height="600" width="190"></iframe></div>
<div><iframe src="bottom.html" width="100%" height="80" frameborder="0" scrolling="no"></iframe></div>
</body>
</html>