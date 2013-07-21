package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.db.DBOperation;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class DeleteCustomServlet extends HttpServlet{

	
	/**
	 * 
	 */
	private static final long serialVersionUID = -3776917888002551609L;

	public void doPost(HttpServletRequest request,HttpServletResponse response) throws IOException{
		request.setCharacterEncoding("utf-8");
		int customId = Integer.parseInt(request.getParameter("customId"));
		
		try {
			Connection con = DBOperation.getConnection();
			
			PreparedStatement stmt = con.prepareStatement("DELETE FROM customization WHERE Id=?");
			stmt.setInt(1, customId);
			stmt.execute();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
		
	}

}
