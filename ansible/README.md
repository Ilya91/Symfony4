ansible staging -m setup - get information about system
ansible staging -m shell -a 'uptime' - run any command via shell on remote server
ansible staging -m command -a 'ls /etc'
ansible staging -m copy -a 'src=README.md dest=/var/www/html' - copy file to remote host
ansible staging -m file -a 'path=/var/www/html/README.md state=absent' -b - remove file
ansible staging -m get_url -a 'url=https://www.greatpicture.ru/wp-content/uploads/2016/08/oshch-696x398-1.jpg dest=/var/www/html' -b - upload file from remote url
ansible staging -m yum -a 'name=stress state=installed' -b
ansible staging -m yum -a 'name=stress state=absent' -b
ansible staging -m uri -a 'url=https://onliner.by return_content=yes' - get content from url
ansible staging -m yum -a 'name=apache2 state=latest' -b
ansible staging -m service -a 'name=apache2 state=started enabled=yes' -b
ansible-doc -l  - show all modules