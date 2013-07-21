package iscas.nfs.itechs.ese.convert;

public class ArffParseException extends Exception {
	public ArffParseException(String msg) {
		super(msg);
	}
	public ArffParseException(String msg, Exception e) {
		super(msg, e);
	}
	public ArffParseException(String msg, int index) {
		this(String.format("%s%nindex = %d", msg, index));
	}
	public ArffParseException(String msg, int attrIndex, int instIndex, Exception e) {
		this(String.format("%s%nattrIndex = %d, instIndex = %d", msg, attrIndex, instIndex), e);
	}
	public ArffParseException(String msg, int type, int index) {
		this(String.format("%s%nindex = %d, type = %d", msg, index, type));
	}
}