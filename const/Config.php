<?php

/**
 * @brief const_config类
 * @author strick@beishanwen.com
 * @date 2017-07-17
 */
class Const_Config
{
    const IS_OFFLINE = true;
    const DB_NAME = 'db_whats_star';
    const DB_TYPE = 1;

    const PG_CONN = array(
        'conn_name' => 'pg_whats_star',
        'host_ip' => '',
        'host_port' => 0,
        'db_name' => '',
        'user_name' => '',
        'user_passwd' => '',
    );

    const REDIS_CONN = array(
        'conn_name' => 'redis_whats_star',
        'host_ip' => '127.0.0.1',
        'host_port' => 6379,
    );
}
