<?php
 
/**
 * PHP MySQL Transaction Demo
 */
class Transaction {
 
    const DB_HOST = 'localhost';
    const DB_NAME = 'bank';
    const DB_USER = 'root';
    const DB_PASSWORD = '';
 
    /**
     * Open the database connection
     */
    public function __construct() {
        // open database connection
        $conStr = sprintf("mysql:host=%s;dbname=%s", self::DB_HOST, self::DB_NAME);
        try {
            $this->pdo = new PDO($conStr, self::DB_USER, self::DB_PASSWORD);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
 
    /**
     * PDO instance
     * @var PDO 
     */
    private $pdo = null;
 
    /**
     * Transfer money between two accounts
     * @param int $from
     * @param int $to
     * @param float $amount
     * @return true on success or false on failure.
     */
    public function transfer($from, $to, $amount) {
 
        try {
            $this->pdo->beginTransaction();
 
            // get available amount of the transferer account
            $sql = 'SELECT amount FROM accounts WHERE id=:from';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(":from" => $from));
            $availableAmount = (int) $stmt->fetchColumn();
            $stmt->closeCursor();
 
            // ---> to do here ตรวจสอบว่ามีเงินที่จะโอนมีน้อยกว่าในบัญชีหรือไม่
            if($availableAmount < $amount){
                return "จำนวนเงินไม่เพียงพอ"
            }

            // deduct from the transferred account
            $sql_update_from = 'UPDATE accounts
                SET amount = amount - :amount
                WHERE id = :from';
            $stmt = $this->pdo->prepare($sql_update_from);
            $stmt->execute(array(":from" => $from, ":amount" => $amount));
            $stmt->closeCursor();
 
            // add to the receiving account
            $sql_update_to = 'UPDATE accounts
                                SET amount = amount + :amount
                                WHERE id = :to';
            $stmt = $this->pdo->prepare($sql_update_to);
            $stmt->execute(array(":to" => $to, ":amount" => $amount));

            // get available amount of the transferer account
            $sql = 'SELECT amount FROM accounts WHERE id=:from';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array(":from" => $from));
            $availableAmount = (int) $stmt->fetchColumn();
            $stmt->closeCursor();
 
            // ---> to do here ***************
            if($availableAmount < 0){
                new throw PDOExcaption();
            }
 
            // commit the transaction
            $this->pdo->commit();
 
            return 'โอนเงินสำเร็จ';
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return 'จำนวนเงินในระบบไม่เพียงพอ';
        }
    }

    public function getAmount(){
        $sql = 'SELECT amount FROM accounts WHERE id=:from';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array(":from" => 1));
        return $stmt->fetchColumn();
    }
 
    /**
     * close the database connection
     */
    public function __destruct() {
        // close the database connection
        $this->pdo = null;
    }
 
}