package iscas.nfs.itechs.ese.convert;

import java.io.File;

public class GenerateException extends Exception {
	public GenerateException(String sMsg) {
		super(sMsg);
	}

	public GenerateException(String sMsg, File fDir) {
		this(sMsg + String.format("%nFile = %s", fDir.getAbsolutePath()));
	}

	public GenerateException(String sMsg, Exception e, String sFileName) {
		super(sMsg + String.format("%nFile = %s", sFileName), e);
	}

	public GenerateException(String sMsg, String sFileName) {
		this(sMsg + String.format("%nFile = %s", sFileName));
	}

	public GenerateException(String sMsg, int iMaxFile) {
		this(sMsg + String.format("%nMax = %d", iMaxFile));
	}
}