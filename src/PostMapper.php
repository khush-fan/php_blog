<?php
declare(strict_types=1);

namespace Blog;

use mysql_xdevapi\Exception;
use PDO;

class PostMapper
{
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * PostMapper constructor.
     * @param PDO $connection
     */
    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @param string $urlKey
     * @return array
     */
    public function getByUrlKey(string $urlKey): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM post WHERE url_key = :url_key');
        $statement->execute([
            'url_key' => $urlKey
        ]);
        $result = $statement->fetchAll();
        return array_shift($result);
    }


    public function getList(string $direction): ?array {
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new Exception('Не поддерживается');
        }
        $statement = $this->connection->prepare('SELECT * FROM post ORDER BY published_date ' . $direction);
        $statement->execute();
        return $statement->fetchAll();
    }
}