package iscas.nfs.itechs.ese.servlets;

import iscas.nfs.itechs.ese.convert.PropertiesManager;
import iscas.nfs.itechs.ese.merge.ArffFile;

import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import weka.core.Attribute;
import weka.core.DenseInstance;
import weka.core.FastVector;
import weka.core.Instance;
import weka.core.Instances;

@SuppressWarnings("deprecation")
public class MergeServlet extends HttpServlet {
	/**
	 * 
	 */
	private static final long serialVersionUID = -5542140432431113505L;
	private static final String ATTRIBUTE_SEPARATOR = "-_-";
	private static final String FILE_SEPARATOR = "@";
	private static final String FILE_ATTR_SEPARATOR = "@";
	private static final String DATE_FORMAT = "yyyy.MM.dd G 'at' HH:mm:ss z";
	private static final String ARFF_STORAGE = PropertiesManager.getApp("Constants.ARFF_TEMP_STORAGE");
	private static final Random random = new Random();
	
	private Instances head = null;

	public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException {
		String newFileName = request.getParameter("newFileName");
		String[] newAttrs = request.getParameterValues("attr");
		String[] flIDs = request.getParameter("Files").split(FILE_SEPARATOR);
		Integer[] fileIDs = new Integer[flIDs.length];
		for(int i = 0; i < fileIDs.length; i++) fileIDs[i] = Integer.valueOf(flIDs[i]);
		flIDs = null;
		String[] types = request.getParameterValues("attr_type");
		String[] flattr = request.getParameter("Attrs").split(FILE_ATTR_SEPARATOR);
		String[][] attrs = new String[newAttrs.length][fileIDs.length];
		
		for(int i = 0; i < fileIDs.length; i++) {
			String[] column = flattr[i].split(ATTRIBUTE_SEPARATOR);
			for(int j = 0; j < types.length; j++) {
				attrs[j][i] = column[j];
			}
		}

		String result = generateMergedArffFile(request, newFileName, newAttrs, types, fileIDs, attrs);
		response.sendRedirect("exportData.jsp?flag=" + result);
	}
	
	@SuppressWarnings("unchecked")
	private Enumeration getScopeOfAttrInFile(HashMap<Integer, ArffFile> map, int fileID, String attr) {
		Attribute[] attrs = map.get(fileID).getAttributes();
		for(int i = 0; i < attrs.length; i++) {
			if(attrs[i].name().equals(attr))
				return attrs[i].enumerateValues();
		}
		return null;
	}
	
	@SuppressWarnings("unchecked")
	private Instances generateAttributeHead(String fileName, String[] newAttrs, String[] types, 
			HashMap<Integer, ArffFile> map, Integer[] fileIDs, String[][] ats) {
		ArrayList<Attribute> attrs = new ArrayList<Attribute>();
		String tp = null;
		for(int i = 0; i < types.length; i++) {
			tp = types[i].toLowerCase();
			if(tp.equals("numeric") || tp.equals("integer") || tp.equals("real"))
				attrs.add(new Attribute(newAttrs[i]));
			else if(tp.equals("date"))
				attrs.add(new Attribute(newAttrs[i], DATE_FORMAT));
			else if(tp.equals("string")) {
				attrs.add(new Attribute(newAttrs[i], (FastVector)null));
			} else if(tp.equals("nominal")) {
				List scope = new LinkedList();
				for(int j = 0; j < fileIDs.length; j++) {
					Enumeration es = getScopeOfAttrInFile(map, fileIDs[j], ats[i][j]);
					while(es.hasMoreElements()) scope.add(es.nextElement());
				}
				attrs.add(new Attribute(newAttrs[i], scope));
			} else {
				
			}
		}
		head = new Instances(fileName, attrs, newAttrs.length);
		return head;
	}
	
	private int getIndexOfAttribute(HashMap<Integer, ArffFile> map, String[][] attrs, int fileID, 
			int rowIndex, int fileIndex) {
		Attribute[] ats = map.get(fileID).getAttributes();
		for(int i = 0; i < ats.length; i++) {
			if(ats[i].name().equals(attrs[rowIndex][fileIndex]))
				return i;
		}
		return 0;
	}
	
	private Instances getInstanceInFile(Integer fileID, int fileIndex, String[][] attrs,
			HashMap<Integer, ArffFile> map, Instances instances) {
		instances = new Instances(head, 0, 0);
		Instance[] data = map.get(fileID).getData();
		
		int[] attrIndex = new int[attrs.length];
		for(int i = 0; i < attrIndex.length; i++) {
			attrIndex[i] = getIndexOfAttribute(map, attrs, fileID, i, fileIndex);
		}
		
		for(int i = 0; i < data.length; i++) {
			Instance instance = new DenseInstance(attrs.length);
			for(int j = 0; j < attrIndex.length; j++) {
				if(Attribute.typeToString(head.attribute(j)) != Attribute.ARFF_ATTRIBUTE_STRING) {
					instance.setValue(j, data[i].value(attrIndex[j]));
				}
				else {
					int index = head.attribute(j).indexOfValue(data[i].stringValue(attrIndex[j]));
					if(index == -1) {
						index = head.attribute(j).addStringValue(data[i].stringValue(attrIndex[j]));
					}
					instance.setValue(j, (double)index);
				}
			}
			instances.add(instance);
		}
		
		return instances;
	}
	
	private String generateDataInstances(Integer[] fileIDs, String[][] attrs, 
			HashMap<Integer, ArffFile> map) {
		Instances instances = new Instances(head);
		for(int i = 0; i < fileIDs.length; i++) {
			Instances ins = null;
			instances.addAll(getInstanceInFile(fileIDs[i], i, attrs, map, ins));
		}
		return instances.toString();
	}
	
	@SuppressWarnings("unchecked")
	private String generateMergedArffFile(HttpServletRequest request, String fileName, String[] newAttrs,
			String[] types, Integer[] fileIDs, String[][] attrs) throws IOException {
		HashMap<Integer, ArffFile> map = (HashMap<Integer, ArffFile>)request.getSession().getAttribute("arffs");
		head = generateAttributeHead(fileName, newAttrs, types, map, fileIDs, attrs);
		fileName = String.valueOf(random.nextLong());
		FileWriter fw = new FileWriter(ARFF_STORAGE + fileName + ".arff");
		BufferedWriter bw = new BufferedWriter(fw);
//		bw.write(head.toString());
		bw.write(generateDataInstances(fileIDs, attrs, map));
		bw.flush();
		bw.close();
		return fileName + ".arff";
	}
}
