### Install Application dependencies
1. Run
```curl -s https://getcomposer.org/installer | php composer.phar install```
2. Create mysql database
`'db1'`
3. Create table Product 
```
create table Product(
  id int auto_increment primary key,
  name varchar(250),
  category varchar(50),
  price double
);
```
### Variable (for paginator)
*$itemsPerPage = 5;*

**\src\Application\Service\ProductService.php**