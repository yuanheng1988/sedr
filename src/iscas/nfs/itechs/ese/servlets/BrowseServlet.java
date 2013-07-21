package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.db.DBOperation;
import iscas.nfs.itechs.ese.utils.Constants;
import iscas.nfs.itechs.ese.utils.Utilities;

import java.io.IOException;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Calendar;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class BrowseServlet extends HttpServlet  {
	/**
	 * 
	 */
	private static final long serialVersionUID = 8843680609050033017L;

	public void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
		response.addHeader("Cache-Control", "no-cache, must-revalidate");
		int fileID = Integer.valueOf(request.getParameter("file_id"));
    	response.getWriter().write(String.valueOf(Utilities.markOrUnmarkFile(request.getSession(), fileID)));
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		int fileID = Integer.valueOf(request.getParameter("file_id"));
		int userID = Integer.valueOf(request.getParameter("user_id"));
		try {
			PreparedStatement stmt = DBOperation.getConnection().prepareStatement("SELECT download_count FROM " +
					"file_desc WHERE id=?");
			stmt.setInt(1, fileID);
			ResultSet rs = stmt.executeQuery();
			rs.next();
			int count = rs.getInt(1);
			stmt = DBOperation.getConnection().prepareStatement("UPDATE file_desc " +
					"SET download_count=? WHERE id=?");
			stmt.setInt(1, count + 1);
			stmt.setInt(2, fileID);
			stmt.execute();
			
			stmt = DBOperation.getConnection().prepareStatement("SELECT download_count FROM file_desc WHERE " +
					"id=?");
			stmt.setInt(1, fileID);
			rs = stmt.executeQuery();
			rs.next();
			count = rs.getInt(1);
			
			stmt = DBOperation.getConnection().prepareStatement("INSERT INTO download_record(file_id, download_time, ip,user_id)"+
				" VALUES(?, ?, ?,?)");
			stmt.setInt(1, fileID);
			stmt.setString(2, Constants.DATE_FORMAT.format(Calendar.getInstance().getTime()));
			stmt.setString(3, request.getRemoteAddr());
			stmt.setInt(4, userID);
			stmt.execute();
			
			response.getWriter().write(count + "$$$");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
