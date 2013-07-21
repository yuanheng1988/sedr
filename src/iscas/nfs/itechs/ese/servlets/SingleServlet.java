package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.DataBean;
import iscas.nfs.itechs.ese.beans.OtherFields;
import iscas.nfs.itechs.ese.beans.Scope;
import iscas.nfs.itechs.ese.beans.SingleOrganization;
import iscas.nfs.itechs.ese.beans.SingleProject;
import iscas.nfs.itechs.ese.beans.User;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

public class SingleServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	
	public void doGet(HttpServletRequest request, HttpServletResponse response) {
		
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException,IOException {
		request.setCharacterEncoding("utf-8");
		DataBean data = new DataBean();
		HttpSession session = request.getSession();
		
		data.setContributor(((User)session.getAttribute("user")).getName());
		data.setSubjectArea(request.getParameter("data_subject"));
		data.setGeneratedPhase(request.getParameter("data_phase"));
		data.setSupportAnalysis(request.getParameter("support_analysis"));
		OtherFields otherFields = new OtherFields();
		String[] researchArea = request.getParameterValues("researchArea");
		if(researchArea != null) otherFields.setResearchAreas(researchArea);
		otherFields.setIntegrity(Float.valueOf(request.getParameter("integrity")) / 100);
		
		data.setOthers(otherFields);
		
		session.setAttribute("data", data);
		
		String scope_scale = request.getParameter("scope_scale");
		String scope = request.getParameter("scope");
		RequestDispatcher dispatcher = null;
		if(scope.equals("Organization") && scope_scale.equals("Single")) {//Single Organization
			SingleOrganization so = new SingleOrganization();
			so.setScope("Organization");
			so.setScopeScale("Single");
			data.setScope(so);
			//request.setAttribute("stepFlag", 1);
			dispatcher = request.getRequestDispatcher("singleOrganization.jsp");
			dispatcher.forward(request, response);
		} else if(scope.equals("Project") && scope_scale.equals("Single")) {//Single Project
			SingleProject sp = new SingleProject();
			sp.setScope("Project");
			sp.setScopeScale("Single");
			data.setScope(sp);
			//request.setAttribute("stepFlag", 1);
			dispatcher = request.getRequestDispatcher("singleProject.jsp");
			dispatcher.forward(request, response);
		} else {
			Scope sc = new Scope();
			if((scope.equals("Organization")))
				sc.setScope("Organization");
			else
				sc.setScope("Project");
			sc.setScopeScale("Multiple");
			data.setScope(sc);
			request.setAttribute("stepFlag", 2);
			//response.sendRedirect("submit.jsp");
			dispatcher = request.getRequestDispatcher("submit.jsp"); 
			dispatcher .forward(request, response);

		}
	}
}
