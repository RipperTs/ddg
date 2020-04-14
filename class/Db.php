<?php

/**
 * 数据库操作类
 * Class Db
 */
class Db
{

    public function __construct($config)
    {
        $dbms = 'mysql';                //数据库类型
        $host = $config['host'];        //数据库主机名
        $user = $config['user'];      //数据库连接用户名
        $pass = $config['password'];  //对应的密码

        $dsn = "$dbms:host=$host;charset=utf8";

        try {
            $this->db = new PDO($dsn, $user, $pass); //初始化一个PDO对象
        } catch (PDOException $e) {
            exit ("Error!: " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * 获取所有数据
     * @param $sql
     * @return array
     */
    public function getAll($sql)
    {
        $sth = $this->db->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}