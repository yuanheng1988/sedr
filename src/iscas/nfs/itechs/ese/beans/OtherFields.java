package iscas.nfs.itechs.ese.beans;

public class OtherFields {
	public static final String RESEARCH_AREA_SEPARATOR = ",";
	
	private String[] researchAreas = {};
	private float integrity = 0.0F;
	
	public String getResearchAreas4SQL() {
		if(researchAreas.length == 0) return "";
		StringBuffer sb = new StringBuffer();
		
		int i = 0;
		for(; i < researchAreas.length - 1; i++)
			sb.append(researchAreas[i] + RESEARCH_AREA_SEPARATOR);
		sb.append(researchAreas[i]);
		
		return sb.toString();
	}
	
	public String[] getResearchAreas() {
		return researchAreas;
	}
	public void setResearchAreas(String[] researchAreas) {
		this.researchAreas = researchAreas;
	}
	public float getIntegrity() {
		return integrity;
	}
	public void setIntegrity(float integrity) {
		this.integrity = integrity;
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("<tr><td width='20%' align='right'>Research Area</td><td width='80%' align='left'>");
		for(int i = 0; i < researchAreas.length; i++) 
			sb.append(researchAreas[i] + "<br>");
		sb.append("</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Integrity</td><td width='80%' align='left'>");
		sb.append(this.integrity * 100 + " %</td></tr>");
		
		return sb.toString();
	}
}
