<?php

use Dotenv\Dotenv;

require 'bootstrap/autoload.php';

/**
 * 总结知识点
 * PDO
 *      PDO::exec() 在一个单独的函数调用中执行一条 SQL 语句，返回受此语句影响的行数。比如增删改,
 *                  如果是查询PDO::exec() 不会从一条 SELECT 语句中返回结果。对于在程序中只需要发出一次的 SELECT 语句，
 *                  可以考虑使用 PDO::query()。对于需要发出多次的语句，可用 PDO::prepare() 来准备一个 PDOStatement 对象并用 PDOStatement::execute() 发出语句。
 *      PDO::quote() 为SQL语句中的字符串添加引号或者转义特殊字符串。
 *      public string PDO::quote ( string $string [, int $parameter_type = PDO::PARAM_STR ] )
 *          实例：
 *              $name = 'ddd';
                $name = $DB->quote($name);
                $sql = 'select * from first where name = '.$name;
                $result = $DB->query($sql);
                var_dump($result->fetchAll());
 *     PDO::query — 执行 SQL 语句，返回PDOStatement对象,可以理解为结果集
 *     PDO::getAttribute — 取回一个数据库连接的属性
 *          attribute
                PDO::ATTR_* 常量中的一个。下列为应用到数据库连接中的常量：
                •PDO::ATTR_AUTOCOMMIT
                •PDO::ATTR_CASE
                •PDO::ATTR_CLIENT_VERSION
                •PDO::ATTR_CONNECTION_STATUS
                •PDO::ATTR_DRIVER_NAME
                •PDO::ATTR_ERRMODE
                •PDO::ATTR_ORACLE_NULLS
                •PDO::ATTR_PERSISTENT
                •PDO::ATTR_PREFETCH
                •PDO::ATTR_SERVER_INFO
                •PDO::ATTR_SERVER_VERSION
                •PDO::ATTR_TIMEOUT
 *
 *     PDO::getAvailableDrivers — 返回一个可用驱动的数组
 *          $ds = $DB->getAvailableDrivers();
            var_dump($ds);
            array (size=2)
            0 => string 'mysql' (length=5)
            1 => string 'sqlite' (length=6)
 *      PDO::lastInsertId — 返回最后插入行的ID或序列值
 *              string PDO::lastInsertId ([ string $name = NULL ] )
 *      PDO::inTransaction — 检查是否在一个事务内
 *      PDO::setAttribute — 设置属性
            PDO::ATTR_CASE：强制列名为指定的大小写。
                ◦ PDO::CASE_LOWER：强制列名小写。
                ◦ PDO::CASE_NATURAL：保留数据库驱动返回的列名。
                ◦ PDO::CASE_UPPER：强制列名大写。
            ◦PDO::ATTR_ERRMODE：错误报告。
                ◦PDO::ERRMODE_SILENT：仅设置错误代码。
                ◦PDO::ERRMODE_WARNING: 引发 E_WARNING 错误
                ◦PDO::ERRMODE_EXCEPTION: 抛出 exceptions 异常。
            ◦PDO::ATTR_ORACLE_NULLS （在所有驱动中都可用，不仅限于Oracle）：转换 NULL 和空字符串。
                ◦PDO::NULL_NATURAL: 不转换。
                ◦PDO::NULL_EMPTY_STRING：将空字符串转换成 NULL。
                ◦PDO::NULL_TO_STRING: 将 NULL 转换成空字符串。
            ◦PDO::ATTR_STRINGIFY_FETCHES: 提取的时候将数值转换为字符串。 Requires bool.
            ◦PDO::ATTR_STATEMENT_CLASS：设置从PDOStatement派生的用户提供的语句类。不能用于持久的PDO实例。需要 array(string 类名, array(mixed 构造函数的参数))。
            ◦PDO::ATTR_TIMEOUT：指定超时的秒数。并非所有驱动都支持此选项，这意味着驱动和驱动之间可能会有差异。比如，SQLite等待的时间达到此值后就放弃获取可写锁，但其他驱动可能会将此值解释为一个连接或读取超时的间隔。需要 int 类型。
            ◦PDO::ATTR_AUTOCOMMIT （在OCI，Firebird 以及 MySQL中可用）：是否自动提交每个单独的语句。
            ◦PDO::ATTR_EMULATE_PREPARES 启用或禁用预处理语句的模拟。 有些驱动不支持或有限度地支持本地预处理。使用此设置强制PDO总是模拟预处理语句（如果为 TRUE ），或试着使用本地预处理语句（如果为 FALSE）。如果驱动不能成功预处理当前查询，它将总是回到模拟预处理语句上。需要 bool 类型。
            ◦PDO::MYSQL_ATTR_USE_BUFFERED_QUERY （在MySQL中可用）：使用缓冲查询。
            ◦PDO::ATTR_DEFAULT_FETCH_MODE：设置默认的提取模式。关于模式的说明可以在 PDOStatement::fetch() 文档找到。
                * PDO::FETCH_ASSOC：返回一个索引为结果集列名的数组
                ◦ PDO::FETCH_BOTH（默认）：返回一个索引为结果集列名和以0开始的列号的数组
                ◦ PDO::FETCH_BOUND：返回 TRUE ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量。
                ◦ PDO::FETCH_CLASS：返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名。如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS |PDO::FETCH_CLASSTYPE），则类名由第一列的值决定
                ◦ PDO::FETCH_INTO：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性
                ◦ PDO::FETCH_LAZY：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名
                ◦ PDO::FETCH_NUM：返回一个索引为以0开始的结果集列号的数组
                ◦ PDO::FETCH_OBJ：返回一个属性名对应结果集列名的匿名对象
 *
            注意：为了让mysql服务器去拼凑sql，而不是web server去拼凑，必须在创建pdo对象的时候加个参数：
            这个参数叫模拟prepare，默认是TRUE，意思是让web server代替mysql去做prepare，达到模拟prepare的作用。(web server实现模拟prepare的原理其实也就是底层用系统函数自行拼凑sql，和手动拼凑没区别，所以还是会把危险的sql拼凑进去，然后给mysql服务器执行，依然会被sql注入)
            所以必须设置为FALSE。
            setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
 *          //同时这个属性与 PDO::ATTR_STRINGIFY_FETCHES => false,配合，可以帮我们禁止了类型转换成字符串这种问题
 *
 *          PDO::ATTR_STRINGIFY_FETCHES => false  //是否将取出来的数据转换成字符串类型
 *
 *
 *  PDOStatement 类
 *
 *
 *
 *
 *
 */

