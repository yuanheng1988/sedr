package iscas.nfs.itechs.ese.servlets;

import java.io.IOException;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class SearchServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = -9042028183993567203L;

	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {		
		request.setCharacterEncoding("UTF-8");
		String[] keyWords = null;
		if(request.getParameter("keyWords") != null && !request.getParameter("keyWords").equals("")) {
			keyWords = request.getParameter("keyWords").split("\\s+");
		}
		if(request.getParameter("hint") != null) {
			response.sendRedirect(redirectWithHint(keyWords));
			return;
		}
		String[] researchArea = request.getParameterValues("researchArea");
		String[] phase = request.getParameterValues("phase");
		StringBuffer sb = new StringBuffer("searchRepository.jsp?keyWords=" + 
				request.getParameter("keyWords") + "&ra=");
		int i = 0;
		if(researchArea != null) {
			for(; i < researchArea.length - 1; i++) {
				sb.append(researchArea[i] + "@");
			}
			sb.append(researchArea[i]);
		}
		sb.append("&phase=");
		if(phase != null) {
			for(i = 0; i < phase.length - 1; i++) {
				sb.append(phase[i] + "@");
			}
			sb.append(phase[i]);
		}
		sb.append("&searched=true");
		response.sendRedirect(sb.toString());
	}
	
	private String redirectWithHint(String[] keyWords) {
		return null;
	}
}
