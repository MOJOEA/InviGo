# PHP-MVC

# Download template จาก Github
- https://github.com/potchara-msu/php-mvc.git

![[Pasted image 20251217202739.png]]

# การตั้งค่า
## การตั้งค่า  subdomain
- เพื่อสร้าง subdomain ของ host http://mvc.localhost
	- **mvc** คือ subdomain
	- **localhost** คือ ชื่อเครื่อง
	- จะตั้งชื่อ subdomain
		- http://mvc.localhost
	
## แก้ไขไฟล์ hosts
- **ไฟล์ hosts:** ไฟล์สำหรับกำหนดชื่อโดเมนกับที่อยู่ IP
	- Windows => `C:\Windows\System32\drivers\etc\hosts`
	- MacOS/Linux => `/etc/hosts`
		- เพิ่ม
			- `127.0.0.1 your_subdomain.localhost`
		- ตัวอย่าง
			- **`127.0.0.1 mvc.localhost`**

## ตั้งค่า Apache httpd.conf

### Windows 

![[Pasted image 20251217092503.png]]

![[Pasted image (2).png]]

### MacOS
![[Pasted image 20251217093024.png]]
![[Pasted image 20251217093101.png]]
## ตั้งค่า Apache httpd.conf

- **httpd.conf:** ไฟล์ตั้งค่าของเว็บเซิร์ฟเวอร์ Apache
	- ตรวจสอบ
		- Windows => `/XAMPP/apache/conf/etc/httpd.conf`
		- MacOS/Linux => `/Applications/XAMPP/xamppfiles/etc/httpd.conf`

- ค้นหา *vhosts* เพื่อเราจะได้ กำหนดค่าของ virtual host เองได้ และรู้ว่าต้องกำหนดค่าที่ไฟล์ไหน
	- ถ้าถูก  comment อยู่ให้เอา comment ออก
	- Include etc/extra/httpd-vhosts.conf

![[Pasted image 20241116174234.png]]

- ไปที่ไฟล์ **httpd-vhosts.conf**
	- เพิ่ม 2 virtual host
	- โดยตั้งค่าไปที่โฟลเดอร์ public
		- http://mvc.localhost => PATH_TO_PUBLIC_FOLDER

ตัวอย่าง
```xml
<VirtualHost *:80>
    ServerName mvc.localhost
    DocumentRoot "PATH_TO_PUBLIC_FOLDER"
    <Directory "PATH_TO_PUBLIC_FOLDER">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Windows
```xml
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:\xampp\htdocs"
    <Directory "C:\xampp\htdocs">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    ServerName mvc.localhost
    DocumentRoot "D:\temp\php\mvc\public"
    <Directory "D:\temp\php\mvc\public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Mac
```xml
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs"
    <Directory "/Applications/XAMPP/xamppfiles/htdocs">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>    
</VirtualHost>

<VirtualHost *:80>
    ServerName mvc.localhost
    DocumentRoot "/Applications/XAMPP/xamppfiles/htdocs/mvc/public"
    <Directory "/Applications/XAMPP/xamppfiles/htdocs/mvc/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>    
</VirtualHost>

```

- Restart Apache

![[Pasted image 20251217094854.png]]


Test call http://mvc.locahost

![[Pasted image 20251217205836.png]]
