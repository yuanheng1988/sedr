package iscas.nfs.itechs.ese.utils;

import java.io.UnsupportedEncodingException;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashSet;
import java.util.Iterator;
import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import javax.servlet.http.HttpSession;

import com.jspsmart.upload.File;
import com.jspsmart.upload.SmartUpload;

import iscas.nfs.itechs.ese.beans.CustomBean;
import iscas.nfs.itechs.ese.beans.DataBean;
import iscas.nfs.itechs.ese.beans.OtherFields;
import iscas.nfs.itechs.ese.beans.Scope;
import iscas.nfs.itechs.ese.beans.SingleOrganization;
import iscas.nfs.itechs.ese.beans.SingleProject;
import iscas.nfs.itechs.ese.beans.UploadedFile;
import iscas.nfs.itechs.ese.beans.User;
import iscas.nfs.itechs.ese.db.DBOperation;

public class Utilities {
	private static Random random = new Random();
	private static LinkedList<UploadedFile> files = null;
	
	public static void generateFile(File file, UploadedFile upload) throws UnsupportedEncodingException {
		upload.setDownloadCount(0);
		upload.setOriginName(new String(file.getFileName().getBytes("gbk"), "gb2312"));
		upload.setFormat(file.getFileExt());
		upload.setSize(file.getSize());
		
		Calendar calendar = Calendar.getInstance();
		upload.setUpdateTime(Constants.DATE_FORMAT.format(calendar.getTime()));
		upload.setCreationTime(Constants.DATE_FORMAT.format(calendar.getTime()));
		String currentName = String.valueOf(calendar.getTimeInMillis()) + "_" + random.nextLong();
		upload.setCurrentName(currentName + "." + file.getFileExt());
	}
	
//	@SuppressWarnings("unchecked")
	public static void generateCustomUsers4file(List<Integer> cus_ids,int data_id) throws SQLException,Exception{			
		Connection con = DBOperation.getConnection();
		
		PreparedStatement stmt = con.prepareStatement("SELECT research_area,life_cycle_phase,business_area FROM data_profile where data_id=?");
		stmt.setInt(1, data_id);
		
		ResultSet rs1 = stmt.executeQuery();
		rs1.next();
		
		String researchArea = rs1.getString(1);
		String data_lcp = rs1.getString(2);
		String data_ba = rs1.getString(3);
		String[] data_ra = researchArea.split(OtherFields.RESEARCH_AREA_SEPARATOR);
		System.out.println(rs1.getString(1)+"||"+rs1.getString(2)+"||"+rs1.getString(3));
		
		
		/*Connection con = DBOperation.getConnection();
		PreparedStatement stmt = null;
		while(rs.next()) {
			stmt = con.prepareStatement("SELECT research_area FROM data_profile WHERE data_id IN " +
					"(SELECT data_id FROM data_file WHERE file_id=?)");
			stmt.setInt(1, rs.getInt(1));
			ResultSet result = stmt.executeQuery();
			if(result.next() && isResearchAreaMatch(researchArea, result.getString(1))) {
				UploadedFile file = new UploadedFile();
				manufactFile(rs, file);
				files.add(file);
			}
		}*/
//		System.out.println(!custom_ba.equals(""));
//		System.out.println(!custom_lcp.equals(""));
//		System.out.println(custom_ba);
//		System.out.println(custom_lcp);
//		System.out.println("four line end;");
		if(!data_ba.equals("")){
			stmt = con.prepareStatement("SELECT * FROM customization WHERE custom_ba=? OR custom_ba='all'");
			stmt.setString(1, data_ba);
		}
		else if(!data_lcp.equals("")){
//			System.out.println("enter lcp");
			stmt = con.prepareStatement("SELECT * FROM customization WHERE custom_lcp=? OR custom_lcp='all'");
			stmt.setString(1, data_lcp);
		}
		else{
			stmt = con.prepareStatement("SELECT * FROM customization WHERE custom_ba='' AND custom_lcp=''");
		}
		
		ResultSet result = stmt.executeQuery();
//		int k = 0;
//		result.next();
//		System.out.println(result.getString(3));
//		System.out.println(result.getString(4));
//		System.out.println(data_ba);
//		System.out.println(result.getString(4).equals("data_ba"));
		while(result.next()){
			System.out.println(result.getString(1)+"||"+result.getString(2)+"||"+result.getString(3)+"||"+result.getString(4)+"||"+result.getString(5));
//			if(!result.getString(4).equals("") && !result.getString(4).equals(data_ba) && !result.getString(4).equals("all"))
//				continue;
//			else if(!result.getString(5).equals("") && !result.getString(5).equals(data_lcp) && !result.getString(4).equals("all"))
//				continue;
//			else{
//			System.out.println("while in;");
				String[] cus_ra = result.getString(3).split(OtherFields.RESEARCH_AREA_SEPARATOR);
				int i,j;
				for(i = 0;i < cus_ra.length;i++){
					for(j = 0;j< data_ra.length;j++){
						if(isResearchAreaMatch(data_ra[j],cus_ra[i])){
							cus_ids.add(Integer.parseInt(result.getString(1)));
//							k++;
							break;
						}
					}
					if(j != data_ra.length)
						break;
				}
//			System.out.println("while out;");
//			}
		}
		for(int l=0;l<cus_ids.size();l++){
			System.out.println("enter user list");
//			if(cus_ids[l]==0)
//				break;
			System.out.println(cus_ids.get(l));
		}
		System.out.println("===========================");
		
		
		
/*			for(int i=0;i < data_ra.length;i++){
				if(result.getString(3).contains(data_ra[i])){
					user_ids.add(result.getInt(2));
					break;
				}
			}
		}
		HashSet<String> hashSet = new HashSet<String>(user_ids);

	    unique_user_ids = new ArrayList<String>(hashSet);
	    
		for(@SuppressWarnings("unused") Object item:unique_user_ids){
			System.out.println(unique_user_ids);
		}
		System.out.println("end;");
*/		
		
	}
	
