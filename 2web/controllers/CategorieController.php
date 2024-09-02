<?php
require_once __DIR__ . '/../models/Categorie.php';

class CategorieController {
    private $conn;
    private $categorie;

    public function __construct($db) {
        $this->conn = $db;
        $this->categorie = new Categorie($db);
    }

    // Créer une nouvelle catégorie
    public function creerCategorie($data) {
        $this->categorie->nom = $data['nom'];
        $this->categorie->departement_id = $data['departement_id'];
        $this->categorie->description = $data['description'];

        if ($this->categorie->creer()) {
            return json_encode(['message' => 'Catégorie créée avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de créer la catégorie.']);
        }
    }

    // Lire toutes les catégories
    public function lireCategories() {
        $stmt = $this->categorie->lireTous();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($categories);
    }

    // Lire une catégorie spécifique
    public function lireCategorie($id) {
        $this->categorie->id = $id;
        $this->categorie->lireUn();

        if ($this->categorie->nom != null) {
            return json_encode([
                'id' => $this->categorie->id,
                'nom' => $this->categorie->nom,
                'departement_id' => $this->categorie->departement_id,
                'description' => $this->categorie->description,
                'date_creation' => $this->categorie->date_creation
            ]);
        } else {
            return json_encode(['message' => 'Catégorie non trouvée.']);
        }
    }

    // Mettre à jour une catégorie
    public function mettreAJourCategorie($id, $data) {
        $this->categorie->id = $id;
        $this->categorie->nom = $data['nom'];
        $this->categorie->departement_id = $data['departement_id'];
        $this->categorie->description = $data['description'];

        if ($this->categorie->mettreAJour()) {
            return json_encode(['message' => 'Catégorie mise à jour avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de mettre à jour la catégorie.']);
        }
    }

    // Supprimer une catégorie
    public function supprimerCategorie($id) {
        $this->categorie->id = $id;

        if ($this->categorie->supprimer()) {
            return json_encode(['message' => 'Catégorie supprimée avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de supprimer la catégorie.']);
        }
    }
}
