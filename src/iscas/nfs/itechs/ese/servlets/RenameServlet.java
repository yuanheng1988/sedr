package iscas.nfs.itechs.ese.servlets;

import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.PreparedStatement;

import iscas.nfs.itechs.ese.db.DBOperation;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class RenameServlet extends HttpServlet{

	/**
	 * 
	 */
	private static final long serialVersionUID = -4985941085742609068L;
	
	public void doGet(HttpServletRequest request,HttpServletResponse response){
		String newname = request.getParameter("newname");
		int fileId = Integer.parseInt(request.getParameter("fileId"));
		
		try {
			Connection con = DBOperation.getConnection();
			PreparedStatement stmt = con.prepareStatement("update file_desc set description=? where id=?");
			stmt.setString(1, newname);
			stmt.setInt(2, fileId);
			
			stmt.execute();
			
			PrintWriter out1=response.getWriter();
			out1.println("<script>alert('Rename successfully!');document.location='editInfo.jsp';</script>"); 
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

}
