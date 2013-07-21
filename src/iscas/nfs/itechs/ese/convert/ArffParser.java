package iscas.nfs.itechs.ese.convert;

import java.io.Reader;
import java.util.zip.ZipOutputStream;
import java.util.zip.ZipEntry;
import java.util.Locale;
import java.text.SimpleDateFormat;
import java.text.ParseException;
import weka.core.Instances;
import weka.core.Attribute;
import weka.core.Instance;
import weka.core.FastVector;
import weka.core.converters.ArffLoader.ArffReader;

/**ARFF syntax: SPACE is always ignored and serves as separator. EOL is equivalent 
to SPACE except where it is explicitly specified. KEYWORD is case-insensitive.
Sepecial characters are |, (, ), {, }, [, ], +, *, :
TOKEN: QUOTED_STRING | UNQUOTED_STRING | KEYWORD | EOL | COMMENT | SPACE | EOF
KEYWORD: @relation | @attribute | @data | @end 
	| numeric | integer | real | string | date | relational
COMMENTS: {COMMENT EOL}+
WORD: QUOTED_STRING | UNQUOTED_STRING
WORD_LIST: WORD {, WORD}*
DOUBLE_WORD: WORD WORD
DOUBLE_WORD_LIST: DOUBLE_WORD {, DOUBLE_WORD}*
ARFF: RELATION DATA
RELATION: RELATION_HEAD RELATION_BODY
REALTION_HEAD: [COMMENTS] @relation WORD [COMMENT] EOL
RELATION_BODY: {ATTRIBUTE}+
ATTRIBUTE: SIMPLE_ATTRIBUTE | COMPOSITE_ATTRIBUTE
SIMPLE_ATTRIBUTE: [COMMENTS] @attribute WORD SIMPLE_DATA_TYPE [COMMENT] EOL
SIMPLE_DATA_TYPE: NUMERIC | NOMINAL | STRING | DATE
NUMERIC: numeric | integer | real
NOMINAL: '{' WORD_LIST '}'
STRING: string
DATE: date [WORD]
COMPOSITE_ATTRIBUTE: [COMMENTS] @attribute WORD relational [COMMENT] EOL 
	{ATTRIBUTE}+ [COMMENTS] @end WORD [COMMENT] EOL
DATA: DATA_HEAD DATA_BODY
DATA_HEAD: [COMMENTS] @data [COMMENT] EOL
DATA_BODY: {INSTANCE}+
INSTANCE: [COMMENTS] (FULL_INSTANCE | SPARSE_INSTANCE) [COMMENT] EOL
FULL_INSTANCE: WORD_LIST [WEIGHT]
SPARSE_INSTANCE: '{' DOUBLE_WORD_LIST '}' [WEIGHT]
WEIGHT: , '{' WORD '}'
*/
public class ArffParser {
	private static final String DATE_FORMAT[] = {"E MMM dd HH:mm:ss 'UTC'Z yyyy"
		, "yyyy-MM-dd", "yyyy-MM-dd HH:mm:ss"
		, "MM/dd/yyyy", "MM/dd/yyyy HH:mm:ss"
		, "dd MM yyyy", "dd MM yyyy HH:mm:ss"};
	private static final String ZIP_CHARSET = "UTF-8";
	private Instances table, head;
	private String message;

