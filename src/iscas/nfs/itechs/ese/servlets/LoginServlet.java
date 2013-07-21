package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.db.DBOperation;

import java.io.IOException;
import java.io.PrintWriter;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class LoginServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = -7544826480770003680L;

	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.setCharacterEncoding("UTF-8");
		String userName = request.getParameter("username");
		String pwd = request.getParameter("password");
		User user = null;
		try {
			user = DBOperation.getUser(userName, pwd);
		} catch (Exception e) {
			// TODO Auto-generated catch block
		} finally {
			request.getSession().setAttribute("user", user);
			PrintWriter out = response.getWriter();
			out.println("<html><script>window.open('index.jsp', '_top')</script></html>");
		}
	}
	
	public void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.getSession().removeAttribute("user");
		PrintWriter out = response.getWriter();
		out.println("<html><script>window.open('index.jsp', '_top')</script></html>");
	}
}
