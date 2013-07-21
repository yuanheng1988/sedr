package iscas.nfs.itechs.ese.utils;

import iscas.nfs.itechs.ese.beans.User;

public class AuthorityUtil {
	public static boolean isUplodable(User user) {
		if(user == null) return false;
		Authority[] authority = Constants.authorities;
		return authority[user.getAuthID() - 1].isUpload();
	}
	
	public static boolean isUpdatable(User user) {
		return false;
	}
	
	public static boolean isDeletable(User user) {
		return false;
	}
	
	public static boolean isDownloadable(User user) {
		if(user == null) return false;
		Authority[] authority = Constants.authorities;
		return authority[user.getAuthID() - 1].isDownload();
	}
}