	public void parseDataType(Reader r)
		throws ArffParseException {
		int attrIndex, attrCount, instIndex, instCount, missCount, type;
		Attribute attr, attrInfer;
		Instance inst;
		String value, typeName, dateFormat;
		StringBuilder message;
		int freq[], minFreq;
		FastVector attrs;
		boolean parseNumber, parseDate;
		SimpleDateFormat simpleDate;

		try {
			ArffReader ar = new ArffReader(r);
			table = ar.getData();
		} catch(Exception e) {
			throw new ArffParseException("Error in Parsing ARFF", e);
		}
		if(table.numInstances() == 0)
			throw new ArffParseException("No Instance");

		instCount = table.numInstances();
		attrCount = table.numAttributes();
		attrs = new FastVector(attrCount);
		message = new StringBuilder();
		for(attrIndex = 0; attrIndex < attrCount; attrIndex++) {
			attr = table.attribute(attrIndex);
			if(attr.type() != Attribute.STRING)
				throw new ArffParseException("Initial Attribute Type Must Be STRING"
					, attrIndex);

			missCount = 0;
			parseNumber = true;
			parseDate = true;
			type = Attribute.STRING;
			freq = new int[attr.numValues()];
			dateFormat = null;
			simpleDate = null;
			for(instIndex = 0; instIndex < instCount; instIndex++) {
				inst = table.instance(instIndex);
				if(inst.isMissing(attrIndex)) {
					missCount++;
					continue;
				}
				freq[(int)inst.value(attrIndex)]++;
				value = inst.stringValue(attrIndex);
				if(parseNumber)
					try {
						Double.valueOf(value);
						type = Attribute.NUMERIC;
						break;
					} catch(NumberFormatException nfe) {
						parseNumber = false;
					}
				if(parseDate) {
					for(String format : DATE_FORMAT) {
						try {
							simpleDate = new SimpleDateFormat(format, Locale.ENGLISH);
							simpleDate.setLenient(false);
							simpleDate.parse(value);
							type = Attribute.DATE;
							assert(format != null);
							dateFormat = format;
							break;
						} catch(ParseException pe) {
							continue;
						}
					}
					if(dateFormat != null)
						break;
					simpleDate = null;
					parseDate = false;
				}
			}

			if(type == Attribute.STRING) {
				if((double)freq.length / (instCount - missCount) <= 0.4) {
					minFreq = instCount;
					for(int f : freq)
						if(f < minFreq)
							minFreq = f;
					if(minFreq >= 2)
						type = Attribute.NOMINAL; 
				}
			}

			switch(type) {
			case Attribute.STRING:
				attrInfer = new Attribute(attr.name(), (FastVector)null, attrIndex);
				typeName = Attribute.ARFF_ATTRIBUTE_STRING;
				break;
			case Attribute.NUMERIC:
				attrInfer = new Attribute(attr.name(), attrIndex);
				typeName = Attribute.ARFF_ATTRIBUTE_NUMERIC;
				break;
			case Attribute.NOMINAL: {
				FastVector nominals = new FastVector(freq.length);
				for(int v = 0; v < freq.length; v++)
					nominals.addElement(attr.value(v));
				attrInfer = new Attribute(attr.name(), nominals, attrIndex);
				typeName = "nominal";
				break;
			}
			case Attribute.DATE:
				attrInfer = new Attribute(attr.name(), (String)null, attrIndex);
				for(instIndex = 0; instIndex < instCount; instIndex++) {
					try {
						inst = table.instance(instIndex);
						long numDate = simpleDate.parse(inst.stringValue(attrIndex)).getTime();
						String strDate = attrInfer.formatDate(numDate);
						inst.setValue(attrIndex, attr.addStringValue(strDate));
					} catch(Exception e) {
						throw new ArffParseException("Error Reformatting Date String"
							, attrIndex, instIndex, e);
					}
				}
				typeName = Attribute.ARFF_ATTRIBUTE_DATE;
				break;
			default:
				throw new ArffParseException("Illegal Attribute Type", type, attrIndex);
			}

			message.append(String.format("[%d]%s, %s=>%s%n", attrIndex, attr.name()
				, Attribute.ARFF_ATTRIBUTE_STRING, typeName));
			attrs.addElement(attrInfer);
		}
		head = new Instances(table.relationName(), attrs, 0);
		this.message = message.toString();
	}

	public void createEntry(ZipOutputStream zos, int iTableCnt)
		throws ArffParseException {
		try {
			ZipEntry ze = new ZipEntry(String.format("%d.arff", iTableCnt));
			zos.putNextEntry(ze);
			zos.write(head.toString().getBytes(ZIP_CHARSET));
			int instIndex, instCount;
			for(instIndex = 0, instCount = table.numInstances(); instIndex < instCount; 
				instIndex++) {
				zos.write(table.instance(instIndex).toString().getBytes(ZIP_CHARSET));
				zos.write((int)'\n');
			}
			zos.closeEntry();
		} catch(Exception e) {
			throw new ArffParseException("Error in Creating ZIP Entry", e);
		}
	}

	public String getMessage() {
		return message;
	}
}