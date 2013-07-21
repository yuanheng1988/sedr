package iscas.nfs.itechs.ese.beans;

public class DataBean {
	private String contributor = "";
	private String subjectArea = "";
	private String generatedPhase = "";
	private String supportAnalysis = "";
	private Scope scope = null;
	private OtherFields others = null;
	private UploadedFile[] files = null;
	
	public String getSupportAnalysis() {
		return supportAnalysis;
	}

	public void setSupportAnalysis(String supportAnalysis) {
		this.supportAnalysis = supportAnalysis;
	}

	public String getContributor() {
		return contributor;
	}
	
	public void setContributor(String contributor) {
		this.contributor = contributor;
	}
	
	public String getSubjectArea() {
		return subjectArea;
	}
	
	public void setSubjectArea(String subjectArea) {
		this.subjectArea = subjectArea;
	}
	
	public String getGeneratedPhase() {
		return generatedPhase;
	}
	
	public void setGeneratedPhase(String generatedPhase) {
		this.generatedPhase = generatedPhase;
	}
	
	public Scope getScope() {
		return scope;
	}
	
	public void setScope(Scope scope) {
		this.scope = scope;
	}
	
	public OtherFields getOthers() {
		return others;
	}
	
	public void setOthers(OtherFields others) {
		this.others = others;
	}
	
	public UploadedFile[] getFiles() {
		return files;
	}
	
	public void setFiles(UploadedFile[] files) {
		this.files = files;
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("<tr><td width='20%' align='right'>Contributor</td><td width='80%' align='left'>");
		sb.append(this.contributor + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Subject Area</td><td width='80%' align='left'>");
		sb.append(this.subjectArea + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Generated Phase</td><td width='80%' align='left'>");
		sb.append(this.generatedPhase + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>Support Analysis</td><td width='80%' align='left'>");
		sb.append(this.supportAnalysis + "</td></tr>");
		
		sb.append(this.others.toString());
		if(scope != null)
			sb.append(this.scope.toString());
		
		sb.append("<tr><td colspan='2' align='left'><strong>Uploaded Files</strong></td><tr/>");
		
		if(files != null)
			for(int i = 0; i < files.length && files[i] != null; i++) {
				sb.append("<tr><td colspan='2'>File " + (i + 1) + "</td></tr>");
				sb.append(files[i].toString());
			}
		
		return sb.toString();
	}
	
	@SuppressWarnings("null")
	public String[] getFileCurrentNames(){
		String[] currentNames = new String[files.length];
		for(int i = 0; i < files.length && files[i] != null; i++) {
			currentNames[i] = new String();
			currentNames[i] = files[i].getCurrentName();
//			System.out.println(i+currentNames[i]);
		}
//		System.out.println("+++++++++++++++++++++++++");
		return currentNames;	
	}
	
	@SuppressWarnings("null")
	public String[] getFileOriginalNames(){
		String[] originalNames = new String[files.length];
		for(int i = 0; i < files.length && files[i] != null; i++) {
			originalNames[i] = new String();
			originalNames[i] = files[i].getOriginName();
//			System.out.println(i+originalNames[i]);
		}
		return originalNames;	
	}
	
	public String generateSQLString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("'" + others.getIntegrity() + "', '" + supportAnalysis + "', '" + 
				others.getResearchAreas4SQL() + "', ");
		if(scope instanceof SingleOrganization) {
			sb.append("'" + scope.getScopeScale() + "', '', '', '', '', '', '', '', '', '', '', '', '', " +
					"'', '', '', '', '',");
			SingleOrganization so = (SingleOrganization)scope;
			sb.append("'" + so.getOrgType() + "', '" + so.getBaType() + "', '" + so.getAppTypes4SQL() + "'");
		} else if(scope instanceof SingleProject) {
			SingleProject sp = (SingleProject)scope;
			sb.append("'', '" + scope.getScopeScale() + "', '" + sp.getLifeCyclePhase() + "', '" +
					sp.getArchitecture() + "', '" + sp.getDesignTech() + "', '" + sp.getSizingTech() + "', '"
					+ sp.getTotalEffort() + "', '" + sp.getTotalTime() + "', '" + sp.getTotalMember() + "', '" + 
					sp.getTotalSize() + "', '" + sp.getToalDefect() + "', '" + sp.getLanguage() + "', '" + 
					sp.getOs() + "', '" + sp.getDbsystem() + "', '" + sp.getDevSites() + "', '" + 
					sp.getDevTech() + "', '" + sp.getDevTool() + "', '" + sp.getDevPlatform() + "'," +
					" '', '', ''");
		} else if(scope.getScope().equals("Organization")){	//Multiple Organization
			sb.append("'" + scope.getScopeScale() + "', '', '', '', '', '', '', '', '', '', '', '', '', " +
					"'', '', '', '', '', '', '', ''");
		} else {	//Multiple Project
			sb.append("'', '" + scope.getScopeScale() + "', '', '', '', '', '', '', '', '', '', '', " +
					"'', '', '', '', '', '', '', '', ''");
		}
		
		return sb.toString();
	}
}
