<?php
@include "inc.page.php";
$SIDU['page']['nav'] = 'defa';
@uppe();
@main();
@down();

function main()
{
	echo "<div class='web'><p><b>Database User</b> -- available in next release -- or you can download v2.4 from sidu.sf.net which has this feature</p>
<pre>
<b>MySQL</b>

REVOKE ALL PRIVILEGES ON *.* FROM user;
REVOKE GRANT OPTION ON *.* FROM user;

GRANT USAGE ON *.* TO user;
GRANT ALL PRIVILEGES ON *.* TO user WITH GRANT OPTION;

GRANT SELECT,INSERT,DELETE,UPDATE,FILE,
SUPER,RELOAD,SHUTDOWN,PROCESS,REFERENCES,SHOW DATABASES,LOCK TABLES,
REPLICATION SLAVE,REPLICATION CLIENT,CREATE USER 
ON *.* TO user WITH GRANT OPTION
MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 
MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;

REVOKE ALL PRIVILEGES ON db.* FROM user;
REVOKE GRANT OPTION ON db.* FROM user;

GRANT USAGE USAGE ON db.* TO user;
GRANT ALL PRIVILEGES ON db.* TO user WITH GRANT OPTION;

REVOKE ALL PRIVILEGES ON db.tab FROM user;
REVOKE GRANT OPTION ON db.tab FROM user;

GRANT USAGE USAGE ON db.tab TO user;
GRANT ALL PRIVILEGES ON db.tab TO user WITH GRANT OPTION;

<b>Postgres</b>

CREATE ROLE ben LOGIN ENCRYPTED PASSWORD 'md5021fae7a1b5955'
  SUPERUSER NOINHERIT CREATEDB CREATEROLE
  VALID UNTIL 'infinity';
COMMENT ON ROLE benb IS 'comm';

CREATE USER name SUPERUSER CREATEDB CREATEROLE CREATEUSER INHERIT LOGIN
ALTER Role postgres ENCRYPTED PASSWORD 'md5965fb1f623b2c';

DB:Find Variables process Lock Admin Privileges Export Create Drop Alter
Sch:Find Priv Create Drop Alter
Tab:analyze vaccum empty drop create alter


</pre>

<table class='grid'>
<tr class='th'><td>user</td><td>super</td><td>+db</td><td>+role</td><td>inherit</td><td>conn limit</td><td>expire</td></tr>
<tr><td>ben</td><td>Y</td><td>Y</td><td>Y</td><td>Y</td><td>no limit</td><td>never</td></tr>
</table>
</div>";
}
?>
