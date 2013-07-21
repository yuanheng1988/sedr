package iscas.nfs.itechs.ese.beans;

public class SingleProject extends Scope {
	private String lifeCyclePhase = "";
	private String architecture = "";
	private String sizingTech = "";
	private String designTech = "";
	public String getDesignTech() {
		return designTech;
	}
	public void setDesignTech(String designTech) {
		this.designTech = designTech;
	}

	private String totalEffort = "";
	private String totalTime = "";
	private String totalMember = "";
	private String totalSize = "";
	private String toalDefect = "";
	private String language = "";
	private String os = "";
	private String dbsystem = "";
	private String devSites = "";
	private String devTech = "";
	private String devTool = "";
	private String devPlatform = "";
	
	public String getLifeCyclePhase() {
		return lifeCyclePhase;
	}
	public void setLifeCyclePhase(String lifeCyclePhase) {
		this.lifeCyclePhase = lifeCyclePhase;
	}
	public String getArchitecture() {
		return architecture;
	}
	public void setArchitecture(String architecture) {
		this.architecture = architecture;
	}

	public String getSizingTech() {
		return sizingTech;
	}
	public void setSizingTech(String sizingTech) {
		this.sizingTech = sizingTech;
	}
	public String getTotalEffort() {
		return totalEffort;
	}
	public void setTotalEffort(String totalEffor) {
		this.totalEffort = totalEffor;
	}
	public String getTotalTime() {
		return totalTime;
	}
	public void setTotalTime(String totalTime) {
		this.totalTime = totalTime;
	}
	public String getTotalMember() {
		return totalMember;
	}
	public void setTotalMember(String totalMember) {
		this.totalMember = totalMember;
	}
	public String getTotalSize() {
		return totalSize;
	}
	public void setTotalSize(String totalSize) {
		this.totalSize = totalSize;
	}
	public String getToalDefect() {
		return toalDefect;
	}
	public void setToalDefect(String toalDefect) {
		this.toalDefect = toalDefect;
	}
	public String getLanguage() {
		return language;
	}
	public void setLanguage(String language) {
		this.language = language;
	}
	
	public String getOs() {
		return os;
	}
	public void setOs(String os) {
		this.os = os;
	}
	public String getDbsystem() {
		return dbsystem;
	}
	public void setDbsystem(String dbsystem) {
		this.dbsystem = dbsystem;
	}
	public String getDevSites(){
		return devSites;
	}
	public void setDevSites(String devSites) {
		this.devSites = devSites;
	}
	public String getDevTech() {
		return devTech;
	}
	public void setDevTech(String devTech) {
		this.devTech = devTech;
	}
	public String getDevTool() {
		return devTool;
	}
	public void setDevTool(String devTool) {
		this.devTool = devTool;
	}
	public String getDevPlatform() {
		return devPlatform;
	}
	public void setDevPlatform(String devPlatform) {
		this.devPlatform = devPlatform;
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("<tr><td width='20%' align='right'>Life Cycle Phase</td><td width='80%' align='left'>");
		sb.append(this.lifeCyclePhase + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Architecture</td><td width='80%' align='left'>");
		sb.append(this.architecture + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Design Technique</td><td width='80%' align='left'>");
		sb.append(this.designTech + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Sizing Techniques</td><td width='80%' align='left'>");
		sb.append(this.sizingTech + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Total Effort</td><td width='80%' align='left'>");
		sb.append(this.totalEffort + "&nbsp;&nbsp;Person-Hours</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Total Time</td><td width='80%' align='left'>");
		sb.append(this.totalTime + "&nbsp;&nbsp;Days</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Total Member</td><td width='80%' align='left'>");
		sb.append(this.totalMember + "&nbsp;&nbsp;Persons</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Total Size</td><td width='80%' align='left'>");
		sb.append(this.totalSize + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Total Defect</td><td width='80%' align='left'>");
		sb.append(this.toalDefect + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Primary Programming Language</td><td width='80%' align='left'>");
		sb.append(this.language + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Operating System</td><td width='80%' align='left'>");
		sb.append(this.os + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Database System</td><td width='80%' align='left'>");
		sb.append(this.dbsystem + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Development Sites</td><td width='80%' align='left'>");
		sb.append(this.devSites + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Development Techniques</td><td width='80%' align='left'>");
		sb.append(this.devTech + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Development Tools</td><td width='80%' align='left'>");
		sb.append(this.devTool + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Developemnt Platform</td><td width='80%' align='left'>");
		sb.append(this.devPlatform + "</td></tr>");
		
		return sb.toString();
	}
}
