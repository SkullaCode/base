<?php


namespace App\Interfaces;


use PDOStatement;

interface IMedoo
{
    /**
     * @param string $query
     * @param array $map
     * @return bool|PDOStatement
     */
    public function query($query, $map = []);

    /**
     * @param string $query
     * @param array $map
     * @return bool|PDOStatement
     */
    public function exec($query, $map = []);

    /**
     * @param string $string
     * @param array $map
     * @return object
     */
    public static function raw($string, $map = []);

    /**
     * @param string $string
     * @return string
     */
    public function quote($string);

    /**
     * @param string $table
     * @param array|string $join
     * @param null|array $columns
     * @param null|array $where
     * @return array|bool
     */
    public function select($table, $join, $columns = null, $where = null);

    /**
     * @param string $table
     * @param array $datas
     * @return bool|PDOStatement
     */
    public function insert($table, $datas);

    /**
     * @param string $table
     * @param array $data
     * @param null|array $where
     * @return bool|PDOStatement
     */
    public function update($table, $data, $where = null);

    /**
     * @param string $table
     * @param array $where
     * @return bool|PDOStatement
     */
    public function delete($table, $where);

    /**
     * @param string $table
     * @param array $columns
     * @param null|array $where
     * @return bool|PDOStatement
     */
    public function replace($table, $columns, $where = null);

    /**
     * @param string $table
     * @param null|array $join
     * @param null|array $columns
     * @param null|array $where
     * @return array
     */
    public function get($table, $join = null, $columns = null, $where = null);

    /**
     * @param string $table
     * @param array $join
     * @param null|array $where
     * @return bool
     */
    public function has($table, $join, $where = null);

    /**
     * @param string $table
     * @param null|array $join
     * @param null|array $columns
     * @param null|array $where
     * @return array|bool
     */
    public function rand($table, $join = null, $columns = null, $where = null);

    /**
     * @param string $table
     * @param null|array $join
     * @param null|array $column
     * @param null|array $where
     * @return bool|int|string
     */
    public function count($table, $join = null, $column = null, $where = null);

    /**
     * @param string $table
     * @param array $join
     * @param null|array $column
     * @param null|array $where
     * @return bool|int|string
     */
    public function avg($table, $join, $column = null, $where = null);

    /**
     * @param string $table
     * @param array $join
     * @param null|array $column
     * @param null|array $where
     * @return bool|int|string
     */
    public function max($table, $join, $column = null, $where = null);

    /**
     * @param string $table
     * @param array $join
     * @param null|array $column
     * @param null|array $where
     * @return bool|int|string
     */
    public function min($table, $join, $column = null, $where = null);

    /**
     * @param string $table
     * @param array $join
     * @param null|array $column
     * @param null|array $where
     * @return bool|int|string
     */
    public function sum($table, $join, $column = null, $where = null);

    /**
     * @param callable $actions
     * @return bool
     */
    public function action($actions);

    /**
     * @return int|string
     */
    public function id();

    /**
     * @return $this
     */
    public function debug();

    /**
     * @return null|array|string
     */
    public function error();

    /**
     * @return null|string
     */
    public function last();

    /**
     * @return array
     */
    public function log();

    /**
     * @return array
     */
    public function info();
}