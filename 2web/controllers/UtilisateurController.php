<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class UtilisateurController {
    private $conn;
    private $utilisateur;

    public function __construct($db) {
        $this->conn = $db;
        $this->utilisateur = new Utilisateur($db);
    }

    public function lireUnUtilisateur($id) {
        $query = "SELECT * FROM utilisateurs WHERE id = :id LIMIT 0,1";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row) {
            return $row;
        } else {
            return null;
        }
    }
    

    // Créer un nouvel utilisateur
    public function creerUtilisateur($data) {
        $this->utilisateur->nom = $data['nom'];
        $this->utilisateur->email = $data['email'];
        $this->utilisateur->mot_de_passe = $data['mot_de_passe'];
        $this->utilisateur->role = $data['role'];

        if ($this->utilisateur->creer()) {
            return json_encode(['message' => 'Utilisateur créé avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de créer l\'utilisateur.']);
        }
    }

    // Lire tous les utilisateurs avec tri
    public function lireUtilisateurs($tri_colonne = "id", $tri_ordre = "ASC") {
        $stmt = $this->utilisateur->lireTous($tri_colonne, $tri_ordre);
        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($utilisateurs);
    }

    // Lire un utilisateur spécifique
    public function lireUtilisateur($id) {
        $this->utilisateur->id = $id;
        $this->utilisateur->lireUn();

        if ($this->utilisateur->nom != null) {
            return json_encode([
                'id' => $this->utilisateur->id,
                'nom' => $this->utilisateur->nom,
                'email' => $this->utilisateur->email,
                'role' => $this->utilisateur->role,
                'date_creation' => $this->utilisateur->date_creation
            ]);
        } else {
            return json_encode(['message' => 'Utilisateur non trouvé.']);
        }
    }

    // Mettre à jour un utilisateur
    public function mettreAJourUtilisateur($id, $data) {
        $this->utilisateur->id = $id;
        $this->utilisateur->nom = $data['nom'];
        $this->utilisateur->email = $data['email'];
        $this->utilisateur->role = $data['role'];

        if ($this->utilisateur->mettreAJour()) {
            return json_encode(['message' => 'Utilisateur mis à jour avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de mettre à jour l\'utilisateur.']);
        }
    }

    // Supprimer un utilisateur
    public function supprimerUtilisateur($id) {
        $this->utilisateur->id = $id;

        if ($this->utilisateur->supprimer()) {
            return json_encode(['message' => 'Utilisateur supprimé avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de supprimer l\'utilisateur.']);
        }
    }

    // Rechercher des utilisateurs
    public function rechercherUtilisateurs($criteres) {
        $stmt = $this->utilisateur->rechercher($criteres);
        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($utilisateurs);
    }

    // Compter les utilisateurs
    public function compterUtilisateurs() {
        return $this->utilisateur->compter();
    }
}
