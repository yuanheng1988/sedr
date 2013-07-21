package iscas.nfs.itechs.ese.merge;

import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.HashMap;
import java.util.LinkedList;

import com.google.gson.Gson;

import weka.core.Attribute;

//import net.sf.json.JSONArray;

import iscas.nfs.itechs.ese.beans.UploadedFile;
import iscas.nfs.itechs.ese.convert.PropertiesManager;

public class ArffParser {
	private static final String FILE_SAVE_AT = PropertiesManager.getApp("Constants.FILE_STORAGE");
	private LinkedList<LinkedList<LinkedList<String>>> list = null;
	
	private HashMap<Integer, ArffFile> arffFiles = null;
	
	private static final String trimANDreplace(String s) {
		if(s == null) {
			return "";
		} else {
//			s = s.replace("", "\n\n");
//			s = s.replace("", "\r\n");
//			s = s.replace("&nbsp;&nbsp;&nbsp;&nbsp;", "\t");
//			s = s.replace("&nbsp;", " ");
//			s = s.replace("&lt;", "<");
//			s = s.replace("&gt;", ">");
//			s = s.replace("&amp;", "&");
			s = s.replace("\"", "\'");
			return s;
		} 
	}
	
	public String readArffFiles(UploadedFile[] files) {
		if(files == null) return "";
		list = new LinkedList<LinkedList<LinkedList<String>>>();
		arffFiles = new HashMap<Integer, ArffFile>();
		try {
			read4JSON(files);
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
//		JSONArray obj = JSONArray.fromObject(list);
		Gson gson = new Gson();
		return trimANDreplace(gson.toJson(list));
	}
	
	private void read4JSON(UploadedFile[] files) throws FileNotFoundException, IOException {
		ArffFile file = null;
		for(UploadedFile fl : files) {
			file = new ArffFile(FILE_SAVE_AT + fl.getCurrentName());
			arffFiles.put(fl.getId(), file);
			file.run();
			Attribute[] attrs = file.getAttributes();
			LinkedList<String> attrNames = new LinkedList<String>();
			LinkedList<String> attrTypes = new LinkedList<String>();
			for(Attribute attr : attrs) {
				attrNames.add(attr.name());
				attrTypes.add(Attribute.typeToString(attr));
			}
			LinkedList<LinkedList<String>> nmANDtp = new LinkedList<LinkedList<String>>();
			nmANDtp.add(attrNames);
			nmANDtp.add(attrTypes);
			list.add(nmANDtp);
		}
	}
	
	public HashMap<Integer, ArffFile> getFileMap() {
		return this.arffFiles;
	}
	
	public ArffFile getArffFileWithName(String name, ArffFile file) throws Exception {
		if(file == null) {
			file = new ArffFile(FILE_SAVE_AT + name);
			file.run();
		}
		return file;
	}
}
