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

    /**
     * @param int $page
     * @param int $limit
     * @param string $direction
     * @return array|null
     */
    public function getList(int $page = 1, int $limit = 2, string $direction = 'ASC'): ?array {
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new Exception('Не поддерживается');
        }

        $start = ($page -1) * $limit;
        $statement = $this->connection->prepare(
            'SELECT * FROM post ORDER BY published_date ' . $direction . ' LIMIT ' . $start . ',' . $limit
        );
        $statement->execute();
        return $statement->fetchAll();
    }
}