	public static void saveUpdateFile(File file,int fileId) throws SQLException,Exception{
		Connection con = DBOperation.getConnection();
		
		Calendar calendar = Calendar.getInstance();
		String currentName = String.valueOf(calendar.getTimeInMillis()) + "_" + random.nextLong()+ "." + file.getFileExt();
		String updateTime = Constants.DATE_FORMAT.format(calendar.getTime());
		String originName = new String(file.getFileName().getBytes("gbk"), "gb2312");
		String format = file.getFileExt();
		long size = file.getSize();
		
		file.saveAs(Constants.FILE_STORAGE + currentName, SmartUpload.SAVE_PHYSICAL);
		
		PreparedStatement stmt = con.prepareStatement("update file_desc set original_filename=?,file_size=?,file_format=?,current_filename=?,update_time=? WHERE id=?");
		stmt.setString(1, originName);
		stmt.setLong(2, size);
		stmt.setString(3, format);
		stmt.setString(4, currentName);
		stmt.setString(5, updateTime);
		stmt.setInt(6, fileId);
		
		stmt.execute();	
	}
	
	private static int getMaxID(String tableName, String columnName, Connection con) throws SQLException {
		PreparedStatement stmt = con.prepareStatement("select max(" + columnName + ") from " + tableName);
		ResultSet rs = stmt.executeQuery();
		if(rs.next()) return rs.getInt(1);
		
		return 0;
	}
	
