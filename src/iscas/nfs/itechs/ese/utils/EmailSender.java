package iscas.nfs.itechs.ese.utils;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.Calendar;
import java.util.Properties;

import javax.mail.Authenticator;
import javax.mail.BodyPart;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.Message.RecipientType;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeBodyPart;
import javax.mail.internet.MimeMessage;
import javax.mail.internet.MimeMultipart;

import iscas.nfs.itechs.ese.beans.DataBean;
import iscas.nfs.itechs.ese.beans.Feedback;

public class EmailSender {
	private static Properties props = null;
	private static String smtpHost = Constants.MAIL_HOST_ADDRESS;
	private static String downloadHost = Constants.MAIL_DOWNLOAD_HOST;
	
	static {
		props = System.getProperties();
		props.put("mail.smtp.host", smtpHost);
		props.put("mail.smtp.auth", "true");
	}
	
	private static String generateFeedbackContent(Feedback fb) throws UnsupportedEncodingException {
		StringBuffer sb = new StringBuffer();
		
		sb.append("Hi,<br>");
		sb.append("&nbsp;We have received your feedback. Thank you very much!<br><br><br>");
		sb.append("Your feedback content is:<br>");
		sb.append("Title:" + fb.getTitle() + "<br>");
		sb.append("Content:" + fb.getContent() + "<br><br><br>");
		sb.append("Best Regards.<br>");
		sb.append("<a href='mailto:" + Constants.MAIL_SENDER_ADDRESS + "'>" 
				+ Constants.MAIL_SENDER_ADDRESS + "<a>");
		sb.append("<br><br><br>&nbsp;&nbsp;&nbsp;" + Calendar.getInstance().getTime() + "&nbsp;");
		
		return sb.toString();
	}
	
	public static boolean sendFeedbackEmail(Feedback fb) throws MessagingException, IOException {
		Session session = Session.getDefaultInstance(props, new EmailAuthenticator());
		MimeMessage message = new MimeMessage(session);
		message.setFrom(new InternetAddress(Constants.MAIL_SENDER_ADDRESS, Constants.MAIL_SENDER));
		message.addRecipient(RecipientType.TO, new InternetAddress(fb.getEmail()));
		message.setSubject("Thanks for your feedback!");
		BodyPart part = new MimeBodyPart();
		part.setContent(generateFeedbackContent(fb), "text/html;charset=utf-8");
		MimeMultipart mp = new MimeMultipart();
		mp.addBodyPart(part);
		message.setContent(mp);
		
		Transport.send(message);
		return true;
	}
	
	private static String generateCustomizeContent(DataBean data,int data_id,int cus_user_id,String cus_info) throws Exception {
		StringBuffer sb = new StringBuffer();
		
		sb.append("Hi,Dear " + Utilities.getUsernameWithID(cus_user_id)+"<br>");
		sb.append("&nbsp;According to your customization,we send this new data to you!<br><br>");
		sb.append("Your customization infomation is:<br>" + cus_info + "<br><br>");
		sb.append("The profile of this new data is:<br>");
		sb.append("<table border='1' width='800'>");
//		sb.append(Utilities.getDataBeanWithDataID(data_id).toString());
		sb.append(data.toString());
		sb.append("</table>");
		
		String[] fileCurrentNames = data.getFileCurrentNames();
		String[] fileOriginalNames = data.getFileOriginalNames();
		sb.append("<h4>Download List:</h4><br>");
		for(int i=0;i<data.getFiles().length;i++){
			sb.append("Link for File " + (i+1) + " :&nbsp;&nbsp;");
			sb.append("<a href='"+ downloadHost +"/download.jsp?fileName=" + fileCurrentNames[i] + "' onClick = 'alert( fileOriginalNames[i])'>" + fileOriginalNames[i] + "</a>" + "<br>");
		}
		sb.append("<br><br>");
		sb.append("Best Regards.<br>");
		sb.append("<a href='mailto:" + Constants.MAIL_SENDER_ADDRESS + "'>" 
				+ Constants.MAIL_SENDER_ADDRESS + "<a>");
		sb.append("<br><br><br>&nbsp;&nbsp;&nbsp;" + Calendar.getInstance().getTime() + "&nbsp;");
		
		return sb.toString();
	}
	
	public static boolean sendCustomizeEmail(DataBean data,int data_id,int cus_user_id,String cus_info,String cus_email) throws Exception {
		Session session = Session.getDefaultInstance(props, new EmailAuthenticator());
			
		MimeMessage message = new MimeMessage(session);   
		message.setFrom(new InternetAddress(Constants.MAIL_SENDER_ADDRESS, Constants.MAIL_SENDER));
		message.addRecipient(RecipientType.TO, new InternetAddress(cus_email));
		message.setSubject("Your customization at Data Repository!");
		BodyPart part = new MimeBodyPart();
		part.setContent(generateCustomizeContent(data,data_id,cus_user_id,cus_info), "text/html;charset=utf-8");
		MimeMultipart mp = new MimeMultipart();
		mp.addBodyPart(part);
		message.setContent(mp);
		
		Transport.send(message);
		return true;
	}
}

class EmailAuthenticator extends Authenticator {
	private static final String MAIL_USER = Constants.MAIL_SENDER_ADDRESS;
	private static final String MAIL_PWD = "itechs2010";
	
	protected PasswordAuthentication getPasswordAuthentication() {
		return new PasswordAuthentication(MAIL_USER, MAIL_PWD);
	}
}
