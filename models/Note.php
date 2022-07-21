<?php

class Note {

    /**
     * @OA\Info(title="PHP REST API", version="1.0")
     */
    private $conn;
    private $table = "notebook";
    public $id;
    public $fullName;
    public $company;
    public $phone;
    public $email;
    public $birthday;
    public $photo;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * @OA\Get(
     *     path="/rest-api/note/getNotes.php",
     *     summary="Метод для чтения всех записей в БД",
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="Номер страницы",
     *          @OA\Schema(
     *              type="int"
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="row_per_page",
     *          in="query",
     *          required=false,
     *          description="Количество записей на странице",
     *          @OA\Schema(
     *              type="int"
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    function getNotes() {
// select all query
        if ($_GET['page'] && $_GET['row_per_page']) {
            $page = $_GET["page"];
            $row_per_page = $_GET["row_per_page"];

            $begin = ($page * $row_per_page) - $row_per_page;

            $stmt = $this->conn->prepare("SELECT * FROM " . "$this->table"
                    . " LIMIT ?,?");
            $stmt->bind_param('ii', $begin, $row_per_page);
            $stmt->execute();
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM " . "$this->table");
            $stmt->execute();
        }
        return $stmt;
    }

    /**
     * @OA\Get(
     *     path="/rest-api/note/getNote.php",
     *     summary="Метод для чтения одной записи в БД",
     * @OA\Parameter(
     *          name="id",
     *          in="query",
     *          required=true,
     *          description="Передача id дя поиска записи в БД",
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    function getNote($id) {
        $this->id = $id;
        $stmt = $this->conn->
                prepare("SELECT * FROM " . "$this->table" . " WHERE Id=?");
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) != 0) {
            return $stmt;
        } else {
            return false;
        }
    }

    /**
     * @OA\Post(
     *     path="/rest-api/note/create.php",
     *     summary="Метод для добавления записи в БД",
     * @OA\RequestBody(
     *     @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="fullName",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="company",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="birthday",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="photo",
     *                  type="string",
     *              ),    
     *          ),
     *     ),
     * ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="412", description="Precondition failed"),
     * )
     */
    function add($params) {
        try {
            $this->fullName = $params['fullName'];
            $this->company = $params['company'];
            $this->phone = $params['phone'];
            $this->email = $params['email'];
            $this->birthday = $params['birthday'];
            $this->photo = $params['photo'];

            $stmt = $this->conn->prepare("INSERT INTO " . "$this->table"
                    . " (`Id`, `ФИО`, `Компания`, "
                    . "`Телефон`, `Email`, `Дата рождения`, `Фото`) "
                    . "VALUES (DEFAULT, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $this->fullName, $this->company,
                    $this->phone, $this->email, $this->birthday, $this->photo);

            if ($stmt->execute()) {
                return true;
            }

            return false;
        } catch (mysqli_sql_exception $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @OA\Get(
     *     path="/rest-api/note/delete.php",
     *     summary="Метод для удаления записи из БД",
     *     @OA\Parameter(
     *          name="id",
     *          in="query",
     *          required=true,
     *          description="Id записи для удаления",
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    function delete($id) {
        if ($this->getNote($id)) {
            try {
                $this->id = $id;

                $stmt = $this->conn->prepare("DELETE FROM " . "$this->table"
                        . " WHERE Id=?");
                $stmt->bind_param('i', $id);
                $stmt->execute();

                return true;
            } catch (mysqli_sql_exception $exception) {
                echo $exception->getMessage();
            }
        } else {
            return false;
        }
    }

    /**
     * @OA\Post(
     *     path="/rest-api/note/update.php",
     *     summary="Метод для обновления записи в БД",
     * @OA\RequestBody(
     *     @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="id",
     *                  type="int",
     *              ),
     *              @OA\Property(
     *                  property="fullName",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="company",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="phone",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="birthday",
     *                  type="string",
     *              ),
     *              @OA\Property(
     *                  property="photo",
     *                  type="string",
     *              ),    
     *          ),
     *     ),
     * ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="412", description="Precondition failed"),
     * )
     */
    function update($params) {

        if ($params['id'] && $this->getNote($params['id'])) {
            try {
                $queryParams = [];
                $queryDataTypes = '';
                $query = [];

                if ($params['fullName']) {
                    $queryParams[] = $params['fullName'];
                    $queryDataTypes .= 's';
                    $query[] = "`ФИО` = ?";
                }
                if ($params['company']) {
                    $queryParams[] = $params['company'];
                    $queryDataTypes .= 's';
                    $query[] = "`Компания` = ?";
                }
                if ($params['phone']) {
                    $queryParams[] = $params['phone'];
                    $queryDataTypes .= 's';
                    $query[] = "`Телефон` = ?";
                }
                if ($params['email']) {
                    $queryParams[] = $params['email'];
                    $queryDataTypes .= 's';
                    $query[] = "`Email` = ?";
                }
                if ($params['birthday']) {
                    $queryParams[] = $params['birthday'];
                    $queryDataTypes .= 's';
                    $query[] = "`Дата рождения` = ?";
                }
                if ($params['photo']) {
                    $queryParams[] = $params['photo'];
                    $queryDataTypes .= 's';
                    $query[] = "`Фото` = ?";
                }

                $queryDataTypes .= 'i';
                $queryParams[] = $params['id'];
                $sqlQuery = "UPDATE " . "$this->table" . " SET " . implode(", ", $query)
                        . " WHERE Id = ?";
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->bind_param($queryDataTypes, ...$queryParams);
                $stmt->execute();
                return true;
            } catch (mysqli_sql_exception $exception) {
                echo $exception->getMessage();
            }
        } else {
            return false;
        }
    }

}
