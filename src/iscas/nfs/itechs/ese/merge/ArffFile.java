package iscas.nfs.itechs.ese.merge;

import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.Serializable;

import weka.core.Attribute;
import weka.core.Instance;
import weka.core.Instances;
import weka.core.converters.ArffLoader.ArffReader;

public class ArffFile implements Runnable, Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = -2442309532996026342L;
	private String fileName = "";
	private Attribute[] attrs = null;
	private Instance[] data = null;
	
	public ArffFile(String fileName) throws FileNotFoundException, IOException {
		this.fileName = fileName;
	}
	
	private void readFile() throws IOException {
		BufferedReader br = new BufferedReader(new FileReader(this.fileName));
		Instances readed = null;
		try {
			readed = new ArffReader(br).getData();
			this.attrs = new Attribute[readed.numAttributes()];
			this.data = new Instance[readed.numInstances()];
			for(int i = 0; i < readed.numAttributes(); i++)
				this.attrs[i] = readed.attribute(i);
			for(int i = 0; i < readed.numInstances(); i++)
				this.data[i] = readed.instance(i);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			if(br != null) br.close();
		}
	}
	
	public Attribute[] getAttributes() {
		return this.attrs;
	}
	
	public void run() {
		// TODO Auto-generated method stub
		try {
			readFile();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public Instance[] getData() {
		return this.data;
	}
	
	public String getFileName() {
		return this.fileName;
	}
}