	private static int saveFile(UploadedFile[] files, Connection con) throws SQLException {
		int maxFileId;
		int maxDataId = getMaxID("data_profile", "data_id", con) + 1;
		
		PreparedStatement stmt;
		UploadedFile file;
		for(int i = 0; i < files.length; i++) {
			file = files[i];
			stmt = con.prepareStatement("insert into file_desc(original_filename, file_size, file_format, " +
					"current_filename, creation_time, update_time, download_count, description) " +
					"VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
			stmt.setString(1, file.getOriginName());
			stmt.setLong(2, file.getSize());
			stmt.setString(3, file.getFormat());
			stmt.setString(4, file.getCurrentName());
			stmt.setString(5, file.getCreationTime());
			stmt.setString(6, file.getUpdateTime());
			stmt.setInt(7, file.getDownloadCount());
			stmt.setString(8, file.getDescription());
			
			stmt.execute();
			
			maxFileId = getMaxID("file_desc", "id", con);
			
			file.setId(maxFileId);
			
			stmt = con.prepareStatement("insert into data_file(data_id, file_id) VALUES(?, ?)");
			stmt.setInt(1, maxDataId);
			stmt.setInt(2, file.getId());
			stmt.execute();
			
		}
		
		return maxDataId;
	}
	
	public static void saveData(DataBean data, User user) throws Exception {
		Connection con = DBOperation.getConnection();
		int dataid = saveFile(data.getFiles(), con);
		
		Statement stmt = con.createStatement();
		stmt.execute("insert into data_profile(data_id, user_id,integrity,support_analysis,research_area," +
			"organization_scope,project_scope,life_cycle_phase,architecture,design_technique,size_technique, " +
			"effort, time, member, size, defect, program_language, operating_system, database_system, " +
			"development_site,development_technique, development_tool, development_platform,organization_type," +
			"business_area, application_type) VALUES('" + dataid + "', '" + user.getId() + "', " + 
			data.generateSQLString() + ")");
		
		//Generate Custom Users for this file
//		ArrayList<String> user_ids = new ArrayList<String>();
//		ArrayList<String> unique_user_ids = new ArrayList<String>();
		List<Integer> cus_ids = new ArrayList<Integer>();
//		for(int i=0;i<100;i++)
//			cus_ids[i]=0;
		
//		generateCustomUsers4file(unique_user_ids,user_ids,dataid);
		generateCustomUsers4file(cus_ids,dataid);
		//发送邮件？？
		PreparedStatement pre_stmt;
		ResultSet rs;
		int cus_user_id;
		String cus_email;
		String cus_info;
		for(int l=0;l<cus_ids.size();l++){
//			if(cus_ids.get(l)==0)
//				break;
			pre_stmt = con.prepareStatement("SELECT * FROM customization where id=?");
			pre_stmt.setInt(1, cus_ids.get(l));
			rs =pre_stmt.executeQuery();
			
			rs.next();
			cus_user_id = rs.getInt(2);
			cus_email = rs.getString(6);
			cus_info = "Research Area: " + (rs.getString(3).equals("")?"none":rs.getString(3)) + "; Business Area: " + (rs.getString(4).equals("")?"none":rs.getString(4)) + "; Life cycle phase: " + (rs.getString(5).equals("")?"none":rs.getString(5));
			
			System.out.println(cus_user_id+"||"+cus_email+"||"+cus_info);
			
			EmailSender.sendCustomizeEmail(data,dataid,cus_user_id,cus_info,cus_email);
		}
//		System.out.println("ahhho".contains("dahho"));
	}
	
	
	
	/**
	 * 
	 * @param sortFlag
	 * @param sortOrder
	 * @param keepFresh
	 * @return
	 * @throws Exception
	 */
	public static LinkedList<UploadedFile> browseFiles(String sortFlag, String sortOrder, boolean keepFresh,
			String researchArea) throws Exception {
		if(!keepFresh && files != null)
			return files;
		
		Connection con = DBOperation.getConnection();
		
		String flag = "download_count";
		if(sortFlag.equals("creation_time"))
			flag = "creation_time";
		else if(sortFlag.equals("description"))
			flag = "description";
		
		String order = "DESC";
		if(sortOrder.equals("ASC"))
			order = "ASC";
		
		StringBuffer sb = new StringBuffer();
		sb.append("SELECT * FROM file_desc ORDER BY ");
		sb.append(flag + " ");
		sb.append(order);
		
		ResultSet rs = con.createStatement().executeQuery(sb.toString());
		files = new LinkedList<UploadedFile>();
		if(researchArea == null || researchArea.equals("All")) filesList(rs);
		else filesListWithRA(rs, researchArea);
		
		return files;
	}
	
	private static void filesList(ResultSet rs) throws SQLException {
		while(rs.next()) {
			UploadedFile file = new UploadedFile();
			manufactFile(rs, file);
			files.add(file);
		}
	}
	
	public static UploadedFile manufactFile(ResultSet rs, UploadedFile file) throws SQLException {
		file = (file == null) ? new UploadedFile() : file;
		
		file.setId(rs.getInt(1));
		file.setOriginName(rs.getString(2));
		file.setSize(rs.getInt(3));
		file.setFormat(rs.getString(4));
		file.setCurrentName(rs.getString(5));
		file.setCreationTime(rs.getDate(6).toString());
		file.setUpdateTime(rs.getDate(7).toString());
		file.setDownloadCount(rs.getInt(8));
		file.setDescription(rs.getString(9));
		
		return file;
	}
	
	public static User generateUser(ResultSet rs, User user) throws SQLException {
		user = (user == null) ? new User() : user;
		
		user.setId(rs.getInt(1));
		user.setName(rs.getString(2));
		user.setPwd(rs.getString(3));
		user.setAuthID(rs.getInt(4));
		user.setAffiliation(rs.getString(5));
		user.setTitle(rs.getString(6));
		user.setEmail(rs.getString(7));
		user.setInterest(rs.getString(8));
		user.setCountry(rs.getString(9));
		
		return user;
	}
	
	private static void filesListWithRA(ResultSet rs, String researchArea) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = null;
		while(rs.next()) {
			stmt = con.prepareStatement("SELECT research_area FROM data_profile WHERE data_id IN " +
					"(SELECT data_id FROM data_file WHERE file_id=?)");
			stmt.setInt(1, rs.getInt(1));
			ResultSet result = stmt.executeQuery();
			if(result.next() && isResearchAreaMatch(researchArea, result.getString(1))) {
				UploadedFile file = new UploadedFile();
				manufactFile(rs, file);
				files.add(file);
			}
		}
	}
	
