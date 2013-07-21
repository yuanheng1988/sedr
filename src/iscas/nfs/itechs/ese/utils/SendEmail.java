package iscas.nfs.itechs.ese.utils;

import java.util.Date;
import java.util.Properties;
import java.util.TimerTask;

import javax.mail.BodyPart;
import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.Multipart;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.AddressException;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeBodyPart;
import javax.mail.internet.MimeMessage;
import javax.mail.internet.MimeMultipart;
import javax.servlet.ServletContext;

public class SendEmail extends TimerTask 
{ 
	private ServletContext context = null; 
 
	public SendEmail(ServletContext context) 
	{ 
		this.context = context; 
	} 
 
	@Override 
	public void run()
	{ 
		/*
		 * 以下为javamail的邮件发送
		 */
	      
		System.out.println("正在发送邮件");

		Properties props=new Properties();
		props.put("mail.smtp.host","smtp.163.com");//发件人使用发邮件的电子信箱服务器我使用的是163的服务器
		props.put("mail.smtp.auth","true"); //这样才能通过验证
		Session s=Session.getInstance(props);
		s.setDebug(true);

		MimeMessage message=new MimeMessage(s);

		//给消息对象设置发件人/收件人/主题/发信时间
		InternetAddress from;
		try {
			from = new InternetAddress("xukexin1988hpxcm@163.com");
			message.setFrom(from);
			InternetAddress to=new InternetAddress("603148132@qq.com");// tto为发邮件的目的地（收件人信箱）

			message.setRecipient(Message.RecipientType.TO,to);
			message.setSubject("Custom Information");// ttitle为邮件的标题
			message.setSentDate(new Date());
			BodyPart mdp=new MimeBodyPart();//新建一个存放信件内容的BodyPart对象
			mdp.setContent(context,"text/html;charset=utf-8");//给BodyPart对象设置内容和格式/编码方式tcontent为邮件内容
			Multipart mm=new MimeMultipart();//新建一个MimeMultipart对象用来存放BodyPart对象(事实上可以存放多个)
			mm.addBodyPart(mdp);//将BodyPart加入到MimeMultipart对象中(可以加入多个BodyPart)
			message.setContent(mm);//把mm作为消息对象的内容

			message.saveChanges();
			Transport transport=s.getTransport("smtp");
			transport.connect("smtp.163.com","daida","789-jik");//发邮件人帐户密码,此外是我的帐户密码，使用时请修改。
			transport.sendMessage(message,message.getAllRecipients());
			transport.close();
		} catch (AddressException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}  //发邮件的出发地（发件人的信箱），这是我的邮箱地址，使用请改成你的有效地址
        catch (MessagingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}        
} 


