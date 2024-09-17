<?php

class Alumno {
    private $conn;
    private $table = "alumnos";

    public $id;
    public $dni;
    public $nombre;
    public $apellido;
    public $curso;
    public $division;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->dni = $row['dni'];
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->curso = $row['curso'];
            $this->division = $row['division'];
        }

        return $row ? true : false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (dni, nombre, apellido, curso, division) VALUES (:dni, :nombre, :apellido, :curso, :division)";
        $stmt = $this->conn->prepare($query);

        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->curso = htmlspecialchars(strip_tags($this->curso));
        $this->division = htmlspecialchars(strip_tags($this->division));

        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":curso", $this->curso);
        $stmt->bindParam(":division", $this->division);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . " SET dni = :dni, nombre = :nombre, apellido = :apellido, curso = :curso, division = :division WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->dni = htmlspecialchars(strip_tags($this->dni));
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->curso = htmlspecialchars(strip_tags($this->curso));
        $this->division = htmlspecialchars(strip_tags($this->division));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":dni", $this->dni);
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":curso", $this->curso);
        $stmt->bindParam(":division", $this->division);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>