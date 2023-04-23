[English](./README.md) | 中文

<div align="center">

# LARAVEL HASIN

<p>
    <a href="https://github.com/biiiiiigmonster/hasin/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-7389D8.svg?style=flat" ></a>
    <a href="https://github.com/biiiiiigmonster/hasin/releases" ><img src="https://img.shields.io/github/release/biiiiiigmonster/hasin.svg?color=4099DE" /></a> 
    <a href="https://packagist.org/packages/biiiiiigmonster/hasin"><img src="https://img.shields.io/packagist/dt/biiiiiigmonster/hasin.svg?color=" /></a> 
</p>

</div>

`hasin`是一个基于`where in`语法实现的`Laravel ORM`关联关系查询的扩展包，部分业务场景下可以替代`Laravel ORM`中基于`where exists`语法实现的`has`，以获取更高的性能。

## 安装

| Laravel 版本      | 安装命令                                                |
|-----------------|-----------------------------------------------------|
| Laravel 10      | ``` composer require biiiiiigmonster/hasin:^3.0 ``` |
| Laravel 9       | ``` composer require biiiiiigmonster/hasin:^2.0 ``` |
| Laravel 5.5 ~ 8 | ``` composer require biiiiiigmonster/hasin:^1.0 ``` |

## 简介

`Laravel ORM`的关联关系非常强大，基于关联关系的查询`has`也给我们提供了诸多灵活的调用方式，然而某些情形下，`has`使用了**where exists**语法实现

例如:
```php
// User hasMany Post
User::has('posts')->get();
```
#### `select * from users where exists (select * from posts where users.id = posts.user_id)`
> exists是对外表做loop循环，每次loop循环再对内表（子查询）进行查询，那么因为对内表的查询使用的索引（内表效率高，故可用大表），而外表有多大都需要遍历，不可避免（尽量用小表），故内表大的使用exists，可加快效率。

但是当**User表**数据量较大的时候，就会出现性能问题，那么这时候用**where in**语法将会极大的提高性能

#### `select * from users where users.id in (select posts.user_id from posts)`
> in是把外表和内表做hash连接，先查询内表，再把内表结果与外表匹配，对外表使用索引（外表效率高，可用大表），而内表多大都需要查询，不可避免，故外表大的使用in，可加快效率。

因此在代码中使用`has(hasMorph)`或者`hasIn(hasMorphIn)`应由**数据体量**来决定……

```php
/**
 * SQL:
 * 
 * select * from `users` 
 * where exists 
 *   ( 
 *      select * from `posts` 
 *      where `users`.`id` = `posts`.`user_id` 
 *   ) 
 * limit 10 offset 0
 */
$users = User::has('posts')->paginate(10);

/**
 * SQL:
 * 
 * select * from `users` 
 * where `users`.`id` in  
 *   ( 
 *      select `posts`.`user_id` from `posts` 
 *   ) 
 * limit 10 offset 0
 */
$users = User::hasIn('posts')->paginate(10);
```

> `Laravel ORM`十种关联关系多达248种实际业务case sql输出可查看[有道云笔记](https://note.youdao.com/noteshare?id=882bfd7ccdf1370c55326a33333c6f62)

## 使用

此扩展方法`hasIn(hasMorphIn)`支持`Laravel ORM`中的所有关联关系，入参调用及内部实现流程与框架的`has(hasMorph)`完全一致。

> hasIn

```php
// hasIn
User::hasIn('posts')->get();

// orHasIn
User::where('age', '>', 18)->orHasIn('posts')->get();

// doesntHaveIn
User::doesntHaveIn('posts')->get();

// orDoesntHaveIn
User::where('age', '>', 18)->orDoesntHaveIn('posts')->get();
```

> whereHasIn

```php
// whereHasIn
User::whereHasIn('posts', function ($query) {
    $query->where('votes', '>', 10);
})->get();

// orWhereHasIn
User::where('age', '>', 18)->orWhereHasIn('posts', function ($query) {
    $query->where('votes', '>', 10);
})->get();

// whereDoesntHaveIn
User::whereDoesntHaveIn('posts', function ($query) {
    $query->where('votes', '>', 10);
})->get();

// orWhereDoesntHaveIn
User::where('age', '>', 18)->orWhereDoesntHaveIn('posts', function ($query) {
    $query->where('votes', '>', 10);
})->get();
```

> hasMorphIn

```php
Image::hasMorphIn('imageable', [Post::class, Comment::class])->get();
```

### 嵌套关联

```php
User::hasIn('posts.comments')->get();
```

## 测试
```bash
composer test
```

## 联系交流
wx：biiiiiigmonster(备注：hasin)

## 协议
[MIT 协议](LICENSE)
