package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.beans.DataBean;
import iscas.nfs.itechs.ese.beans.Scope;
import iscas.nfs.itechs.ese.beans.SingleOrganization;
import iscas.nfs.itechs.ese.beans.SingleProject;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;

public class SubmitServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;

	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		request.setCharacterEncoding("utf-8");
		HttpSession session = request.getSession();
		DataBean data = (DataBean)session.getAttribute("data");
		Scope scope = data.getScope();
		SingleOrganization so = null;
		SingleProject sp = null;
		if(scope instanceof SingleOrganization) {
			so = (SingleOrganization)scope;
			
			so.setOrgType(request.getParameter("org_type"));
			so.setBaType(request.getParameter("ba_type"));
			so.setAppTypes(request.getParameterValues("app_type"));
			
			data.setScope(so);
			request.setAttribute("stepFlag", 1);
		} else if(scope instanceof SingleProject) {
			sp = (SingleProject)scope;
			
			sp.setArchitecture(request.getParameter("architecture"));
			sp.setDbsystem(request.getParameter("dbsystem"));
			sp.setDevPlatform(request.getParameter("devPlatform"));
			String devSites = request.getParameter("devCountry");
			if(devSites.equals("China")) devSites += "-" + request.getParameter("devProvince");
			sp.setDevSites(devSites);
			sp.setDevTech(request.getParameter("devTechs"));
			sp.setDevTool(request.getParameter("devTools"));
			sp.setLanguage(request.getParameter("language"));
			sp.setLifeCyclePhase(request.getParameter("life_cycle"));
			sp.setOs(request.getParameter("os"));
			sp.setSizingTech(request.getParameter("sizingTech"));
			sp.setToalDefect(request.getParameter("totalDefect"));
			sp.setTotalEffort(request.getParameter("totalEffort"));
			sp.setTotalMember(request.getParameter("totalMember"));
			sp.setTotalSize(request.getParameter("totalSize") + "\t" + request.getParameter("sizeMetric"));
			sp.setTotalTime(request.getParameter("totalTime"));
			sp.setDesignTech(request.getParameter("designTech"));
			
			data.setScope(sp);
			request.setAttribute("stepFlag", 1);
		} /*else {
			request.setAttribute("stepFlag", 2);
		}*/
		RequestDispatcher dispatcher = request.getRequestDispatcher("submit.jsp"); 
		try {
			dispatcher .forward(request, response);
		} catch (ServletException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
	}
}
