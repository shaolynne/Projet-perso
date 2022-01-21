from ftplib import FTP
user = 'ftp_user'
passwd = 'ftp_pass'
dest='sym37tnr'
ftp = FTP(user=user, passwd=passwd)
ftp.connect(dest,22)
ftp.login(user,passwd)
with open('referentiel_des_environnements.xlsx', 'wb') as fp:
    ftp.retrbinary('RETR /Referentiel_des_environnements/referentiel_des_environnements.xlsx', fp.write)
ftp.quit()