<?php
ob_start(); // Début de la mise en tampon de sortie pour éviter les problèmes de "headers already sent"

// Connexion à la base de données
$bddname = 'gsb_frais';
$hostname = 'localhost';
$username = 'userGsb';
$password = 'secret';
$db = mysqli_connect($hostname, $username, $password, $bddname);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($db, "utf8");

require("fpdf.php"); // Inclusion de la bibliothèque FPDF

// Définition de la classe PDF héritée de FPDF pour les en-têtes et pieds de page personnalisés
class PDF extends FPDF {
    function Header() {
        $this->Image('./images/logo.jpg', 10, 6, 30); // Assurez-vous que le chemin est correct
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80); // Déplacer la cellule vers la droite
        $this->Cell(30, 10, 'Titre du Document', 1, 0, 'C');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Récupération des données utilisateur

$unId = $_SESSION['idUtilisateur'] ?? 'defaultUser';
$unMois = $_SESSION['leMois'] ?? 'defaultMonth';

// Requête pour récupérer les informations de l'utilisateur
$req = "SELECT id, CONCAT(nom, ' ', prenom) AS nomvisiteur, mois FROM utilisateur INNER JOIN lignefraisforfait ON lignefraisforfait.idvisiteur = utilisateur.id WHERE id=? AND mois=?";
$stmt = mysqli_prepare($db, $req);
mysqli_stmt_bind_param($stmt, "ss", $unId, $unMois);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Rapport de frais pour ' . $row['nomvisiteur'], 0, 1);

// Conversion de la date
$mois = substr($row['mois'], 4, 2);
$annee = substr($row['mois'], 0, 4);
$dateObj = DateTime::createFromFormat('!m', $mois);
$nomMois = $dateObj ? $dateObj->format('F') : 'Invalid Month';

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Mois de ' . $nomMois . ' ' . $annee, 0, 1);

// Entêtes de tableau pour les frais
$pdf->SetFillColor(200, 220, 255);
$pdf->Cell(50, 10, 'Type de frais', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Quantité', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Montant unitaire', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Total', 1, 1, 'C', true);

// Requête pour obtenir les détails des frais
$req2 = "SELECT libelle, montant, quantite, (montant * quantite) as total FROM fraisforfait INNER JOIN lignefraisforfait ON lignefraisforfait.idfraisforfait = fraisforfait.id WHERE idvisiteur=? AND mois=?";
$stmt2 = mysqli_prepare($db, $req2);
mysqli_stmt_bind_param($stmt2, "ss", $unId, $unMois);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$totalGénéral = 0;
while ($row2 = mysqli_fetch_array($result2)) {
    $pdf->Cell(50, 10, $row2['libelle'], 1);
    $pdf->Cell(40, 10, $row2['quantite'], 1, 0, 'C');
    $pdf->Cell(50, 10, number_format($row2['montant'], 2, '.', ''), 1, 0, 'C');
    $pdf->Cell(50, 10, number_format($row2['total'], 2, '.', ''), 1, 1, 'C');
    $totalGénéral += $row2['total'];
}

// Affichage du total général
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(140, 10, 'Total Général', 1);
$pdf->Cell(50, 10, number_format($totalGénéral, 2, '.', '') . ' EUR', 1, 1, 'C');

// Génération du PDF

$pdf->Output();
ob_end_flush(); // Envoi du contenu du tampon et désactivation de la mise en tampon
?>
