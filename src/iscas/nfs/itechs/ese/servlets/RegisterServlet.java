package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.db.DBOperation;
import iscas.nfs.itechs.ese.utils.Utilities;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class RegisterServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = -2495481508211569019L;
	private static final int DEFAULT_AUTH = 2;

	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.setCharacterEncoding("UTF-8");
		response.addHeader("Cache-Control", "no-cache, must-revalidate");
		String userName = request.getParameter("username");
		/**
		 * 
		 */
		User user = null;
		try {
			Connection con = DBOperation.getConnection();
			PreparedStatement stmt = con.prepareStatement("SELECT * from users WHERE username=?");
			stmt.setString(1, userName);
			if(stmt.executeQuery().next()) {
				response.sendRedirect("register.jsp?registered=true");
				return;
			} else {
				String pwd = request.getParameter("password");
				String aff = request.getParameter("affiliation");
				String title = request.getParameter("title");
				String email = request.getParameter("email");
				String interest = request.getParameter("interest");
				String country = request.getParameter("country");
				
				user = new User();
				user.setName(userName);
				user.setPwd(pwd);
				user.setAffiliation(aff);
				user.setAuthID(DEFAULT_AUTH);
				user.setTitle(title);
				user.setEmail(email);
				user.setInterest(interest);
				user.setCountry(country);
				
				Utilities.registerUser(user);
				request.getSession().setAttribute("user", DBOperation.getUser(userName, pwd));
				
				response.sendRedirect("index.jsp");
			}
		} catch (Exception e1) {
			// TODO Auto-generated catch block
		}
	}
}
