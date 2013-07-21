package iscas.nfs.itechs.ese.beans;

public class CustomBean {
	public static final String RESEARCH_AREA_SEPARATOR = ",";
	
	private int id = 0;
	private String customBa = "";
	private String customLcp = "";
	private String customEmail = "";
	private String[] researchAreas = {};
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public void setResearchAreas(String[] researchAreas) {
		this.researchAreas = researchAreas;
	}
	public String[] getResearchAreas() {
		return researchAreas;
	}
	public void setCustomBa(String customBa) {
		this.customBa = customBa;
	}
	public String getCustomBa() {
		return customBa;
	}
	public void setCustomLcp(String customLcp) {
		this.customLcp = customLcp;
	}
	public String getCustomLcp() {
		return customLcp;
	}
	
	public String getResearchAreas4SQL() {
		if(researchAreas.length == 0) return "";
		StringBuffer sb = new StringBuffer();
		
		int i = 0;
		for(; i < researchAreas.length - 1; i++)
			sb.append(researchAreas[i] + RESEARCH_AREA_SEPARATOR);
		sb.append(researchAreas[i]);
		
		return sb.toString();
	}
	public void setCustomEmail(String customEmail) {
		this.customEmail = customEmail;
	}
	public String getCustomEmail() {
		return customEmail;
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("<tr><td width='20%' align='right'>Custom Research Area:</td><td width='80%' align='left'>");
		for(int i = 0; i < researchAreas.length; i++) 
			sb.append(researchAreas[i] + "<br>");
		sb.append("</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Custom Business Area:</td><td width='80%' align='left'>");
		sb.append(this.customBa + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Custom Life Cycle Phase:</td><td width='80%' align='left'>");
		sb.append(this.customLcp + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Custom Email:</td><td width='80%' align='left'>");
		sb.append(this.customEmail + "</td></tr>");
		
		return sb.toString();
	}

}
