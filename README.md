<div align="center">

# LARAVEL HASIN

<p>
    <a href="https://github.com/biiiiiigmonster/hasin/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-7389D8.svg?style=flat" ></a>
    <a href="https://github.com/biiiiiigmonster/hasin/releases" ><img src="https://img.shields.io/github/release/biiiiiigmonster/hasin.svg?color=4099DE" /></a> 
    <a href="https://packagist.org/packages/biiiiiigmonster/hasin"><img src="https://img.shields.io/packagist/dt/biiiiiigmonster/hasin.svg?color=" /></a> 
    <a><img src="https://img.shields.io/badge/php-7+-59a9f8.svg?style=flat" /></a> 
</p>

</div>

`hasin(hasMorphIn)`是一个基于`where in`语法实现的`Laravel ORM`关联关系查询的扩展包，部分业务场景下可以替代`Laravel ORM`中基于`where exists`语法实现的`has(hasMorphIn)`，以获取更高的性能。


## 环境

- PHP >= 7
- laravel >= 5.5


## 安装

```bash
composer require biiiiiigmonster/hasin
```

### 简介

`Laravel`的关联关系查询`whereHas`在日常开发中给我们带来了极大的便利，但是在**主表**数据量比较多的时候会有比较严重的性能问题，主要是因为`whereHas`用了`where exists (select * ...)`这种方式去查询关联数据。


通过这个扩展包提供的`whereHasIn`方法，可以把语句转化为`where id in (select xxx.id ...)`的形式，从而提高查询性能，下面我们来做一个简单的对比：


> 当主表数据量较多的情况下，`where id in`会有明显的性能提升；当主表数据量较少的时候，两者性能相差无几。


主表`test_users`写入`130002`条数据，关联表`test_user_profiles`写入`1002`条数据，查询代码如下

```php
<?php
/**
 * SQL:
 * 
 * select * from `test_users` where exists
 *   (
 *     select * from `test_user_profiles` 
 *     where `test_users`.`id` = `test_user_profiles`.`user_id`
 *  ) 
 * limit 10
 */
$users1 = User::whereHas('profile')->limit(10)->get();

/**
 * SQL:
 * 
 * select * from `test_users` where `test_users`.`id` in 
 *   (
 *     select `test_user_profiles`.`user_id` from `test_user_profiles` 
 *     where `test_users`.`id` = `test_user_profiles`.`user_id`
 *   ) 
 * limit 10
 */
$users1 = User::whereHasIn('profile')->limit(10)->get();
```

最终耗时如下，可以看出性能相差还是不小的，如果数据量更多一些，这个差距还会更大

```bash
whereHas   0.50499701499939 秒
whereHasIn 0.027166843414307 秒
```


### 使用

此扩展`hasIn(hasMorphIn)`支持`Laravel ORM`中的所有关联关系，入参及使用方式与`has(hasMorph)`完全一致，可安全替换

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

#### 嵌套关联

```php
Product::hasIn('attrs.values')->get();
```

## License
[MIT 协议](LICENSE).
