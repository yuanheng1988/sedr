package iscas.nfs.itechs.ese.convert;

import java.io.File;
import java.util.Map;
import java.util.HashMap;
import java.util.List;
import java.util.LinkedList;

public class GenerateUniqueFile {
	public static final int MAX_SMALL = 10, MAX_MEDIUM = 50, MAX_LARGE = 100;
	private static final int RADIX = 10, RETRY_COUNT = 3, RETRY_DELAY = 1000;

	private final File fDir;
	private final Map<String, File> msfUsed;
	private final List<Integer> liFree;
	private final int iMaxFile;

	public GenerateUniqueFile(String sDir)
		throws GenerateException {
		this(new File(sDir));
	}

	public GenerateUniqueFile(File fDir)
		throws GenerateException {
		this(fDir, MAX_SMALL);
	}

	public GenerateUniqueFile(File fDir, int iMaxFile)
		throws GenerateException {

		if(!fDir.exists())
			throw new GenerateException("File does not exist", fDir);
		if(!fDir.isDirectory())
			throw new GenerateException("File is not a directory", fDir);
		if(fDir.list().length != 0)
			throw new GenerateException("Directory is not empty", fDir);
		if(iMaxFile < MAX_SMALL || iMaxFile > MAX_LARGE)
			throw new GenerateException("Maximum number of free files is out of limits", iMaxFile);

		this.fDir = fDir;
		this.msfUsed = new HashMap<String, File>();
		this.liFree = new LinkedList<Integer>();
		this.iMaxFile = iMaxFile;
		while(iMaxFile-- != 0)
			liFree.add(iMaxFile);
	}

	public synchronized File doGenerate()
		throws GenerateException {
		Integer iFree;
		String sFree;
		File fFree = null;
		boolean bRet;

		if(liFree.isEmpty())
			throw new GenerateException("No free file is available");
		iFree = liFree.get(0);
		sFree = Integer.toString(iFree, RADIX);
		if(msfUsed.containsKey(sFree))
			throw new GenerateException("Free file is still in use", sFree);

		fFree = new File(fDir, sFree);
		try {
			bRet = fFree.createNewFile();
			if(!bRet)
				throw new GenerateException("Free file already exists", sFree);
		} catch(Exception e) {
			if(e instanceof GenerateException)
				throw (GenerateException)e;
			else
				throw new GenerateException("Cannot create free file", e, sFree);
		}
		msfUsed.put(sFree, fFree);
		liFree.remove(0);
		return fFree;
	}

	private class BackgroundRecycle implements Runnable {
		private final File fUsed;

		public BackgroundRecycle(File fUsed) {
			assert(fUsed != null);
			this.fUsed = fUsed;
		}

		public void run() {
			try {
				GenerateUniqueFile.this.doRecycle(fUsed);
			} catch(Exception e) {
				e.printStackTrace();
			}
		}
	}

	public void doBackgroundRecycle(File fUsed) {
		new Thread(this.new BackgroundRecycle(fUsed)).start();
	}

	public synchronized boolean doRecycle(File fUsed)
		throws GenerateException {
		boolean bRet;
		File fParent;
		String sUsed;
		Integer iUsed;

		fParent = fUsed.getParentFile();
		if(!fDir.equals(fParent))
			return false;
		sUsed = fUsed.getName();
		if(!msfUsed.containsKey(sUsed))
			return false;
		iUsed = Integer.parseInt(sUsed, RADIX);
		if(liFree.contains(iUsed))
			throw new GenerateException("Used file is still free", sUsed);

		try {
			if(!fUsed.exists())
				throw new GenerateException("Used file does not exists", sUsed);
			for(int retry = RETRY_COUNT; ; ) {
				bRet = fUsed.delete();
				if(bRet)
					break;
				if(retry == 0)
					throw new GenerateException("Cannot delete used file", sUsed);;
				Thread.sleep(RETRY_DELAY);
				retry--;
			}
		} catch(Exception e) {
			if(e instanceof GenerateException)
				throw (GenerateException)e;
			else
				throw new GenerateException("Error in delete or exists for used file", sUsed);
		}
		msfUsed.remove(sUsed);
		liFree.add(iUsed);
		return true;
	}
}