package iscas.nfs.itechs.ese.utils;

import java.io.BufferedReader;
import java.io.IOException;

public interface StopWordsFilter {
	public String filterStopWords(BufferedReader br) throws IOException;
}
