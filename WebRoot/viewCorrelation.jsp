<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%@ page import="iscas.nfs.itechs.ese.merge.ArffFile, iscas.nfs.itechs.ese.merge.ArffParser,iscas.nfs.itechs.ese.convert.PropertiesManager,iscas.nfs.itechs.ese.utils.Utilities" %>
<%@ page import="weka.core.Attribute, weka.core.Instance, weka.core.Instances,weka.core.converters.ArffLoader" %>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<%
	String fileName = request.getParameter("fileName");
	ArffFile file = null;
	
	String relationName = new String();
	ArffLoader loader = new ArffLoader();
	
	Attribute[] attrs = null;
	Instance[] data = null;
	if(fileName != null){
		ArffParser parser = new ArffParser();
		try {
		    loader.setSource(new java.io.File(PropertiesManager.getApp("Constants.FILE_STORAGE"),fileName));
			relationName = loader.getDataSet().relationName();
		
			file = parser.getArffFileWithName(fileName, file);
			attrs = file.getAttributes();
			data = file.getData();
		} catch(Exception e) {
			out.write("The content of the file you want to view is invalid!");
		}
	}
 %>
  <head>
    <base href="<%=basePath%>">
    
    <title><%=request.getParameter("on") %></title>
    <script type="text/javascript" src="js/browse.js"></script>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<!--
	<link rel="stylesheet" type="text/css" href="styles.css">
	-->

  </head>
  
  <body>
   	<center>
   		<% 
   			if(file != null){ 
   			%>
   			<font face="Papyrus" size="4">Relation: <%=relationName %></font>
   			<table width="75%" border="1">
   			<tr><th>Row number</th><th>Column a(Axis_x)</th><th>Column b(Axis_y)</th><th>Pearson correlation coefficient</th></tr> 
   			<%
   			List<String> pearsonValues = new ArrayList<String>();
   			double[] xArray = new double[data.length];
   		    double[] yArray = new double[data.length];
   		    double temp;
   		    double tempPearsonValue;
   		    int index;
   			
   			for(int i=0;i<attrs.length;i++){
   			    for(int j=i+1;j<attrs.length;j++){
   			       if(Attribute.typeToString(attrs[i]).equals("numeric") && Attribute.typeToString(attrs[j]).equals("numeric")){
   			          for(int k=0;k<data.length;k++){
   			              xArray[k] = Float.valueOf(data[k].toString(attrs[i]));
   			              yArray[k] = Float.valueOf(data[k].toString(attrs[j]));
   			          }
   			          temp = Utilities.getPearsonValue(xArray,yArray);
   			          index = 0;
   			          while(index < pearsonValues.size()){
   			             tempPearsonValue = Float.parseFloat(pearsonValues.get(index).split(":")[0]);
   			             if(temp <= tempPearsonValue){
   			                 index++;        
//   			             System.out.println(tempPearsonValue);
   			             }
   			             else
   			                 break;
   			          }
   			          pearsonValues.add(index,temp + ":" + i +":" + j);
   			       }
   			    }
   		    }
   		    
   		    for(int i=0;i<pearsonValues.size();i++){
   		        int index_x = Integer.parseInt(pearsonValues.get(i).split(":")[1]);
   		        int index_y = Integer.parseInt(pearsonValues.get(i).split(":")[2]);
   		        double pearsonValue = Double.parseDouble(pearsonValues.get(i).split(":")[0]);   
   			 %>
   		    <tr><td><%=i+1 %></td><td><%=attrs[index_x].name() %></td><td><%=attrs[index_y].name() %></td><td><%=pearsonValue %></td><td><input type="button" value="draw chart" onclick="drawCorrelationChart('<%=fileName %>',<%=index_x %>,<%=index_y %>)"/></td></tr>
   		    <%
   		    }
   		     %>
   		</table>
   			<%
   			} else {
   				out.write("<font size='4' face='Papyrus'><strong>No data</strong></font>");
   			}
   		 %>
   	</center>
  </body>
</html>
