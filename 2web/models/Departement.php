<?php
class Departement {
    private $conn;
    private $table_name = "departements";

    public $id;
    public $nom;
    public $description;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouveau département
    public function creer() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      nom=:nom, description=:description";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Lire tous les départements
    public function lireTous() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lire un département spécifique
    public function lireUn() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nom = $row['nom'];
            $this->description = $row['description'];
            $this->date_creation = $row['date_creation'];
        }
    }

    // Mettre à jour un département
    public function mettreAJour() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                      nom = :nom,
                      description = :description
                  WHERE
                      id = :id";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Supprimer un département
    public function supprimer() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
