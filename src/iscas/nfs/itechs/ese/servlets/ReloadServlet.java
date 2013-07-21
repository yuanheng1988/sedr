package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.utils.Utilities;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.SQLException;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.jspsmart.upload.File;
import com.jspsmart.upload.Files;
import com.jspsmart.upload.SmartUpload;
import com.jspsmart.upload.SmartUploadException;

public class ReloadServlet extends HttpServlet{
	/**
	 * 
	 */
	private static final long serialVersionUID = -3244276253370849113L;
	
	public void doPost(HttpServletRequest request,HttpServletResponse response) throws IOException{
		request.setCharacterEncoding("utf-8");

		SmartUpload fileUpdater = new SmartUpload();
		try {
			fileUpdater.initialize(this.getServletConfig(), request, response);
			fileUpdater.upload();
		} catch (ServletException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (SmartUploadException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		Files files = fileUpdater.getFiles();
		File file = files.getFile(0);
		
		int fileId = Integer.parseInt(fileUpdater.getRequest().getParameter("reloadFileSelect"));
		
		try {
			//file = generateUpdateFile(request,response,file,fileId);
			
			//uploadFile(request,file);
			Utilities.saveUpdateFile(file,fileId);
		}  catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		PrintWriter out1=response.getWriter();
		out1.println("<script>alert('Reload successfully!');document.location='editInfo.jsp';</script>"); 
	}
	
	
}
