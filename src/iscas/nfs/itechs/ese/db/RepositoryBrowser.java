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
import java.util.*;
import java.sql.*;

public class RepositoryBrowser {
    List<DataFile> dataFileList = new ArrayList<DataFile>();

    public RepositoryBrowser() throws Exception{
        Connection connection = DBOperation.getConnection();
        Statement sta = connection.createStatement();
        String sql = "select * from file_desc";
        ResultSet rs =sta.executeQuery(sql);
        while(rs.next()){
            DataFile dataFile = new DataFile(rs.getInt("id"), rs.getString("original_filename"),rs.getString("file_size"),
                                             rs.getString("file_format"), rs.getString("current_filename"), rs.getString("creation_time"),
                                             rs.getString("update_time"), rs.getString("download_count"));
            dataFileList.add(dataFile);
        }
    }

    public List<DataFile> getDataFileList(int start, int end) throws Exception{
        if(start>dataFileList.size())
            return null;
        if(end>dataFileList.size())
            return dataFileList.subList(start,dataFileList.size());
        return dataFileList.subList(start,end);
    }

    public int dataFileListSize(){
        return this.dataFileList.size();
    }
}
