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

require("tfpdf.php"); // Inclusion de la bibliothèque FPDF

// Définition de la classe PDF héritée de FPDF pour les en-têtes et pieds de page personnalisés
class PDF extends tFPDF {
    function Header() {
        $largeurPage = $this->GetPageWidth(); // Obtenir la largeur de la page
        $largeurImage = 30; // La largeur de votre image
        $x = ($largeurPage / 2) - ($largeurImage / 2); // Calculer la position X pour centrer l'image

    $this->Image('./images/logo.jpg', $x, 6, $largeurImage); // Centrer l'image horizontalement
    
//        $this->Image('./images/logo.jpg', 100, 6, 30); // Assurez-vous que le chemin est correct
//        $this->SetFont('Arial', 'B', 15);
//        $this->Cell(80); // Déplacer la cellule vers la droite
//        $this->Cell(30, 10, 'ETAT DE FRAIS ENGAGES', 1, 0, 'C');
      $this->Ln(20);
    }
 
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        
    }
    
function BreakCell($w, $h, $txt, $border=0, $align='J', $fill=false)
{
    if(!isset($this->CurrentFont))
        $this->Error('No font has been set');
    $cw = &$this->CurrentFont['cw'];
    if($w==0)
        $w = $this->w-$this->rMargin-$this->x;
    $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
    $s = str_replace("\r",'',$txt);
    $nb = strlen($s);
    if($nb>0 && $s[$nb-1]=="\n")
        $nb--;

    $b = 0;
    if($border) $b = $border;

    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $ns = 0;
    $nl = 1;
    $stop = false;
    while($i < $nb && $stop === false)
    {
        $c = $s[$i];
        if($c == "\n")
        {
            if($this->ws > 0)
            {
                $this->ws = 0;
                $this->_out('0 Tw');
            }
            $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 0, $align, $fill);
            $stop = true;
            break;
        }

        $l += $cw[$c];
        if($l > $wmax)
        {
            if($sep == -1)
            {
                if($i == $j)
                    $i++;
                if($this->ws > 0)
                {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w, $h, substr($s, $j, $i - $j - 3).'...', $b, 0, $align, $fill);
                $stop = true;
                break;
            }
        }
        else
            $i++;
    }

    if($stop === false){
        if($this->ws > 0)
        {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 0, $align, $fill);
    }
}



    function formaterDate($date) {
    // Vérifie si la date n'est pas vide et est une chaîne de caractères valide
    if (!empty($date)) {
        // Convertit la date du format 'YYYY-MM-DD' au format 'DD/MM/YYYY'
        $dateFormatee = date('d/m/Y', strtotime($date));
        return $dateFormatee;
    } else {
        // Retourne une chaîne vide ou un message d'erreur si la date est invalide
        return "Date invalide";
    }
    }

    
