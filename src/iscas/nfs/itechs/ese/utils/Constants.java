package iscas.nfs.itechs.ese.utils;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.DateFormat;
import java.text.SimpleDateFormat;

import iscas.nfs.itechs.ese.convert.PropertiesManager;
import iscas.nfs.itechs.ese.db.DBOperation;

public class Constants {
	/**
	 * Note: FILE_STORAGE should be modified to specific location on the server
	 */
	public static final String FILE_STORAGE = PropertiesManager.getApp("Constants.FILE_STORAGE");
	public static final String ARFF_TEMP_STORAGE = PropertiesManager.getApp("Constants.ARFF_TEMP_STORAGE");
	public static final long FILE_SIZE = 4000000L;
	public static final String FILE_FORMATES = ".arrf";
	public static final DateFormat DATE_FORMAT = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	public static final int FILES_AMOUNT_PER_PAGE = 5;
	public static final String MAIL_SENDER_ADDRESS = "ser_itechs@nfs.iscas.ac.cn";
	public static final String MAIL_HOST_ADDRESS = "nfs.iscas.ac.cn";
	public static final String MAIL_SENDER = "ser_iTechs";
	public static final String MAIL_DOWNLOAD_HOST = "http://192.192.192.33:8081/sedr";
	public static final int TOP_DOWNLOADS = 10;
	public static final int TOP_CONTRIBUTORS = 10;
	
	
	public static Authority[] authorities = getAuthorites();
	
	public static Authority[] getAuthorites() {
		authorities = new Authority[3];
		try {
			Statement stmt = DBOperation.getConnection().createStatement();
			ResultSet rs = stmt.executeQuery("select * from authority_info order by id");
			int i = 0;
			while(rs.next() && i < authorities.length) {
				generateAuthority(rs, i);
				i++;
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return authorities;
	}
	
	private static void generateAuthority(ResultSet rs, int index) throws SQLException {
		if(authorities[index] == null) authorities[index] = new Authority();
		Authority ath = authorities[index];
		ath.setId(rs.getInt(1));
		ath.setLevel(rs.getString(2));
		ath.setUpload(rs.getBoolean(3));
		ath.setDownload(rs.getBoolean(4));
		ath.setUpdate_all(rs.getBoolean(5));
		ath.setUpdate_own(rs.getBoolean(6));
		ath.setDelete_all(rs.getBoolean(7));
		ath.setDelete_own(rs.getBoolean(8));
	}
}

class Authority {
	private int id = 0;
	private String level = "other";
	private boolean upload = false;
	private boolean download = false;
	private boolean update_all = false;
	private boolean update_own = false;
	private boolean delete_all = false;
	private boolean delete_own = false;
	
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getLevel() {
		return level;
	}
	public void setLevel(String level) {
		this.level = level;
	}
	public boolean isUpload() {
		return upload;
	}
	
	public void setUpload(boolean upload) {
		this.upload = upload;
	}
	public boolean isDownload() {
		return download;
	}
	public void setDownload(boolean download) {
		this.download = download;
	}
	public boolean isUpdate_all() {
		return update_all;
	}
	public void setUpdate_all(boolean updateAll) {
		update_all = updateAll;
	}
	public boolean isUpdate_own() {
		return update_own;
	}
	public void setUpdate_own(boolean updateOwn) {
		update_own = updateOwn;
	}
	public boolean isDelete_all() {
		return delete_all;
	}
	public void setDelete_all(boolean deleteAll) {
		delete_all = deleteAll;
	}
	public boolean isDelete_own() {
		return delete_own;
	}
	public void setDelete_own(boolean deleteOwn) {
		delete_own = deleteOwn;
	}
}