[English](./README.md) | 中文

<div align="center">

# LARAVEL HASIN

<p>
    <a href="https://github.com/biiiiiigmonster/hasin/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-7389D8.svg?style=flat" ></a>
    <a href="https://github.com/biiiiiigmonster/hasin/releases" ><img src="https://img.shields.io/github/release/biiiiiigmonster/hasin.svg?color=4099DE" /></a> 
    <a href="https://packagist.org/packages/biiiiiigmonster/hasin"><img src="https://img.shields.io/packagist/dt/biiiiiigmonster/hasin.svg?color=" /></a> 
    <a><img src="https://img.shields.io/badge/php-7+-59a9f8.svg?style=flat" /></a> 
</p>

</div>

`hasin`是一个基于`where in`语法实现的`Laravel ORM`关联关系查询的扩展包，部分业务场景下可以替代`Laravel ORM`中基于`where exists`语法实现的`has`，以获取更高的性能。


## 环境

- PHP >= 7.1
- laravel >= 5.8


## 安装

```bash
composer require biiiiiigmonster/hasin
```

## 简介

`Laravel ORM`的关联关系非常强大，基于关联关系的查询`has`也给我们提供了诸多灵活的调用方式，然而某些情形下，`has`使用了**where exists**语法实现

#### `select * from A where exists (select * from B where A.id=B.a_id)`
> exists是对外表做loop循环，每次loop循环再对内表（子查询）进行查询，那么因为对内表的查询使用的索引（内表效率高，故可用大表），而外表有多大都需要遍历，不可避免（尽量用小表），故内表大的使用exists，可加快效率。

但是当**A表**数据量较大的时候，就会出现性能问题，那么这时候用**where in**语法将会极大的提高性能

#### `select * from A where A.id in (select B.a_id from B)`
> in是把外表和内表做hash连接，先查询内表，再把内表结果与外表匹配，对外表使用索引（外表效率高，可用大表），而内表多大都需要查询，不可避免，故外表大的使用in，可加快效率。

因此建议在代码中使用`hasIn(hasMorphIn)`来代替`has(hasMorph)`来获取更高的性能……

```php
<?php
/**
 * SQL:
 * 
 * select * from `product` 
 * where exists 
 *   ( 
 *      select * from `product_skus` 
 *      where `product`.`id` = `product_skus`.`p_id` 
 *      and `product_skus`.`deleted_at` is null 
 *   ) 
 * and `product`.`deleted_at` is null 
 * limit 10 offset 0
 */
$products = Product::has('skus')->paginate(10);

/**
 * SQL:
 * 
 * select * from `product` 
 * where `product`.`id` IN  
 *   ( 
 *      select `product_skus`.`p_id` from `product_skus` 
 *      and `product_skus`.`deleted_at` is null 
 *   ) 
 * and `product`.`deleted_at` is null 
 * limit 10 offset 0
 */
$products = Product::hasIn('skus')->paginate(10);
```

> `Laravel ORM`十种关联关系多达248种实际业务case sql输出可查看[有道云笔记](https://note.youdao.com/noteshare?id=882bfd7ccdf1370c55326a33333c6f62)

## 使用

在配置文件app.php添加配置，自动注册服务
```php
<?php
    // ...
    
    'providers' => [
        // ...
        
        BiiiiiigMonster\Hasin\HasinServiceProvider::class,// hasin扩展包引入
    ],
```
此扩展方法`hasIn(hasMorphIn)`支持`Laravel ORM`中的所有关联关系，入参调用及内部实现流程与框架的`has(hasMorph)`完全一致，可安全使用或替换

> hasIn

```php
// hasIn
Product::hasIn('skus')->get();

// orHasIn
Product::where('name', 'like', '%拌饭酱%')->orHasIn('skus')->get();

// doesntHaveIn
Product::doesntHaveIn('skus')->get();

// orDoesntHaveIn
Product::where('name', 'like', '%拌饭酱%')->orDoesntHaveIn('skus')->get();
```

> whereHasIn

```php
// whereHasIn
Product::whereHasIn('skus', function ($query) {
    $query->where('sales', '>', 10);
})->get();

// orWhereHasIn
Product::where('name', 'like', '%拌饭酱%')->orWhereHasIn('skus', function ($query) {
    $query->where('sales', '>', 10);
})->get();

// whereDoesntHaveIn
Product::whereDoesntHaveIn('skus', function ($query) {
    $query->where('sales', '>', 10);
})->get();

// orWhereDoesntHaveIn
Product::where('name', 'like', '%拌饭酱%')->orWhereDoesntHaveIn('skus', function ($query) {
    $query->where('sales', '>', 10);
})->get();
```

> hasMorphIn

```php
Image::hasMorphIn('imageable', [Product::class, Brand::class])->get();
```

### 嵌套关联

```php
Product::hasIn('attrs.values')->get();
```

### 自关联
```php
Category::hasIn('children')->get();
```

## 联系交流
wx：biiiiiigmonster(备注：hasin)

## 协议
[MIT 协议](LICENSE)
