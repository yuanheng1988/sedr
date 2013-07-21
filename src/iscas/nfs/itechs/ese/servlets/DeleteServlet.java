package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.db.DBOperation;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class DeleteServlet extends HttpServlet{

	/**
	 * 
	 */
	private static final long serialVersionUID = -2244596186817878760L;

	public void doPost(HttpServletRequest request,HttpServletResponse response) throws IOException{
		request.setCharacterEncoding("utf-8");
		int fileId = Integer.parseInt(request.getParameter("fileID"));
		System.out.println(fileId);
		try {
			Connection con = DBOperation.getConnection();
			
			PreparedStatement stmt = con.prepareStatement("delete from file_desc where id=?");
			stmt.setInt(1, fileId);
			stmt.execute();
			
			stmt = con.prepareStatement("Delete from data_file where file_id=?");
			stmt.setInt(1, fileId);
			stmt.execute();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
