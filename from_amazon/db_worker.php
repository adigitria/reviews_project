<?php

/**
 * @param string $host
 * @param string $port
 * @param string $user
 * @param string $pass
 *
 * @param string $dbname
 *
 * @return PDO
 */
function createConnection(string $host, string $port, string $user, string $pass, string $dbname): ?PDO
{
    $dbh = null;

    // Database Params
    $charset = 'utf8';

    // Set DSN
    $dsn     = 'pgsql:host=' . $host . ' port='.$port.' dbname=' . $dbname;
    $options = [
        PDO::ATTR_PERSISTENT         => true,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE               => PDO::CASE_LOWER,
    ];

    //Create PDO Instance
    try {
        $dbh = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        $error = $e->getMessage();
        echo $error;
    }

    return $dbh;
}

/**
 * Prepare statement query
 *
 * @param PDO $dbh
 * @param     $sql
 *
 * @return PDOStatement
 */
function query(PDO $dbh, $sql): PDOStatement
{
    return $dbh->prepare($sql);
}

/**
 * Bind values
 *
 * @param PDOStatement $stmt
 * @param              $param
 * @param              $value
 * @param null         $type
 *
 * @return bool
 */
function bind(PDOStatement $stmt, $param, $value, $type = null): bool
{
    if ($type === null) {
        switch (true) {
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case $value === null:
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
                break;
        }
    }

    return $stmt->bindValue($param, $value, $type);
}

/**
 * Execute the prepared statement
 *
 * @param PDOStatement $stmt
 *
 * @return mixed
 */
function execute(PDOStatement $stmt): bool
{
    return $stmt->execute();
}

function addData(PDO $dbh, $table, $data): bool
{
    $updData = array_map(
        function (string $key, string $value) {
            return [
                'bindKey'   => ':' . $key,
                'fieldName' => $key,
                'value'     => $value,
            ];
        }, array_keys($data), $data
    );

    $stmt = query($dbh, 'INSERT INTO '.$table.' (' . implode(',', array_column($updData, 'fieldName')) . ') values (' . implode(',', array_column($updData, 'bindKey')) . ')');

    // Bind values
    foreach ($updData as $item) {
        bind($stmt, $item['bindKey'], $item['value']);
    }

    // Execute
    if (execute($stmt)) {
        return true;
    }

    return false;
}
