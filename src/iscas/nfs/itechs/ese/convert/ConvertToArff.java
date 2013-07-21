package iscas.nfs.itechs.ese.convert;

import java.io.InputStream;
import java.util.zip.ZipOutputStream;

public interface ConvertToArff {
	public static String FORMAT_XLS = "xls", FORMAT_DOC = "doc", FORMAT_CSV = "csv";
	public static String CP_GB2312 = "GB2312", CP_GBK = "GBK", CP_UTF_8 = "UTF-8"
		, CP_ISO_8859_1 = "ISO-8859-1", CP_WINDOWS_1252 = "WINDOWS-1252"
		, CP_DEFAULT = "DEFAULT";

/**sFormat is one of FORMAT_*, sCodePage is one of CP_*. sCodePage is required 
only when sFormat is FORMAT_CSV.
*/
	ConvertReport doConvert(InputStream is, String sFormat, String sCodePage
		, ZipOutputStream zos)
		throws ConvertException;
}