package iscas.nfs.itechs.ese.convert;

public class ConvertException extends Exception {
	/**
	 * 
	 */
	private static final long serialVersionUID = -6704950973336317755L;

	public ConvertException(String msg) {
		super(msg);
	}

	public ConvertException(String msg, Exception e) {
		super(msg, e);
	}
}