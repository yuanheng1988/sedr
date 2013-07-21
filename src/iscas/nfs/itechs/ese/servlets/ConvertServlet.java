package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.convert.ConvertByJScript;
import iscas.nfs.itechs.ese.convert.ConvertReport;
import iscas.nfs.itechs.ese.convert.ConvertToArff;
import iscas.nfs.itechs.ese.convert.GenerateUniqueFile;
import iscas.nfs.itechs.ese.convert.PropertiesManager;
import iscas.nfs.itechs.ese.utils.Constants;

import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.Random;
import java.util.zip.ZipOutputStream;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.jspsmart.upload.File;
import com.jspsmart.upload.Request;
import com.jspsmart.upload.SmartUpload;
import com.jspsmart.upload.SmartUploadException;

public class ConvertServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = -481128904712859984L;
	private static final String KEY = "convert.GenerateUniqueFile.DIR1";
	private static final Random random = new Random();
	private static final String TEMP_STORAGE = Constants.ARFF_TEMP_STORAGE;

	private String convert(String fileName, File file) throws IOException, SmartUploadException {
		fileName = (fileName == "" || fileName == null) ? "TEMP" : fileName;
		String fileFormat = "." + file.getFileExt().toLowerCase();
		String store = TEMP_STORAGE + random.nextLong() + fileFormat;
		file.saveAs(store, SmartUpload.SAVE_PHYSICAL);
		
		java.io.File fl = new java.io.File(store);
		FileInputStream fis = new FileInputStream(fl);
		ZipOutputStream zos = null;
		String zip = random.nextLong() + ".zip";
		try {
			PropertiesManager.loadAll();
			GenerateUniqueFile guf = (GenerateUniqueFile)this.getServletContext().getAttribute("guf");
			if(guf == null) {
				guf = new GenerateUniqueFile(PropertiesManager.getApp(KEY));
				this.getServletContext().setAttribute("guf", guf);
			}
			ConvertToArff cta = new ConvertByJScript(guf);
			FileOutputStream fos = new FileOutputStream(TEMP_STORAGE + zip);
			zos = new ZipOutputStream(fos);
			
			ConvertReport report = null;
			String format = null, CP_default = null;
			
			if(fileFormat.equals(".xls") || fileFormat.equals(".xlsx"))
				format = ConvertToArff.FORMAT_XLS;
			else if(fileFormat.equals(".doc") || fileFormat.equals(".docx"))
				format = ConvertToArff.FORMAT_DOC;
			else if(fileFormat.equals(".csv")) {
				format = ConvertToArff.FORMAT_CSV;
				CP_default = ConvertToArff.CP_DEFAULT;
			}
			report = cta.doConvert(fis, format, CP_default, zos);
			if(report != null && report.successCount() > 0) {
				zos.close();
			} else {
				fos.close();
				zos = null;
			}
			return zip;
		} catch(Exception e) {
			e.printStackTrace();
			return null;
		} finally {
			if(fis != null)
				try {
					fis.close();
				} catch(Exception e) {
					e.printStackTrace();
				}
			if(zos != null)
				try {
					zos.close();
				} catch(Exception e) {
					e.printStackTrace();
				}
			fl.delete();
		}
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException,
			ServletException {
		request.setCharacterEncoding("gb2312");
		SmartUpload fileUploader = new SmartUpload();
		try {
			fileUploader.initialize(this.getServletConfig(), request, response);
			fileUploader.upload();
		} catch (ServletException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (SmartUploadException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		Request req = fileUploader.getRequest();

		try {
			String result = convert(req.getParameter("file_name"), fileUploader.getFiles().getFile(0));
			if(result == null)
				response.sendRedirect("convert.jsp?flag=false");
			else response.sendRedirect("convert.jsp?flag=true&zip=" + result);
		} catch (SmartUploadException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
