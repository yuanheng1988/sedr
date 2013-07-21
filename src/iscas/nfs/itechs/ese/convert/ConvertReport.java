package iscas.nfs.itechs.ese.convert;

import java.util.List;
import java.util.LinkedList;

public class ConvertReport {
	private int successCount;
	private String format;
	private String codePage;
	private List<TableResult> resultList;

	private static class TableResult {
		private int tableIndex;
		private String result;

		public TableResult(int tableIndex, String result) {
			this.tableIndex = tableIndex;
			this.result = result;
		}

		public String toString() {
			return String.format("tableIndex = %d, result = %s%n", tableIndex, result);
		}
	}

	public ConvertReport(String format, String codePage) {
		this.format = format;
		this.codePage = codePage;
		resultList = new LinkedList<TableResult>();
	}

	public void addError(int tableIndex, String error){
		resultList.add(new TableResult(tableIndex, error));
	}

	public void addSuccess(int tableIndex, String success) {
		successCount++;
		resultList.add(new TableResult(tableIndex, success));
	}

	public int successCount() {
		return successCount;
	}

	public String getReport() {
		StringBuilder sb = new StringBuilder();
		sb.append(String.format("format = %s, code page = %s, success count = %d%n", format, codePage, successCount));
		for(TableResult tr : resultList) 
			sb.append(tr.toString());
		return sb.toString();
	}
}