/**
 * 环境变量composer包
 */
//$dotenv  =  Dotenv::create(__DIR__);
//$dotenv->load();
//$bbb = $_ENV;
//$aaa = getenv('APP_KEY');
//var_dump($aaa);
//var_dump($bbb);

try {
    $DB = new PDO('mysql:host=127.0.0.1;port=3306;dbname=shechunxiao;charset=UTF8;','root','', [
        PDO::ATTR_PERSISTENT=>false,
        PDO::ATTR_CASE=>PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //关联数组
//        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,  //对象
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_TO_STRING,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES=>false
    ]);

    /**
     * 事务提交的问题
     */

//    $DB->setAttribute(PDO::ATTR_AUTOCOMMIT,false);  //设置手动提交事务
//    $DB->beginTransaction();
//    $res = $DB->query('update first set name= "dddds2323" where id>1 and id <4');
//    echo $res->rowCount();
//    if ($res->rowCount()){
//        $DB->commit();
//    }else{
//        $DB->rollBack();
//    }


//    $DB->setAttribute(PDO::ATTR_ORACLE_NULLS,PDO::NULL_TO_STRING);
//    print $DB->errorCode();
//    print_r($DB->errorInfo());
//    $r = $DB->exec('update first set name= "ddd" where id=1');
//    var_dump($r);
//    $name = 'ddd';
//    $name = $DB->quote($name);
//    $sql = 'select * from first where name = '.$name;
//    print_r($sql);
//    echo '<br>';
//    $result = $DB->query($sql);
//    var_dump($result->fetchAll());
//    $ds = $DB->getAvailableDrivers();
//    var_dump($ds);
//    $DB->exec('insert into first (name) values ("fdsfdds")');
//    $id = $DB->lastInsertId();
//    print $id;

//    $isTrasaction = $DB->inTransaction();
//    print $isTrasaction;

//    print $DB->getAttribute(PDO::ATTR_AUTOCOMMIT).'<br/>'; //如果此值为 FALSE ，PDO 将试图禁用自动提交以便数据库连接开始一个事务。
//    print $DB->getAttribute(PDO::ATTR_CASE).'<br/>';
//    //用类似 PDO::CASE_* 的常量强制列名为指定的大小写。 可选的值有,PDO::CASE_NATURAL(integer) 保留数据库驱动返回的列名。PDO::CASE_LOWER(integer) 强制列名小写。
//    //PDO::CASE_UPPER(integer) 强制列名大写。
//    print $DB->getAttribute(PDO::ATTR_CLIENT_VERSION).'<br/>'; //此为只读属性；返回 PDO 驱动所用客户端库的版本信息。
//    print $DB->getAttribute(PDO::ATTR_CONNECTION_STATUS).'<br/>';
//    print $DB->getAttribute(PDO::ATTR_DRIVER_NAME).'<br/>'; //PDO::ATTR_DRIVER_NAME(string) 返回驱动名称。
//    print $DB->getAttribute(PDO::ATTR_ERRMODE).'<br/>';
//    //PDO::ATTR_ERRMODE(integer) 关于此属性的更多信息请参见 错误及错误处理部分。PDO::ERRMODE_SILENT  此为默认模式。 PDO 将只简单地设置错误码
//    //PDO::ERRMODE_WARNING ,除设置错误码之外，PDO 还将发出一条传统的 E_WARNING 信息
//
//    print $DB->getAttribute(PDO::ATTR_ORACLE_NULLS).'<br/>';
//    print $DB->getAttribute(PDO::ATTR_PERSISTENT).'<br/>'; //请求一个持久连接，而非创建一个新连接。
//    print $DB->getAttribute(PDO::ATTR_SERVER_INFO).'<br/>'; //此为只读属性。返回一些关于 PDO 所连接的数据库服务的元信息。
//    print $DB->getAttribute(PDO::ATTR_SERVER_VERSION).'<br/>'; //此为只读属性；返回 PDO 所连接的数据库服务的版本信息。
//    print $DB->getAttribute(PDO::ATTR_TIMEOUT).'<br/>'; //mysql不支持
//    print $DB->getAttribute(PDO::ATTR_PREFETCH).'<br/>';  //mysql不支持
//    $result = $DB->query('select * from first where id=1');
//    $result = $DB->query('select count(*) as number from first where id=1');
//    var_dump($result->columnCount());
//    var_dump($result);
//    var_dump($result->fetchObject());
//    var_dump($result->fetchColumn(1));
//    var_dump($result->getColumnMeta(1));
//    var_dump($result->fetch());
//    var_dump($result->fetch());
//    var_dump($result->fetch());
//    var_dump($result->fetch());
//    $re = $result->fetch();
////    $re = $result->fetch(PDO::FETCH_ASSOC);
//    var_dump($re);

//    throw new InvalidArgumentException('Non-numeric value passed to increment method.');
//    $query = "select id,name from first where id = :id";
//    $st = $DB->prepare($query);
//    $st->execute([':id'=>2]);
//    $re = $st->fetchAll();
//    var_dump($re);

//    foreach ($re as $value){
//        $te[] = $value['test'];
//    }
//    var_dump($te);
//
//    echo 'delete/insert/update影响的行数:'.$result->rowCount().'<br>';
//    echo '返回结果集中的列数:'.$result->columnCount().'<br>';


} catch (PDOException $e) {

    print "Error44!: " . $e->getMessage() . "<br/>";
    die();
}