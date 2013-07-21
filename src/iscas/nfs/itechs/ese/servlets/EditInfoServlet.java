package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.db.DBOperation;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;


public class EditInfoServlet extends HttpServlet{
	/**
	 * 
	 */
	private static final long serialVersionUID = 3222214324L;

	public void doPost(HttpServletRequest request,HttpServletResponse response)throws IOException{
		request.setCharacterEncoding("UTF-8");
		response.addHeader("Cache-Control", "no-cache, must-revalidate");
		
		HttpSession session = request.getSession();
		User user = (User) session.getAttribute("user");
		String name = request.getParameter("username");
		String aff = request.getParameter("affiliation");
		String title = request.getParameter("title");
		String email = request.getParameter("email");
		String interest = request.getParameter("interest");
		String country = request.getParameter("country");
		user.setName(name);
		user.setAffiliation(aff);
		user.setTitle(title);
		user.setEmail(email);
		user.setInterest(interest);
		user.setCountry(country);
		
		try {
			Connection con = (Connection) DBOperation.getConnection();
			PreparedStatement stmt = con.prepareStatement("UPDATE users set username=?,affiliation=?,title=?,email=?,interest=?,country=? where id=?");
			stmt.setString(1, name);
			stmt.setString(2, aff);
			stmt.setString(3, title);
			stmt.setString(4, email);
			stmt.setString(5, interest);
			stmt.setString(6,country);
			stmt.setInt(7, user.getId());
			stmt.execute();
			
			response.sendRedirect("editInfo.jsp");
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
