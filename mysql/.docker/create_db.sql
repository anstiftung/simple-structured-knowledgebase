CREATE DATABASE IF NOT EXISTS cowiki;
GRANT ALL PRIVILEGES ON cowiki.* TO 'cowiki'@'%';
CREATE DATABASE IF NOT EXISTS test;
GRANT ALL PRIVILEGES ON test.* TO 'cowiki'@'%';