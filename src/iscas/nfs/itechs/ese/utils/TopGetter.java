package iscas.nfs.itechs.ese.utils;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Calendar;
import java.util.Date;

import iscas.nfs.itechs.ese.beans.UploadedFile;
import iscas.nfs.itechs.ese.db.DBOperation;

public class TopGetter {
	public static final int YEAR = 1;
	public static final int MONTH = 2;
	public static final int WEEK = 3;
	
	public static final int DOWNLOAD = 1;
	public static final int CONTRIBUTOR = 2;
	
	private static final long ONE_YEAR = 31536000000L;
	private static final long ONE_MONTH = 2592000000L;
	private static final long ONE_WEEK = 604800000L;
	
	private static Date now = null;
	
	private static Connection con = null;
	
	public static Object[] getTop(int typeFlag, int timeFlag, Object[] objects) {
		now = Calendar.getInstance().getTime();
		try {
			con = DBOperation.getConnection();
			
			if(typeFlag == DOWNLOAD) {
				objects = new UploadedFile[Constants.TOP_DOWNLOADS];
				objects = getTopFile(timeFlag, (UploadedFile[])objects);
			}
			else if(typeFlag == CONTRIBUTOR) {
				objects = new String[Constants.TOP_CONTRIBUTORS];
				objects = getTopUser(timeFlag, (String[])objects);
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return objects;
	}
	
	private static UploadedFile[] getTopFile(int timeFlag, UploadedFile[] files) throws SQLException {
		Date prior = null;
		if(timeFlag == YEAR) prior = new Date(now.getTime() - ONE_YEAR);
		else if(timeFlag == MONTH) prior = new Date(now.getTime() - ONE_MONTH);
		else if(timeFlag == WEEK) prior = new Date(now.getTime() - ONE_WEEK);
		else ;
		
		PreparedStatement ps = con.prepareStatement("SELECT * FROM file_desc WHERE id IN (SELECT S.file_id FROM " +
			"(SELECT COUNT(T.id) AS CT, T.file_id FROM (SELECT * FROM download_record WHERE download_time > ?) " +
			"AS T GROUP BY T.file_id ORDER BY CT DESC) AS S) ORDER BY download_count DESC");
		ps.setString(1, Constants.DATE_FORMAT.format(prior.getTime()));
		ResultSet rs = ps.executeQuery();
		
		int index = 0;
		while(rs.next() && index < files.length) {
			files[index] = new UploadedFile();
			Utilities.manufactFile(rs, files[index]);
			index++;
		}
		
		return files;
	}
	
	private static String[] getTopUser(int timeFlag, String[] users) throws SQLException {
		Date prior = null;
		if(timeFlag == YEAR) prior = new Date(now.getTime() - ONE_YEAR);
		else if(timeFlag == MONTH) prior = new Date(now.getTime() - ONE_MONTH);
		else if(timeFlag == WEEK) prior = new Date(now.getTime() - ONE_WEEK);
		else ;
		
		PreparedStatement ps = con.prepareStatement("SELECT users.username FROM data_file,data_profile,users WHERE data_file.data_id = data_profile.data_id And data_profile.user_id=users.id AND file_id IN (SELECT id FROM file_desc WHERE creation_time > ?) GROUP BY users.username ORDER BY COUNT(file_id) DESC");
		ps.setString(1, Constants.DATE_FORMAT.format(prior.getTime()));
		ResultSet rs = ps.executeQuery();
		
		int i = 0;
		while(i < users.length && rs.next()) {
			users[i] = rs.getString(1);
			i++;
		}
		
		return users;
	}
}
