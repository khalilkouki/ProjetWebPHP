<?php
class Categorie {
    private $conn;
    private $table_name = "categories";

    public $id;
    public $nom;
    public $departement_id;
    public $description;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une nouvelle catégorie
    public function creer() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      nom=:nom, departement_id=:departement_id, description=:description";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->departement_id = htmlspecialchars(strip_tags($this->departement_id));
        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":departement_id", $this->departement_id);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Lire toutes les catégories
    public function lireTous() {
        $query = "SELECT * FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lire une catégorie spécifique
    public function lireUn() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nom = $row['nom'];
            $this->departement_id = $row['departement_id'];
            $this->description = $row['description'];
            $this->date_creation = $row['date_creation'];
        }
    }

    // Mettre à jour une catégorie
    public function mettreAJour() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                      nom = :nom,
                      departement_id = :departement_id,
                      description = :description
                  WHERE
                      id = :id";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->departement_id = htmlspecialchars(strip_tags($this->departement_id));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':departement_id', $this->departement_id);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

   // Supprimer une catégorie
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
