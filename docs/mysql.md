## Mysql setup
```sql
CREATE TABLE `channel` (
	`id` CHAR(32) NOT NULL,
	`value` LONGTEXT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
```