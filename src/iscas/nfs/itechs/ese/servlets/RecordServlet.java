package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.DataBean;
import iscas.nfs.itechs.ese.beans.UploadedFile;
import iscas.nfs.itechs.ese.utils.Utilities;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

import com.jspsmart.upload.File;
import com.jspsmart.upload.Files;
import com.jspsmart.upload.Request;
import com.jspsmart.upload.SmartUpload;
import com.jspsmart.upload.SmartUploadException;

public class RecordServlet extends HttpServlet {
	/**
	 * 
	 */	
	private static final long serialVersionUID = 1L;
	
	private UploadedFile[] generateFiles(HttpServletRequest request, HttpServletResponse response,
			UploadedFile[] result) throws IOException, ServletException, SmartUploadException {
		SmartUpload fileUploader = new SmartUpload();
		fileUploader.initialize(this.getServletConfig(), request, response);
		fileUploader.upload();
		Request req = fileUploader.getRequest();
		
		Files files = fileUploader.getFiles();
		result = new UploadedFile[files.getCount()];
		for(int i = 0; i < files.getCount(); i++) {
			File file = files.getFile(i);
			if(file.isMissing()) continue;
			result[i] = new UploadedFile();
			result[i].setDescription(new String(
					req.getParameterValues("file_descriptive_name")[i].getBytes("gbk"), "gb2312"));
			Utilities.generateFile(file, result[i]);
		}
		
		request.setAttribute("stepFlag", req.getParameter("stepFlag"));
		request.getSession().setAttribute("fileUploader", fileUploader);
	
		return result;
	}

	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.setCharacterEncoding("utf-8");
		HttpSession session = request.getSession();
		DataBean data = (DataBean)session.getAttribute("data");
		
		try {
			data.setFiles(generateFiles(request, response, data.getFiles()));
		} catch (ServletException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (SmartUploadException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
//		response.setCharacterEncoding("gb2312");
		
		//response.sendRedirect("recordlist.jsp");
		RequestDispatcher dispatcher = request.getRequestDispatcher("recordlist.jsp"); 
		try {
			dispatcher.forward(request, response);
		} catch (ServletException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
	}
}
