package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.CustomBean;
import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.db.DBOperation;

import java.io.IOException;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.PreparedStatement;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

public class CustomizeServlet extends HttpServlet{

	/**
	 * 
	 */
	private static final long serialVersionUID = 3992980909419211053L;
	
	public void doPost(HttpServletRequest request,HttpServletResponse response) throws IOException{
		request.setCharacterEncoding("UTF-8");
		
		HttpSession session = request.getSession();
		User user = (User) session.getAttribute("user");
		
		CustomBean cb = new CustomBean();
		String[] researchArea = request.getParameterValues("researchArea");
		if(researchArea != null) 
			cb.setResearchAreas(researchArea);
		String customBa = request.getParameter("custom_ba");
		if(customBa != null) 
			cb.setCustomBa(customBa);
		String customLcp = request.getParameter("custom_lcp");
		if(customLcp != null) 
			cb.setCustomLcp(customLcp);
		cb.setCustomEmail(request.getParameter("email"));
		System.out.print(cb.getResearchAreas4SQL()+"||"+cb.getCustomBa()+"||"+cb.getCustomLcp()+"||"+cb.getCustomEmail());
		
		try {
			Connection con = DBOperation.getConnection();
			PreparedStatement stmt = con.prepareStatement("INSERT INTO customization(user_id,custom_ra,custom_ba,custom_lcp,custom_email) VALUES(?,?,?,?,?)");
			stmt.setInt(1,user.getId());
			stmt.setString(2, cb.getResearchAreas4SQL());
			stmt.setString(3, cb.getCustomBa());
			stmt.setString(4, cb.getCustomLcp());
			stmt.setString(5, cb.getCustomEmail());
			
			stmt.execute();
			
			PrintWriter out1=response.getWriter();
			out1.println("<script>alert('Customize successfully!');document.location='customize.jsp';</script>"); 
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
