<?php

/**
 * Description of BookingDao
 *
 * @author richard_lovell
 */
class BookingDao {
    
    /** @var PDO */
    private $db = null;


    public function __destruct() {
        // close db connection
        $this->db = null;
    }

    /**
     * Find all {@link Booking}s by search criteria.
     * @return array array of {@link Booking}s
     */
    public function find($sql) {
        $result = array();
        foreach ($this->query($sql) as $row) {
            $booking = new Booking();
            BookingMapper::map($booking, $row);
            $result[$booking->getId()] = $booking;
        }
        return $result;
    }

    /**
     * Find {@link Todo} by identifier.
     * @return Todo Todo or <i>null</i> if not found
     */
//    public function findById($id) {
//        $row = $this->query('SELECT * FROM todo WHERE deleted = 0 and id = ' . (int) $id)->fetch();
//        if (!$row) {
//            return null;
//        }
//        $todo = new Todo();
//        TodoMapper::map($todo, $row);
//        return $todo;
//    }

    /**
     * Save {@link Todo}.
     * @param ToDo $todo {@link Todo} to be saved
     * @return Todo saved {@link Todo} instance
     */
//    public function save(ToDo $todo) {
//        if ($todo->getId() === null) {
//            return $this->insert($todo);
//        }
//        return $this->update($todo);
//    }

    /**
     * Delete {@link Todo} by identifier.
     * @param int $id {@link Todo} identifier
     * @return bool <i>true</i> on success, <i>false</i> otherwise
     */
    public function delete($id) {
        $sql = '
            UPDATE todo SET
                last_modified_on = :last_modified_on,
                deleted = :deleted
            WHERE
                id = :id';
        $statement = $this->getDb()->prepare($sql);
        $this->executeStatement($statement, array(
            ':last_modified_on' => self::formatDateTime(new DateTime()),
            ':deleted' => true,
            ':id' => $id,
        ));
        return $statement->rowCount() == 1;
    }

    /**
     * @return PDO
     */
    private function getDb() {
        if ($this->db !== null) {
            return $this->db;
        }
        $config = Config::getConfig("db");
        try {
            $this->db = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (Exception $ex) {
            throw new Exception('DB connection error: ' . $ex->getMessage());
        }
        return $this->db;
    }

//    private function getFindSql(TodoSearchCriteria $search = null) {
//        $sql = 'SELECT * FROM todo WHERE deleted = 0 ';
//        $orderBy = ' priority, due_on';
//        if ($search !== null) {
//            if ($search->getStatus() !== null) {
//                $sql .= 'AND status = ' . $this->getDb()->quote($search->getStatus());
//                switch ($search->getStatus()) {
//                    case Todo::STATUS_PENDING:
//                        $orderBy = 'due_on, priority';
//                        break;
//                    case Todo::STATUS_DONE:
//                    case Todo::STATUS_VOIDED:
//                        $orderBy = 'due_on DESC, priority';
//                        break;
//                    default:
//                        throw new Exception('No order for status: ' . $search->getStatus());
//                }
//            }
//        }
//        $sql .= ' ORDER BY ' . $orderBy;
//        return $sql;
//    }

    /**
     * @return Todo
     * @throws Exception
     */
//    private function insert(Todo $todo) {
//        $now = new DateTime();
//        $todo->setId(null);
//        $todo->setCreatedOn($now);
//        $todo->setLastModifiedOn($now);
//        $todo->setStatus(Todo::STATUS_PENDING);
//        $sql = '
//            INSERT INTO todo (id, priority, created_on, last_modified_on, due_on, title, description, comment, status, deleted)
//                VALUES (:id, :priority, :created_on, :last_modified_on, :due_on, :title, :description, :comment, :status, :deleted)';
//        return $this->execute($sql, $todo);
//    }

    /**
     * @return Todo
     * @throws Exception
     */
//    private function update(Todo $todo) {
//        $todo->setLastModifiedOn(new DateTime());
//        $sql = '
//            UPDATE todo SET
//                priority = :priority,
//                last_modified_on = :last_modified_on,
//                due_on = :due_on,
//                title = :title,
//                description = :description,
//                comment = :comment,
//                status = :status,
//                deleted = :deleted
//            WHERE
//                id = :id';
//        return $this->execute($sql, $todo);
//    }

    /**
     * @return Todo
     * @throws Exception
     */
//    private function execute($sql, Todo $todo) {
//        $statement = $this->getDb()->prepare($sql);
//        $this->executeStatement($statement, $this->getParams($todo));
//        if (!$todo->getId()) {
//            return $this->findById($this->getDb()->lastInsertId());
//        }
//        if (!$statement->rowCount()) {
//            throw new NotFoundException('TODO with ID "' . $todo->getId() . '" does not exist.');
//        }
//        return $todo;
//    }

//    private function getParams(Todo $todo) {
//        $params = array(
//            ':id' => $todo->getId(),
//            ':priority' => $todo->getPriority(),
//            ':created_on' => self::formatDateTime($todo->getCreatedOn()),
//            ':last_modified_on' => self::formatDateTime($todo->getLastModifiedOn()),
//            ':due_on' => self::formatDateTime($todo->getDueOn()),
//            ':title' => $todo->getTitle(),
//            ':description' => $todo->getDescription(),
//            ':comment' => $todo->getComment(),
//            ':status' => $todo->getStatus(),
//            ':deleted' => $todo->getDeleted(),
//        );
//        if ($todo->getId()) {
//            // unset created date, this one is never updated
//            unset($params[':created_on']);
//        }
//        return $params;
//    }

    private function executeStatement(PDOStatement $statement, array $params) {
        if (!$statement->execute($params)) {
            self::throwDbError($this->getDb()->errorInfo());
        }
    }

    /**
     * @return PDOStatement
     */
    private function query($sql) {
        $statement = $this->getDb()->query($sql, PDO::FETCH_ASSOC);
        if ($statement === false) {
            self::throwDbError($this->getDb()->errorInfo());
        }
        return $statement;
    }

    private static function throwDbError(array $errorInfo) {
        // TODO log error, send email, etc.
        throw new Exception('DB error [' . $errorInfo[0] . ', ' . $errorInfo[1] . ']: ' . $errorInfo[2]);
    }

    private static function formatDateTime(DateTime $date) {
        return $date->format(DateTime::ISO8601);
    }
}
