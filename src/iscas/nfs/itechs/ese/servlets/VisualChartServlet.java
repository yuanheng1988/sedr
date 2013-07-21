package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.merge.ArffFile;
import iscas.nfs.itechs.ese.merge.ArffParser;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import weka.core.Attribute;
import weka.core.Instance;

public class VisualChartServlet extends HttpServlet{

	/**
	 * 
	 */
	private static final long serialVersionUID = -5927179205958312416L;
	
	public void doPost(HttpServletRequest request,HttpServletResponse response) throws IOException{
		PrintWriter out = response.getWriter();
		
		String fileName = request.getParameter("fileName");
		ArffFile file = null;		
		Attribute[] attrs = null;
		Instance[] data = null;
		if(fileName != null){
			ArffParser parser = new ArffParser();
			try {
				file = parser.getArffFileWithName(fileName, file);
				attrs = file.getAttributes();
				data = file.getData();
				System.out.println("hehe");
			}  catch(Exception e) {
				out.write("The content of the file you want to view is invalid!");
			}
		}
		
		int index_x = Integer.valueOf(request.getParameter("index_x"));
	  	int index_y = Integer.valueOf(request.getParameter("index_y"));
	  	
	  	System.out.println(index_x + "||" + index_y);
	  	Attribute attr_x = attrs[index_x];
	  	Attribute attr_y = attrs[index_y];
	  	String xAxis = "";
	  	String yAxis = "";
	  	
	  	out.write("<center>");
	  	out.write("Data of axis_x and axis_y:");
	  	out.write("<table border='1'>");
 		out.write("<tr><th>AXIS_X:"+ attr_x.name() + "</th>");
 		
 		for(int i=0;i<data.length;i++){
 			out.write("<td>" + data[i].toString(attrs[index_x]) + "</td>");
 			xAxis += data[i].toString(attrs[index_x]);
 			if(i != data.length-1) xAxis += ",";
 		}
 		
 		out.write("</tr>");
 		out.write("<tr><th>AXIS_Y:" + attr_y.name() + "</th>");
 		
 		for(int i=0;i<data.length;i++){
 			out.write("<td>" + data[i].toString(attrs[index_y]) + "</td>");
 			yAxis += data[i].toString(attrs[index_y]);
 			if(i != data.length-1) yAxis += ",";
 		}
 		System.out.println(xAxis);
 		System.out.println(yAxis);
 		
 		out.write("</tr>");
 		out.write("</table>");
 		
 		out.write("<div id='table_div'></div>");
 		out.write("<div id='scatterChart_div'></div>");
 		out.write("<div id='columnChart_div'></div>");
 		if(Attribute.typeToString(attrs[index_x]).equals("numeric") && Attribute.typeToString(attrs[index_y]).equals("numeric")){
//			System.out.println("entered!!");
 			out.write("$$$drawScatterChart('" + xAxis + "','" + yAxis + "',document.getElementById('scatterChart_div'));$$$");
// 			System.out.println("will go out!!");
 		}
 		else if((Attribute.typeToString(attrs[index_x]).equals("string") || Attribute.typeToString(attrs[index_x]).equals("nominal")) && Attribute.typeToString(attrs[index_y]).equals("numeric")){
 			out.write("$$$drawColumnChart('" + xAxis + "','" + yAxis + "',document.getElementById('columnChart_div'));$$$");
 		}
 		else if( Attribute.typeToString(attrs[index_x]).equals("date") && Attribute.typeToString(attrs[index_y]).equals("numeric")){
 			out.write("$$$drawColumnChart(\"" + xAxis + "\",'" + yAxis + "',document.getElementById('columnChart_div'));$$$");
 		}
 		else{
 			out.write("<font size='4' face='Papyrus'><strong>No chart</strong></font>");
 		}
 		
 		out.write("</center>");
 	}
}
