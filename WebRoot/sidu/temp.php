<?php
@include "inc.page.php";
@uppe();
@main();
@down();

function main()
{
	echo "<div class='web'><p><b>SQL Easy Templates</b></p>
<p>SET PASSWORD = password('new-pass')
<br/>SET PASSWORD for user@localhost = password('new-pass')</p>
<p>select inet_aton('127.0.0.1')
<br/>select substring_index('sidu@yahoo.com','@',1)</p>
<p>It's a good idea to have primary key in each table
<br/>It's a good idea to have int col ahead, and blob col at end
<br/>SIDU will sort first col if no sort found by default</p>

<p>sudo apt-get install apache2 php5 libapache2-mod-php5
<br/>sudo apt-get install mysql-server mysql-client php5-mysql
<br/>sudo apt-get install php5-gd</p>
<p>sudo gedit /etc/postgresql/8.3/main/pg_hba.conf
<br/>sudo apt-get install php5-pgsql</p>
<p>sudo apt-get install php5-sqlite</p>
<p>sudo /etc/init.d/postgresql-8.3 restart
<br/>sudo /etc/init.d/apache2 restart</p>

<p>www.mysql.com<br/>www.postgresql.org<br/>www.sqlite.org</p>
<p>This easy temp will be sorted in next release</p>
</div>";
}
?>
