# <p align='center'>友链自助编辑</p>

<p align='center'>一个让访客自助编辑博客友链的程序</p>
<p align='center'>主要为Wordpress设计，其他平台请自行修改以适配数据库</p>
<p align='center'>求Star~</p>

## 特性 Feature
- [x] **适配Wordpress主题** - 不影响主题全局效果
- [x] **邮件通知管理员** - 便于管理员及时处理
- [x] **阻挡非法访问** - 通过HTTP_REFERER和提交数据判断，提高安全性
- [x] **自助申请友链** - 访客可自行申请友链，仅需管理员后台同意申请
- [ ] **自助修改友链** - 对于预留邮箱的友链，通过邮箱验证后可修改友链
- [ ] **自助删除友链** - 对于预留邮箱的友链，通过邮箱验证后可删除友链

## 效果
![image](https://fastly.jsdelivr.net/gh/awerailgun/filescdn@main/other%20image/link1.jpg)
![image](https://fastly.jsdelivr.net/gh/awerailgun/filescdn@main/other%20image/link2.jpg)

## 使用方法
1. 下载本仓库到服务器
2. 按提示编辑<code>friends_add.php</code>
3. 复制<code>index.html</code>里的内容到要显示的页面，记得修改<code>friends_add.php</code>文件路径

## 演示
<a href="https://miku.ie/test/selfmodifylinks">https://miku.ie/test/selfmodifylinks</a>

## 引用 
<a href="https://github.com/PHPMailer/PHPMailer/">PHPMailer</a>