	private static boolean isResearchAreaMatch(String targetRA, String sourceRA) {
		String[] areas = sourceRA.split(OtherFields.RESEARCH_AREA_SEPARATOR);
		for(int i = 0; i < areas.length; i++)
			if(areas[i].equals(targetRA))
				return true;
		
		return false;
	}
	
	public static UploadedFile[] getFilesSubList(int beginPage, UploadedFile[] list) {
		int radix = beginPage * Constants.FILES_AMOUNT_PER_PAGE;
		if((beginPage + 1) * Constants.FILES_AMOUNT_PER_PAGE <= files.size())
			list = new UploadedFile[Constants.FILES_AMOUNT_PER_PAGE];
		else {
			list = new UploadedFile[files.size() - radix];
		}
		for(int i = 0; i < list.length; i++) {
			list[i] = files.get(radix + i);
		}
		return list;
	}
	
	/**
	 * 
	 * @param session
	 * @param fileID
	 * @return	true if file is marked, false if not
	 */
	@SuppressWarnings("unchecked")
	public static boolean markOrUnmarkFile(HttpSession session, int fileID) {
		HashSet<Integer> markedSet = (HashSet<Integer>)session.getAttribute("markedSet");
		if(markedSet.contains(fileID)) {
			markedSet.remove(fileID);
			return false;
		}
		markedSet.add(fileID);
		session.setAttribute("markedSet", markedSet);
		
		return true;
	}
	
	@SuppressWarnings("unchecked")
	public static UploadedFile[] getMarkedFiles(HttpSession session, UploadedFile[] markedFiles) {
		HashSet<Integer> markedSet = (HashSet<Integer>)session.getAttribute("markedSet");
		if(markedSet == null) return markedFiles;
		markedFiles = new UploadedFile[markedSet.size()];
		Iterator<Integer> it = markedSet.iterator();
		int i = 0;
		while(it.hasNext()) {
			markedFiles[i] = files.get(getFileWithID(it.next()));
			i++;
		}
		session.setAttribute("markedSet", markedSet);
		
		return markedFiles;
	}
	
	public static int getFileWithID(int id) {
		for(int i = 0; i < files.size(); i++) {
			UploadedFile file = files.get(i);
			if(file.getId() == id)
				return i;
		}
		
		return 0;
	}
	
