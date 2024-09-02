<?php
require_once __DIR__ . '/../models/Departement.php';

class DepartementController {
    private $conn;
    private $departement;

    public function __construct($db) {
        $this->conn = $db;
        $this->departement = new Departement($db);
    }

    // Créer un nouveau département
    public function creerDepartement($data) {
        $this->departement->nom = $data['nom'];
        $this->departement->description = $data['description'];

        if ($this->departement->creer()) {
            return json_encode(['message' => 'Département créé avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de créer le département.']);
        }
    }

    // Lire tous les départements
    public function lireDepartements() {
        $stmt = $this->departement->lireTous();
        $departements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode($departements);
    }

    // Lire un département spécifique
    public function lireDepartement($id) {
        $this->departement->id = $id;
        $this->departement->lireUn();

        if ($this->departement->nom != null) {
            return json_encode([
                'id' => $this->departement->id,
                'nom' => $this->departement->nom,
                'description' => $this->departement->description,
                'date_creation' => $this->departement->date_creation
            ]);
        } else {
            return json_encode(['message' => 'Département non trouvé.']);
        }
    }

    // Mettre à jour un département
    public function mettreAJourDepartement($id, $data) {
        $this->departement->id = $id;
        $this->departement->nom = $data['nom'];
        $this->departement->description = $data['description'];

        if ($this->departement->mettreAJour()) {
            return json_encode(['message' => 'Département mis à jour avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de mettre à jour le département.']);
        }
    }

    // Supprimer un département
    public function supprimerDepartement($id) {
        $this->departement->id = $id;

        if ($this->departement->supprimer()) {
            return json_encode(['message' => 'Département supprimé avec succès.']);
        } else {
            return json_encode(['message' => 'Impossible de supprimer le département.']);
        }
    }
}
