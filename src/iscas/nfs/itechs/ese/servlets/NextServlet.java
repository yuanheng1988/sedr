package iscas.nfs.itechs.ese.servlets;

import java.io.IOException;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class NextServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = 2319403674396136340L;

	public void doPost(HttpServletRequest req, HttpServletResponse res) {
		System.out.println("Okay");
		
		try {
			res.sendRedirect("index.jsp");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