	public static ArrayList<UploadedFile> getAllFiles(ArrayList<UploadedFile> fls) throws Exception {
		fls = (fls == null) ? new ArrayList<UploadedFile>() : fls;
		Connection con = DBOperation.getConnection();
		Statement stmt = con.createStatement();
		ResultSet rs = stmt.executeQuery("SELECT * FROM file_desc");
		while(rs.next()) {
			fls.add(manufactFile(rs, new UploadedFile()));
		}
		return fls;
	}
	
	public static boolean researchAreaMatched(String ra, UploadedFile file) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("SELECT research_area FROM data_profile WHERE " +
			"data_id IN (SELECT data_id FROM data_file WHERE file_id=?)");
		stmt.setInt(1, file.getId());
		ResultSet rs = stmt.executeQuery();
		if(rs.next() && rs.getString(1).toLowerCase().contains(ra.toLowerCase()))
			return true;
		return false;
	}
	
	public static boolean phaseMatched(String phase, UploadedFile file) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("SELECT life_cycle_phase FROM data_profile WHERE " +
			"data_id IN (SELECT data_id FROM data_file WHERE file_id=?)");
		stmt.setInt(1, file.getId());
		ResultSet rs = stmt.executeQuery();
		if(rs.next() && rs.getString(1).toLowerCase().contains(phase.toLowerCase()))
			return true;
		return false;
	}
	
	public static void registerUser(User user) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("INSERT INTO users " +
			"(username, password, auth_id, affiliation, title, email, interest, country)" +
			" VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		
		stmt.setString(1, user.getName());
		stmt.setString(2, user.getPwd());
		stmt.setInt(3, user.getAuthID());
		stmt.setString(4, user.getAffiliation());
		stmt.setString(5, user.getTitle());
		stmt.setString(6, user.getEmail());
		stmt.setString(7, user.getInterest());
		stmt.setString(8, user.getCountry());
		
		stmt.execute();
	}

	private static DataBean generateDataBean(ResultSet rs, DataBean bean,Connection con) throws SQLException {
		bean = (bean == null) ? new DataBean() : bean;
		bean.setContributor(rs.getString("username"));
		bean.setGeneratedPhase(rs.getString("life_cycle_phase"));
		
		OtherFields other = new OtherFields();
		String[] researchArea = rs.getString("research_area").split(",");
		other.setResearchAreas(researchArea);
		other.setIntegrity(rs.getFloat("integrity"));
		bean.setOthers(other);
		
		Scope scope = null;
		String pro = rs.getString("project_scope"), org = rs.getString("organization_scope");
		if(pro != null || !pro.equals("")) {
			if(pro.equals("Single")) {
				scope = new SingleProject();
				scope.setScope("Project");
				scope.setScopeScale("Single");
			} else {
				scope = new Scope();
				scope.setScope("Project");
				scope.setScopeScale("Multiple");
			}
		} else if(org != null || !org.equals("")){
			if(org.equals("Single")) {
				scope = new SingleOrganization();
				scope.setScope("Organization");
				scope.setScopeScale("Single");
			} else {
				scope = new Scope();
				scope.setScope("Organization");
				scope.setScopeScale("Multiple");
			}
		}
		
		SingleOrganization so = null;
		SingleProject sp = null;
		if(scope instanceof SingleOrganization) {
			so = (SingleOrganization)scope;
			
			so.setOrgType(rs.getString("organization_type"));
			so.setBaType(rs.getString("business_area"));
			so.setAppTypes(rs.getString("application_type").split(","));
			
			bean.setScope(so);
		} else if(scope instanceof SingleProject) {
			sp = (SingleProject)scope;
			
			sp.setArchitecture(rs.getString("architecture"));
			sp.setDbsystem(rs.getString("database_system"));
			sp.setDevPlatform(rs.getString("development_platform"));
			sp.setDevSites(rs.getString("development_site"));
			sp.setDevTech(rs.getString("development_technique"));
			sp.setDevTool(rs.getString("development_tool"));
			sp.setLanguage(rs.getString("program_language"));
			sp.setLifeCyclePhase(rs.getString("life_cycle_phase"));
			sp.setOs(rs.getString("operating_system"));
			sp.setSizingTech(rs.getString("size_technique"));
			sp.setToalDefect(rs.getString("defect"));
			sp.setTotalEffort(rs.getString("effort"));
			sp.setTotalMember(rs.getString("member"));
			sp.setTotalSize(rs.getString("size"));
			sp.setTotalTime(rs.getString("time"));
			sp.setDesignTech(rs.getString("design_technique"));
			
			bean.setScope(sp);
		}
		
//		bean.setSubjectArea(null);
		bean.setSupportAnalysis(rs.getString("support_analysis"));
		
		PreparedStatement stmt = con.prepareStatement("SELECT * FROM data_profile,data_file,file_desc WHERE data_profile.data_id=data_file.data_id and data_file.file_id=file_desc.id and data_profile.data_id=?");
		stmt.setInt(1, rs.getInt("data_id"));
		
		ResultSet result = stmt.executeQuery();
		int index = 0;
		List<UploadedFile> files = new ArrayList<UploadedFile>();
//		result.next();
//		System.out.println(result.getString("description"));
		while(result.next()){
			files.add(new UploadedFile());
			files.get(index).setDescription(result.getString("description"));
			files.get(index).setOriginName(result.getString("original_filename"));
			files.get(index).setFormat(result.getString("file_format"));
			index++;
		}
		UploadedFile[] newfiles = new UploadedFile[files.size()];
		files.toArray(newfiles);
		bean.setFiles(newfiles);
		
		return bean;
	}
	
