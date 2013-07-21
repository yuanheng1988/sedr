package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.Feedback;
import iscas.nfs.itechs.ese.db.DBOperation;
import iscas.nfs.itechs.ese.utils.EmailSender;

import java.io.IOException;
import java.sql.Connection;
import java.sql.PreparedStatement;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class FeedbackServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = 4385009583172587229L;
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.setCharacterEncoding("UTF-8");
		Feedback fb = new Feedback();
		fb.setEmail(request.getParameter("email"));
		fb.setTitle(request.getParameter("title"));
		fb.setContent(request.getParameter("content"));
			
		try {
			saveFeedback(fb);
			EmailSender.sendFeedbackEmail(fb);
			response.sendRedirect("feedback.jsp?feedback=yes");
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	private static void saveFeedback(Feedback fb) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("INSERT INTO " +
				"feedback(email, title, content) VALUES(?, ?, ?)");
		stmt.setString(1, fb.getEmail());
		stmt.setString(2, fb.getTitle());
		stmt.setString(3, fb.getContent());
		
		stmt.execute();
	}
}
