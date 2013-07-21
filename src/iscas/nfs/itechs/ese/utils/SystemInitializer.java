package iscas.nfs.itechs.ese.utils;

import iscas.nfs.itechs.ese.beans.UploadedFile;
import iscas.nfs.itechs.ese.merge.ArffFile;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.Iterator;

import weka.core.Attribute;

public class SystemInitializer {
	private static ArrayList<UploadedFile> files = null;
	private static final String[] RESEARCH_AREA = {"Defect Prediction", "Effort Prediction", "General", 
		"Process Study", "Requirement Study"};
	private static final String[] PHASE = {"Requirement", "Design", "Coding", "Testing", "Maintenance"};
	
	private static final HashMap<String, Integer> INDEX_WITH_FILE_NAME 
		= new HashMap<String, Integer>();

	private static final HashMap<String, HashSet<Integer>> INDEX_WITH_KEY_WORDS
		= new HashMap<String, HashSet<Integer>>();

	private static final HashMap<String, HashSet<Integer>> INDEX_WITH_ATTRIBUTE
		= new HashMap<String, HashSet<Integer>>();

	private static final HashMap<String, HashSet<Integer>> INDEX_WITH_RESEARCH_AREA
		= new HashMap<String, HashSet<Integer>>();

	private static final HashMap<String, HashSet<Integer>> INDEX_WITH_PHASE
		= new HashMap<String, HashSet<Integer>>();
	