/*	private static DataBean generateDataBean4Custom(ResultSet rs, DataBean bean,Connection con) throws SQLException {
		bean = (bean == null) ? new DataBean() : bean;
		bean.setContributor(rs.getString("username"));
//		bean.setGeneratedPhase(rs.getString("life_cycle_phase"));
		
		OtherFields other = new OtherFields();
		String[] researchArea = rs.getString("research_area").split(",");
		other.setResearchAreas(researchArea);
		other.setIntegrity(rs.getFloat("integrity"));
		bean.setOthers(other);
		
		Scope scope = null;
		String pro = rs.getString("project_scope"), org = rs.getString("organization_scope");
		if(pro != null && !pro.equals("")) {
			if(pro.equals("Single")) {
				scope = new SingleProject();
				scope.setScope("Project");
				scope.setScopeScale("Single");
			} else {
				scope = new Scope();
				scope.setScope("Project");
				scope.setScopeScale("Multiple");
			}
		} else if(org != null && !org.equals("")){
			if(org.equals("Single")) {
				scope = new SingleOrganization();
				scope.setScope("Organization");
				scope.setScopeScale("Single");
			} else {
				scope = new Scope();
				scope.setScope("Organization");
				scope.setScopeScale("Multiple");
			}
		}
		
		SingleOrganization so = null;
		SingleProject sp = null;
		if(scope instanceof SingleOrganization) {
			so = (SingleOrganization)scope;
			
			so.setOrgType(rs.getString("organization_type"));
			so.setBaType(rs.getString("business_area"));
			so.setAppTypes(rs.getString("application_type").split(","));
			
			bean.setScope(so);
		} else if(scope instanceof SingleProject) {
			sp = (SingleProject)scope;
			
			sp.setArchitecture(rs.getString("architecture"));
			sp.setDbsystem(rs.getString("database_system"));
			sp.setDevPlatform(rs.getString("development_platform"));
			sp.setDevSites(rs.getString("development_site"));
			sp.setDevTech(rs.getString("development_technique"));
			sp.setDevTool(rs.getString("development_tool"));
			sp.setLanguage(rs.getString("program_language"));
			sp.setLifeCyclePhase(rs.getString("life_cycle_phase"));
			sp.setOs(rs.getString("operating_system"));
			sp.setSizingTech(rs.getString("size_technique"));
			sp.setToalDefect(rs.getString("defect"));
			sp.setTotalEffort(rs.getString("effort"));
			sp.setTotalMember(rs.getString("member"));
			sp.setTotalSize(rs.getString("size"));
			sp.setTotalTime(rs.getString("time"));
			sp.setDesignTech(rs.getString("design_technique"));
			
			bean.setScope(sp);
		}	
			
//		bean.setSubjectArea(null);
		bean.setSupportAnalysis(rs.getString("support_analysis"));
		
/*		PreparedStatement stmt = con.prepareStatement("SELECT * FROM data_profile,data_file,file_desc WHERE data_profile.data_id=data_file.data_id and data_file.file_id=file_desc.id and data_profile.data_id=?");
		stmt.setInt(1, rs.getInt("data_id"));
		
		ResultSet result = stmt.executeQuery();
		int index = 0;
		UploadedFile[] files = null;
		while(result.next()){
			files[index] = new UploadedFile();
			files[index].set
		}
		
		return bean;
	}*/
	
	public static DataBean getDataBeanWithFileID(int fileID) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("select * from data_profile dp join data_file df" +
			" on dp.data_id=df.data_id and df.file_id=? join users us on dp.user_id=us.id;");
		stmt.setInt(1, fileID);
		ResultSet result = stmt.executeQuery();
		DataBean bean = null;
		result.next();
		return generateDataBean(result, bean,con);
	}

	public static String getUsernameWithID(int cusUserId) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("SELECT username FROM users where id=?");
		stmt.setInt(1, cusUserId);
		
		ResultSet rs = stmt.executeQuery();
		rs.next();
		
		return rs.getString(1);
	}

