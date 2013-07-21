package iscas.nfs.itechs.ese.servlets;


import iscas.nfs.itechs.ese.beans.DataBean;
import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.utils.Constants;
import iscas.nfs.itechs.ese.utils.Utilities;

import java.io.IOException;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.jspsmart.upload.File;
import com.jspsmart.upload.Files;
import com.jspsmart.upload.SmartUpload;
import com.jspsmart.upload.SmartUploadException;

public class ConfirmServlet extends HttpServlet {

	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	private void uploadFiles(HttpServletRequest request, DataBean data) throws IOException, SmartUploadException {
		SmartUpload fileUploader = (SmartUpload)request.getSession().getAttribute("fileUploader");
		
		Files files = fileUploader.getFiles();
		for(int i = 0; i < files.getCount(); i++) {
			File file = files.getFile(i);
			
			if(file.isMissing()) continue;
			file.saveAs(Constants.FILE_STORAGE + data.getFiles()[i].getCurrentName(), SmartUpload.SAVE_PHYSICAL);
		}
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.setCharacterEncoding("utf-8");
		
		DataBean data = (DataBean)request.getSession().getAttribute("data");
		try {
			uploadFiles(request, data);
			
			Utilities.saveData(data, (User)request.getSession().getAttribute("user"));
			
			//在saveData函数中发送邮件
		} catch (SmartUploadException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		response.sendRedirect("shareData.jsp");
	}
}
