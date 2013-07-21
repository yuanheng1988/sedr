package iscas.nfs.itechs.ese.db;

/**
 * <p>Title: Software Experience Repository</p>
 *
 * <p>Description: To Construct a Software Experience Repository</p>
 *
 * <p>Copyright: Copyright (c) 2010</p>
 *
 * <p>Company: iTechs, NFS, ISCAS</p>
 *
 * @author Wen Zhang
 * @version 1.0
 */
public class DataFile implements Comparable{

    int id;
    String originalFileName;
    String fileSize;
    String format;
    String currentFileName;
    String creationTime;
    String updateTime;
    String downloadCount;

    public DataFile(int id, String originalFileName, String fileSize, String format,
                    String currentFileName, String creationTime, String updateTime, String downloadCount){
        this.id = id;
        this.originalFileName = originalFileName;
        this.fileSize = fileSize;
        this.format = format;
        this.currentFileName = currentFileName;
        this.creationTime = creationTime;
        this.updateTime = updateTime;
        this.downloadCount = downloadCount;
    }

    public int compareTo(Object o){
        DataFile dataFile = (DataFile)o;
        return this.id - dataFile.id;
    }

    public String getOriginalFileName(){
        return this.originalFileName;
    }

    public String getFileSize(){
        return this.fileSize;
    }

    public String getFormat(){
        return this.format;
    }

    public String getCurrentFileName(){
        return this.currentFileName;
    }

    public String getCreationTime(){
        return this.creationTime;
    }

    public String getUpdateTime(){
        return this.updateTime;
    }

    public String getDownloadCount(){
        return this.downloadCount;
    }
}