/*	public static DataBean getDataBeanWithDataID(int dataId) throws Exception {
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("SELECT * FROM data_profile,users,data_file,file_desc WHERE data_id=? and data_profile.user_id=users.id");
		stmt.setInt(1, dataId);
		
		ResultSet result = stmt.executeQuery();
		DataBean bean = null;
		result.next();
		
		return generateDataBean4Custom(result,bean,con);
		
	}*/
	
	public static List<CustomBean> getCustomItems(int userId,List<CustomBean> cb) throws Exception{
		Connection con = DBOperation.getConnection();
		PreparedStatement stmt = con.prepareStatement("SELECT * FROM customization WHERE user_id=?");
		stmt.setInt(1, userId);
		
		ResultSet rs = stmt.executeQuery();
		int index = 0;
		while(rs.next()){
			cb.add(new CustomBean());
			manufactCustomItems(rs,cb.get(index));
			index++;
		}
		
		return cb;
		
	}

    private static void manufactCustomItems(ResultSet rs, CustomBean customBean) throws SQLException {
    	customBean = (customBean == null) ? new CustomBean() : customBean;
		
    	customBean.setId(rs.getInt(1));
    	customBean.setResearchAreas(rs.getString(3).split(OtherFields.RESEARCH_AREA_SEPARATOR));
    	customBean.setCustomBa(rs.getString(4));
    	customBean.setCustomLcp(rs.getString(5));
    	customBean.setCustomEmail(rs.getString(6));
    }
    
    public static double getPearsonValue(double[] xArray,double[] yArray){
    	double xMean = 0;
    	double yMean = 0;
    	double xStandardDeviation = 0;
    	double yStandardDeviation = 0;
    	int n = xArray.length;
    	double sum = 0;
    	double r;
    	
    	for(int i=0;i<n;i++)
    		xMean += xArray[i];
    	xMean /= n;
    	
    	for(int i=0;i<n;i++){
    		xStandardDeviation += (xArray[i]-xMean)*(xArray[i]-xMean);
    	}
    	xStandardDeviation /= (n-1);
    	xStandardDeviation =  Math.sqrt(xStandardDeviation); 
    	
    	for(int i=0;i<n;i++)
    		yMean += yArray[i];
    	yMean /= n;
    	
    	for(int i=0;i<n;i++){
    		yStandardDeviation += (yArray[i]-yMean)*(yArray[i]-yMean);
    	}
    	yStandardDeviation /= (n-1);
    	yStandardDeviation = Math.sqrt(yStandardDeviation);
    	
    	for(int i=0;i<n;i++){
    		sum += ((xArray[i]-xMean)/xStandardDeviation)*((yArray[i]-yMean)/yStandardDeviation);
    	}
    	r = (double)sum/(n-1);
		return r; 
    }

}