package iscas.nfs.itechs.ese.beans;

public class SingleOrganization extends Scope {
	private String orgType = "";
	private String baType = "";
	private String[] appTypes = {};
	
	public String getOrgType() {
		return orgType;
	}
	
	public void setOrgType(String orgType) {
		this.orgType = orgType;
	}
	
	public String getBaType() {
		return baType;
	}
	
	public void setBaType(String baType) {
		this.baType = baType;
	}
	
	public String[] getAppTypes() {
		return appTypes;
	}
	
	public void setAppTypes(String[] appTypes) {
		this.appTypes = appTypes;
	}
	
	public String getAppTypes4SQL() {
		StringBuffer sb = new StringBuffer();
		
		int i = 0;
		for(; i < appTypes.length - 1; i++)
			sb.append(appTypes[i] + "~");
		sb.append(appTypes[i]);
		
		return sb.toString();
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("<tr><td width='20%' align='right'>Organization Type</td><td width='80%' align='left'>");
		sb.append(this.orgType + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Business Area Type</td><td width='80%' align='left'>");
		sb.append(this.baType + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Application Type</td><td width='80%' align='left'>");
		for(int i = 0; i < this.appTypes.length - 1; i++) 
			sb.append(appTypes[i] + "<br>");
		sb.append(appTypes[appTypes.length - 1]);
		sb.append("</td></tr>");
		
		return sb.toString();
	}
}
