use integration_background;
set names utf8;

update admin_user set password_algorithm_system=9 where password_algorithm_system=5;