function numeroVersMois($numero) {
    $mois = [
        "01" => "Janvier", "02" => "Février", "03" => "Mars", "04" => "Avril",
        "05" => "Mai", "06" => "Juin", "07" => "Juillet", "08" => "Août",
        "09" => "Septembre", "10" => "Octobre", "11" => "Novembre", "12" => "Décembre"
    ];

    if (array_key_exists($numero, $mois)) {
        return $mois[$numero];
    } else {
        return "Numéro de mois invalide";
    }
}
function obtenirDateDuJour() {
    
    // Récupération de la date actuelle
    $jour = date('d');
    $mois = date('m');
    $annee = date('Y');

    // Suppression du zéro initial pour le jour si nécessaire
    $jour = ltrim($jour, '0');

    // Formatage de la date en français
    return $jour . ' ' . $this->numeroVersMois($mois) . ' ' . $annee;
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

// Conversion de la date
$mois = substr($row['mois'], 4, 2);
$annee = substr($row['mois'], 0, 4);
$dateObj = DateTime::createFromFormat('!m', $mois);
$nomMois = $dateObj ? $dateObj->format('F') : 'Invalid Month';

$pdf->SetLeftMargin(30);

$pdf->SetFont('Times', 'B', 11);
$pdf->SetTextColor(31, 73, 125);
$pdf->SetDrawColor(31, 73, 125);
$pdf->Cell(150,10, 'REMBOURSEMENT DE FRAIS ENGAGES', 1, 1, 'C');
$pdf->SetFont('Times', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(50,10,'Visiteur',0);
$pdf->Cell(30,10, $row['id']);
$pdf->Cell(50,10,$row['nomvisiteur'],0,1);
$pdf->Cell(50,10,'Mois',0);

$NomFrMois = $pdf->numeroVersMois($mois);

$pdf->Cell(30,10, $NomFrMois . " " . $annee,0);
$pdf->Ln(10);

$pdf->SetX(0);
$pdf->SetLeftMargin(30);
$pdf->SetFont('Times', 'IB', 11);
$pdf->SetTextColor(31, 73, 125);
$pdf->SetDrawColor(31, 73, 125);
$pdf->Cell(50, 10, 'Frais Forfaitaires', 'LTB', 0, 'C');
$pdf->Cell(30, 10, utf8_decode('Quantité'), 'TB', 0, 'C');
$pdf->Cell(50, 10, 'Montant Unitaire', 'TB', 0, 'C');
$pdf->Cell(20, 10, 'Total', 'TBR', 1, 'C');
$pdf->SetFont('Times', '', 11);
$pdf->SetTextColor(0, 0, 0);

// Requête pour obtenir les détails des frais
$req2 = "SELECT libelle, montant, quantite, (montant * quantite) as total FROM fraisforfait INNER JOIN lignefraisforfait ON lignefraisforfait.idfraisforfait = fraisforfait.id WHERE idvisiteur=? AND mois=?";
$stmt2 = mysqli_prepare($db, $req2);
mysqli_stmt_bind_param($stmt2, "ss", $unId, $unMois);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$totalGeneral = 0;
while ($row2 = mysqli_fetch_array($result2)) {
    $pdf->Cell(50, 10, utf8_decode($row2['libelle']), 1);
    $pdf->Cell(30, 10, $row2['quantite'], 1, 0, 'C');
    $pdf->Cell(50, 10, number_format($row2['montant'], 2, '.', ''), 1, 0, 'C');
    $pdf->Cell(20, 10, number_format($row2['total'], 2, '.', ''), 1, 1, 'C');
    $totalGeneral += $row2['total'];
}
$pdf->SetFont('Times', 'IB', 11);
$pdf->SetTextColor(31, 73, 125);
$pdf->SetDrawColor(31, 73, 125);
$pdf->Cell(150,10, 'Autres Frais','LTBR',1, 'C');

$pdf->Cell(50, 10, 'Date', 'LTBR',0 , 'C');
$pdf->Cell(50, 10, utf8_decode('Libellé'), 'TB', 0, 'C');
$pdf->Cell(50, 10, 'Montant', 'LTBR', 1, 'C');
$pdf->SetFont('Times', '', 11);
$pdf->SetTextColor(0, 0, 0);

$reqHorsForfaits = "Select libelle, date, montant from lignefraishorsforfait WHERE idvisiteur=? AND mois=?";
$stmt3 = mysqli_prepare($db, $reqHorsForfaits);
mysqli_stmt_bind_param($stmt3, "ss", $unId, $unMois);
mysqli_stmt_execute($stmt3);
$resultHorsForfait = mysqli_stmt_get_result($stmt3);
while ($row3 = mysqli_fetch_array($resultHorsForfait)) {
    $pdf->Cell(50, 10, $pdf->formaterDate($row3['date']), 1,0);
    $pdf->BreakCell(50, 10, $row3['libelle'], 1);
    $pdf->Cell(50, 10, number_format($row3['montant'], 2, '.', ''), 1, 1, 'R');
      $totalGeneral += $row3['montant'];
}
$pdf->Cell(75,10, '',);
$pdf->Cell(25,10,'Total ' . $mois . "/" . $annee,1);
$pdf->Cell(50, 10, $totalGeneral,1, 'R', 'R');

//$pdf->Cell(50,10,utf8_decode('Fait à Paris, le'),1,0); // marche pas non plus
//$textFaitLe = utf8_decode('Fait à Paris, le');
//$pdf->Text(120,200, $textFaitLe . $pdf->obtenirDateDuJour());

$pdf->Text(120,200, 'Fait a Paris, le ' . $pdf->obtenirDateDuJour()); // marche
$pdf->Text(120,208, 'Vu l\'agent comptable');
//$pdf->Text(122,200, utf8_decode('Fait à Paris, le') ); // marche pas PTDRRRRRRRRRRRRRRRRR

$pdf->Image('./images/signatureComptable.png', 116,220); // Centrer l'image horizontalement


//$pdf->SetFont('Times', 'IB', 11);

// Affichage du total général
//$pdf->SetFont('Arial', 'B', 12);
//$pdf->Cell(140, 10, 'Total Général', 1);
//$pdf->Cell(50, 10, number_format($totalGénéral, 2, '.', '') . ' EUR', 1, 1, 'C');

// Génération du PDF

ob_end_flush(); // Envoi du contenu du tampon et désactivation de la mise en tampon
ob_end_clean();
$pdf->Output();
?>
