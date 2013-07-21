package iscas.nfs.itechs.ese.convert;

import java.io.InputStream;
import java.io.IOException;
import java.io.Writer;
import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStreamWriter;
import java.io.InputStreamReader;
import java.io.CharArrayWriter;
import java.io.CharArrayReader;
import java.io.BufferedReader;
import java.io.Closeable;
import java.util.zip.ZipOutputStream;
import java.util.List;

public class ConvertByJScript implements ConvertToArff {
	private static final String SCRIPT_DIR = "convert.ConvertByJScript.SCRIPT_DIR";
	private static final String NEW_LINE = "\r\n", CONSOLE_CHARSET = "GBK"
		, RESULT_ERROR = "error", RESULT_SUCCESS = "success", RESULT_END = "end"
		, COMMAND_QUIT_SCRIPT = "quit_script", JOB_OPTION = "//job:";
	private static final char END_OF_TABLE = '\0';

	private final GenerateUniqueFile guf;
	private ProcessBuilder pb;

	public ConvertByJScript(GenerateUniqueFile guf) {
		assert(guf != null);

		this.guf = guf;
		pb = new ProcessBuilder("cscript.exe", "csv_xls_doc_arff.wsf", "//nologo"
			, "//job:csv");
//System.out.println(PropertiesManager.getApp(SCRIPT_DIR));
		pb.directory(new File(PropertiesManager.getApp(SCRIPT_DIR)));
	}

	public ConvertReport doConvert(InputStream is, String sFormat, String sCodePage
		, ZipOutputStream zos)
		throws ConvertException {
		File fSrc = null;
		FileOutputStream fos = null;
		Process p = null;
		BufferedReader brErr = null;
		OutputStreamWriter oswIn = null;
		InputStreamReader isrOut = null;
		CharArrayWriter cawOut;
		CharArrayReader carOut;
		String sOutLine, sErrLine, sErrMsg;
		ArffParser ap;
		ConvertReport cr = null;
		int iByte, iChar, iTableCnt;
		Closeable ac[];

		assert(is != null);
		assert(!(sFormat == null || sFormat.isEmpty()));
		assert(sCodePage == null || !sCodePage.isEmpty());
		assert(!sFormat.equals(ConvertToArff.FORMAT_CSV) || sCodePage != null);
		assert(zos != null);

		try {
			fSrc = guf.doGenerate();
			fos = new FileOutputStream(fSrc, false);
			while((iByte = is.read()) != -1)
				fos.write(iByte);
		} catch(Exception e) {
			if(fSrc != null) {
				try {
					guf.doRecycle(fSrc);
				} catch(Exception e2) {
					e2.printStackTrace();
				}
				if(fos != null)
					try {
						fos.close();
					} catch(Exception e2) {
						e2.printStackTrace();
					}
			}
			throw new ConvertException("Error in file creation", e);
		}
		if(fos != null)
			try {
				fos.close();
			} catch(Exception e) {
				e.printStackTrace();
			}

		ac = new Closeable[3];
		setFormat(sFormat);
		try {
			p = pb.start();
			ac[0] = oswIn = new OutputStreamWriter(p.getOutputStream(), CONSOLE_CHARSET);
			ac[1] = isrOut = new InputStreamReader(p.getInputStream(), CONSOLE_CHARSET);
			ac[2] = brErr = new BufferedReader(new InputStreamReader(p.getErrorStream()
				, CONSOLE_CHARSET));
		} catch(Exception e) {
			if(p != null) {
//CHOICE: destroy the process after all streams have been closed
				p.destroy();
				for(Closeable c : ac)
					if(c != null)
						try {
							c.close();
						} catch(Exception e2) {
							e2.printStackTrace();
						}
			}
			try {
				guf.doRecycle(fSrc);
			} catch(Exception e2) {
				e2.printStackTrace();
			}
			throw new ConvertException("Error in process creation", e);
		}

		try {
			flushLine(oswIn, fSrc.getCanonicalPath());
			if(sCodePage != null)
				flushLine(oswIn, sCodePage);

			sErrLine = brErr.readLine();
			if(RESULT_SUCCESS.equals(sErrLine)) {
				for(ap = new ArffParser(), cr = new ConvertReport(sFormat, sCodePage)
					, iTableCnt = 0, cawOut = new CharArrayWriter(); ; iTableCnt++) {
					for(; ; ) {
						iChar = isrOut.read();
						if(iChar == -1)
							throw new ConvertException(
								"Unexcepted end of standard output stream reader");
						if(iChar == END_OF_TABLE)
							break;
						cawOut.write(iChar);
					}

/*TODO: use ArffLoader to implement ArffParser so as to be compatible with 
weka implementation.
*/
					sErrLine = brErr.readLine();
					if(RESULT_SUCCESS.equals(sErrLine)) {
						carOut = new CharArrayReader(cawOut.toCharArray());
						cawOut.reset();
						try {
							ap.parseDataType(carOut);
							ap.createEntry(zos, iTableCnt);
							cr.addSuccess(iTableCnt, ap.getMessage());
						} catch(ArffParseException pe) {
							cr.addError(iTableCnt, pe.getMessage());
						} finally {
							carOut.close();
						}
					} else if(RESULT_ERROR.equals(sErrLine)) {
						sErrMsg = getError(brErr);
						cr.addError(iTableCnt, sErrMsg);
					} else if(RESULT_END.equals(sErrLine)) {
						break;
					} else {
						throw new ConvertException("Invalid Result String: " + sErrLine);
					}
				}

			} else if(RESULT_ERROR.equals(sErrLine)) {
				sErrMsg = getError(brErr);
				throw new ConvertException(sErrMsg);
			} else {
				throw new ConvertException("Invalid Result String: " + sErrLine);
			}

			flushLine(oswIn, COMMAND_QUIT_SCRIPT);
		} catch(Exception e) {
			try {
				p.destroy();
			} catch(Exception e2) {
				e2.printStackTrace();
			}
			if(e instanceof ConvertException)
				throw (ConvertException)e;
			else
				throw new ConvertException("Error in script execution", e);
		} finally {
			guf.doBackgroundRecycle(fSrc);
			for(Closeable c : ac)
				try {
					c.close();
				} catch(Exception e) {
					e.printStackTrace();
				}
		}
		return cr;
	}

	private String getError(BufferedReader br)
		throws IOException {
		int iLineCnt;
		StringBuffer sb;

		iLineCnt = Integer.valueOf(br.readLine());
		sb = new StringBuffer();
		assert(iLineCnt > 0);
		while(iLineCnt-- != 0) {
			sb.append(br.readLine());
			sb.append(NEW_LINE);
		}
		return sb.toString();
	}

	private void flushLine(Writer w, String sLine) 
		throws IOException {
		assert(sLine != null);
		w.write(sLine);
		w.write(NEW_LINE);
		w.flush();
	}

	private void setFormat(String sFormat) {
		List<String> lsCommand;

		assert(ConvertToArff.FORMAT_XLS.equals(sFormat) 
			|| ConvertToArff.FORMAT_DOC.equals(sFormat) 
			|| ConvertToArff.FORMAT_CSV.equals(sFormat));
		lsCommand = pb.command();
		assert(lsCommand.size() == 4);
		lsCommand.set(3, JOB_OPTION + sFormat);
	}
}