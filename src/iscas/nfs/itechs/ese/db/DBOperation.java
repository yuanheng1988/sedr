package iscas.nfs.itechs.ese.db;

import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.convert.PropertiesManager;
import iscas.nfs.itechs.ese.utils.Utilities;

import java.sql.*;

/**
 * 
 * Modified by Xihao XIE on 2010-11-08
 * 
 * @author Futbal
 *
 */

public class DBOperation {
	private static Connection con = null;
	private static String DB_URL = PropertiesManager.getApp("Constants.DBURL");
	
	public static Connection getConnection() throws Exception {
		if(con != null && !con.isClosed()) return con;
		
		String driver = "com.mysql.jdbc.Driver";
		Class.forName(driver);
        con = DriverManager.getConnection(DB_URL + "hehe");
        return con;
	}
	
	public static User getUser(String name, String pwd) throws Exception {
		if(con == null) con = getConnection();
		ResultSet rs = con.createStatement().executeQuery("SELECT * FROM users WHERE username='" 
				+ name + "' and password='" + pwd + "'");
		if(rs.next())
			return Utilities.generateUser(rs, new User());
		else return null;
	}
	
	
	
	/*
    public static Connection getConnection() throws Exception{
    	Connection dbConnection = null;
        String driver = "org.gjt.mm.mysql.Driver";
        Class.forName(driver);
        dbConnection = DriverManager.getConnection("jdbc:mysql://localhost:3306/ser?user=root&password=root&useUnicode=true&characterEncoding=utf8");
        return dbConnection;
    }
    */
}