	static {
		try {
			files = Utilities.getAllFiles(files);
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		for(String ra : RESEARCH_AREA) {
			INDEX_WITH_RESEARCH_AREA.put(ra.toLowerCase(), new HashSet<Integer>());
		}
		
		for(String phase : PHASE) {
			INDEX_WITH_PHASE.put(phase.toLowerCase(), new HashSet<Integer>());
		}
	}
	
	
	
	public SystemInitializer() throws Exception {
		Initializer fileNameInitializer = new Initializer(Initializer.FILE_NAME_FLAG);
		Thread fniThread = new Thread(fileNameInitializer);
		fniThread.start();
		
		Initializer attrInitializer = new Initializer(Initializer.ATTRIBUTE_FLAG);
		Thread attiThread = new Thread(attrInitializer);
		attiThread.start();
		
		Initializer raInitializer = new Initializer(Initializer.RESEARCH_FLAG);
		Thread raThread = new Thread(raInitializer);
		raThread.start();
		
		Initializer phsInitializer = new Initializer(Initializer.PHASE_FLAG);
		Thread phsThread = new Thread(phsInitializer);
		phsThread.start();
		
		Initializer keyWordsInitializer = new Initializer(Initializer.KEY_WORDS_FLAG);
		Thread kwiThread = new Thread(keyWordsInitializer);
		kwiThread.start();
	}
	
	private static void initialFileNameIndex() throws Exception {
		String str = null;
		for(int i = 0; i < files.size(); i++) {
			str = files.get(i).getOriginName();
			str = str.substring(0, str.lastIndexOf(".arff"));
			INDEX_WITH_FILE_NAME.put(str.toLowerCase(), files.get(i).getId());
		}
		
		System.out.println("File Name Index Constructed!");
	}
	
	private static void initialKeyWordsIndex() throws IOException {
		BufferedReader br = null;
		HashSet<Integer> hs = null;
		for(int i = 0; i < files.size(); i++) {
			br = new BufferedReader(new FileReader(Constants.FILE_STORAGE + files.get(i).getCurrentName()));
			ArffStopWordsFilter filter = new ArffStopWordsFilter();
			String[] filteredContent = filter.filterStopWords(br).split("[ ]+");	//ÕýÔòÊ½
			for(String str : filteredContent) {
				hs = INDEX_WITH_KEY_WORDS.get(str.toLowerCase());
				if(hs == null) {
					hs = new HashSet<Integer>();
					INDEX_WITH_KEY_WORDS.put(str.toLowerCase(), hs);
				}
				hs.add(files.get(i).getId());
			}
			br.close();
		}
		
		System.out.println("Key Words Index Constructed!");
	}
	
	private static void initialAttributeIndex() throws FileNotFoundException, IOException {
		ArffFile af = null;
		Attribute[] attrs = null;
		for(int i = 0; i < files.size(); i++) {
			af = new ArffFile(Constants.FILE_STORAGE + files.get(i).getCurrentName());
			af.run();
			attrs = af.getAttributes();
			for(Attribute attr : attrs) {
				HashSet<Integer> hs = INDEX_WITH_ATTRIBUTE.get(attr.name().toLowerCase());
				if(hs == null){
					hs = new HashSet<Integer>();
					INDEX_WITH_ATTRIBUTE.put(attr.name().toLowerCase(), hs);
				}
				hs.add(files.get(i).getId());
			}
		}
		
		System.out.println("Attributes Index Constructed!");
	}
	
	private static void initialResearchAreaIndex() throws Exception {
		for(String ra : RESEARCH_AREA) {
			HashSet<Integer> hs = INDEX_WITH_RESEARCH_AREA.get(ra.toLowerCase());
			for(int i = 0; i < files.size(); i++) {
				if(Utilities.researchAreaMatched(ra, files.get(i)))
					hs.add(files.get(i).getId());
			}
		}
		
		System.out.println("Research Area Index Constructed!");
	}
	
	private static void initialPhaseIndex() throws Exception {
		for(String ph : PHASE) {
			HashSet<Integer> hs = INDEX_WITH_PHASE.get(ph.toLowerCase());
			for(int i = 0; i < files.size(); i++) {
				if(Utilities.phaseMatched(ph, files.get(i)))
					hs.add(files.get(i).getId());
			}
		}
		
		System.out.println("Phase Index Constructed!");
	}
	
	private HashSet<Integer> getKeyWordsMatchedFiles(String[] keyWords, HashSet<Integer> result) {
		Iterator<String> it = null;
		Iterator<Integer> iti = null;
		String str = null;
		HashSet<Integer> hs = null;
		for(String kw : keyWords) {
			it = INDEX_WITH_FILE_NAME.keySet().iterator();
			while(it.hasNext()) {
				str = it.next();
				if(str.contains(kw.toLowerCase()))
					result.add(INDEX_WITH_FILE_NAME.get(str));
			}
			
			it = INDEX_WITH_ATTRIBUTE.keySet().iterator();
			while(it.hasNext()) {
				str = it.next();
				if(str.contains(kw.toLowerCase())) {
					hs = INDEX_WITH_ATTRIBUTE.get(str);
					iti = hs.iterator();
					while(iti.hasNext())
						result.add(iti.next());
				}
			}
			
			it = INDEX_WITH_KEY_WORDS.keySet().iterator();
			while(it.hasNext()) {
				str = it.next();
				if(str.contains(kw.toLowerCase())) {
					hs = INDEX_WITH_KEY_WORDS.get(str);
					iti = hs.iterator();
					while(iti.hasNext()) 
						result.add(iti.next());
				}
			}
			
			it = INDEX_WITH_RESEARCH_AREA.keySet().iterator();
			while(it.hasNext()) {
				str = it.next();
				if(str.contains(kw.toLowerCase())) {
					hs = INDEX_WITH_RESEARCH_AREA.get(str);
					iti = hs.iterator();
					while(iti.hasNext())
						result.add(iti.next());
				}
			}
			
			it = INDEX_WITH_PHASE.keySet().iterator();
			while(it.hasNext()) {
				str = it.next();
				if(str.contains(kw.toLowerCase())) {
					hs = INDEX_WITH_PHASE.get(str);
					iti = hs.iterator();
					while(iti.hasNext()) 
						result.add(iti.next());
				}
			}
		}
		
		return result;
	}
	
	private HashSet<Integer> integrateWithResearchArea(String[] researchArea, HashSet<Integer> result) {
		HashSet<Integer> hs = null;
		for(String ra : researchArea) {
			hs = INDEX_WITH_RESEARCH_AREA.get(ra.toLowerCase());
			if(hs != null) result.retainAll(hs);
		}
		return result;
	}
	
	private HashSet<Integer> integrateWithPhase(String[] phase, HashSet<Integer> result) {
		HashSet<Integer> hs = null;
		for(String phs : phase) {
			hs = INDEX_WITH_PHASE.get(phs.toLowerCase());
			if(hs != null) result.retainAll(hs);
		}
		return result;
	}
	
	public HashSet<Integer> getMatchedFiles(String[] keyWords, String[] researchArea, 
			String[] phase, HashSet<Integer> result) {
		result = (result == null) ? new HashSet<Integer>() : result;
		if(keyWords != null) result = getKeyWordsMatchedFiles(keyWords, result);
		if(researchArea != null) result = integrateWithResearchArea(researchArea, result);
		if(phase != null) result = integrateWithPhase(phase, result);
		return result;
	}
	
	private UploadedFile getFileWithID(int id) {
		Iterator<UploadedFile> it = files.iterator();
		UploadedFile file = null;
		while(it.hasNext()) {
			file = it.next();
			if(file.getId() == id)
				return file;
		}
		
		return file;
	}
	
	public UploadedFile[] getFilesList(int beginPage, HashSet<Integer> hs, UploadedFile[] list) {
		HashMap<Integer, UploadedFile> fls = new HashMap<Integer, UploadedFile>();
		Iterator<Integer> it = hs.iterator();
		int i = 0;
		while(it.hasNext()) {
			int v = it.next();
			fls.put(i, getFileWithID(v));
			i++;
		}
		
		int radix = beginPage * Constants.FILES_AMOUNT_PER_PAGE;
		if((beginPage + 1) * Constants.FILES_AMOUNT_PER_PAGE <= hs.size())
			list = new UploadedFile[Constants.FILES_AMOUNT_PER_PAGE];
		else {
			list = new UploadedFile[hs.size() - radix];
		}
		for(i = 0; i < list.length; i++) {
			list[i] = fls.get(radix + i);
		}
		return list;
	}
	
	private class Initializer implements Runnable {
		private static final int FILE_NAME_FLAG = 1;
		private static final int KEY_WORDS_FLAG = 2;
		private static final int ATTRIBUTE_FLAG = 3;
		private static final int RESEARCH_FLAG = 4;
		private static final int PHASE_FLAG = 5;
		
		private int flag = 1;
		
		Initializer(int fl) {
			assert(fl == FILE_NAME_FLAG || fl == KEY_WORDS_FLAG || fl == ATTRIBUTE_FLAG || 
					fl == RESEARCH_FLAG || fl == PHASE_FLAG);
			flag = fl;
		}

		public void run() {
			// TODO Auto-generated method stub
			try {
				switch(this.flag) {
					case FILE_NAME_FLAG : {
						initialFileNameIndex();
						break;
					} case KEY_WORDS_FLAG : {
						long begin = System.currentTimeMillis();
						initialKeyWordsIndex();
						long end = System.currentTimeMillis();
						System.out.println((end - begin) / 1000.0 + " seconds ellapse.");
						break;
					} case ATTRIBUTE_FLAG : {
						initialAttributeIndex();
						break;
					} case RESEARCH_FLAG : {
						initialResearchAreaIndex();
						break;
					} case PHASE_FLAG : {
						initialPhaseIndex();
						break;
					} default : break;
				}
			} catch (Exception e) {
				e.printStackTrace();
			}
		}
	}
}
