package iscas.nfs.itechs.ese.utils;

import iscas.nfs.itechs.ese.beans.UploadedFile;
import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.db.DBOperation;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class UpDownfileGetter {
	private static Connection con = null;
	
	public static List<UploadedFile> getupfile(User user)throws SQLException{
		List<UploadedFile> files = new ArrayList<UploadedFile>();
		
		try {
			con = DBOperation.getConnection();
		} catch (Exception e) {
			e.printStackTrace();
		}
		PreparedStatement ps = con.prepareStatement("SELECT * FROM file_desc WHERE id IN (SELECT DF.file_id FROM data_file AS DF,data_profile AS DP,users WHERE DF.data_id=DP.data_id AND DP.user_id=users.id AND users.id=?)");
		ps.setInt(1,user.getId());
		ResultSet rs = ps.executeQuery();
		
		int index = 0;
		while(rs.next()){
			files.add(new UploadedFile());
			Utilities.manufactFile(rs, files.get(index));
			index++;
		}
		
		return files;
	}
	
	public static List<UploadedFile> getdownfile(User user)throws SQLException{
		List<UploadedFile> files = new ArrayList<UploadedFile>();
		
		try {
			con = DBOperation.getConnection();
		} catch (Exception e) {
			e.printStackTrace();
		}
		PreparedStatement ps = con.prepareStatement("SELECT * FROM file_desc WHERE id IN (SELECT file_id FROM download_record WHERE user_id=?)");
		ps.setInt(1,user.getId());
		ResultSet rs = ps.executeQuery();
		
		int index = 0;
		while(rs.next()){
			files.add( new UploadedFile());
			Utilities.manufactFile(rs, files.get(index));
			index++;
		}
		
		return files;
	}

}
