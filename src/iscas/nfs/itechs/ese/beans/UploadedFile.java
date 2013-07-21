package iscas.nfs.itechs.ese.beans;

public class UploadedFile {
	private int id = 0;
	private String description = "";
	private String format = "";
	private String currentName = "";
	private String originName = "";
	private long size = 0L;
	private String creationTime = "";
	private String updateTime = "";
	private int downloadCount = 0;
	
	public int getDownloadCount() {
		return downloadCount;
	}
	public void setDownloadCount(int downloadCount) {
		this.downloadCount = downloadCount;
	}
	public String getDescription() {
		return description;
	}
	public void setDescription(String description) {
		this.description = description;
	}
	
	public String getFormat() {
		return format;
	}
	public void setFormat(String format) {
		this.format = format;
	}
	
	public String toString() {
		StringBuffer sb = new StringBuffer();
		
		sb.append("<tr><td width='20%' align='right'>Descriptive Name:</td><td width='80%' align='left'>");
		sb.append(this.description + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>File Name:</td><td width='80%' align='left'>");
		sb.append(this.originName + "</td></tr>");
		
		sb.append("<tr><td width='20%' align='right'>File Format:</td><td width='80%' align='left'>");
		sb.append(this.format + "</td></tr>");
		
		return sb.toString();
	}
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getCurrentName() {
		return currentName;
	}
	public void setCurrentName(String currentName) {
		this.currentName = currentName;
	}
	public String getOriginName() {
		return originName;
	}
	public void setOriginName(String originName) {
		this.originName = originName;
	}
	public long getSize() {
		return size;
	}
	public void setSize(long size) {
		this.size = size;
	}
	public String getCreationTime() {
		return creationTime;
	}
	public void setCreationTime(String creationTime) {
		this.creationTime = creationTime;
	}
	public String getUpdateTime() {
		return updateTime;
	}
	public void setUpdateTime(String updateTime) {
		this.updateTime = updateTime;
	}
}
