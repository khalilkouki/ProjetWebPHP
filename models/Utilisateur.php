<?php
class Utilisateur {
    private $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $nom;
    public $email;
    public $mot_de_passe;
    public $role;
    public $date_creation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un nouvel utilisateur
    public function creer() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET
                      nom=:nom, email=:email, mot_de_passe=:mot_de_passe, role=:role";

        $stmt = $this->conn->prepare($query);

        // Assainir les données
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mot_de_passe = htmlspecialchars(strip_tags($this->mot_de_passe));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Hacher le mot de passe et le lier à une variable
        $hashed_password = password_hash($this->mot_de_passe, PASSWORD_BCRYPT);

        // Lier les valeurs
        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mot_de_passe", $hashed_password);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Lire tous les utilisateurs avec tri
    public function lireTous($tri_colonne = "id", $tri_ordre = "ASC") {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY $tri_colonne $tri_ordre";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    // Lire un utilisateur spécifique
    public function lireUn() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nom = $row['nom'];
            $this->email = $row['email'];
            $this->role = $row['role'];
            $this->date_creation = $row['date_creation'];
        }
    }

    // Mettre à jour un utilisateur
    public function mettreAJour() {
        $query = "UPDATE " . $this->table_name . "
                  SET
                      nom = :nom,
                      email = :email,
                      role = :role
                  WHERE
                      id = :id";

        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Supprimer un utilisateur
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

    // Rechercher des utilisateurs
    public function rechercher($criteres) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";

        if (!empty($criteres['nom'])) {
            $query .= " AND nom LIKE :nom";
        }
        if (!empty($criteres['email'])) {
            $query .= " AND email LIKE :email";
        }
        if (!empty($criteres['role'])) {
            $query .= " AND role = :role";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($criteres['nom'])) {
            $stmt->bindParam(':nom', $criteres['nom']);
        }
        if (!empty($criteres['email'])) {
            $stmt->bindParam(':email', $criteres['email']);
        }
        if (!empty($criteres['role'])) {
            $stmt->bindParam(':role', $criteres['role']);
        }

        $stmt->execute();

        return $stmt;
    }

    // Compter le nombre d'utilisateurs
    public function compter() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total'];
    }


    public function connexion($email, $mot_de_passe) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 0,1";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row && password_verify($mot_de_passe, $row['mot_de_passe'])) {
            // Initialiser la session
            $_SESSION['id'] = $row['id'];
            $_SESSION['nom'] = $row['nom'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            return true;
        }
        return false;
    }
    
}
