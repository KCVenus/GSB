<?php

namespace Modeles;

use tFPDF;

require (PATH_OUTILS . 'tFPDF.php');
//$unId = $_SESSION['idUtilisateur'] ?? 'defaultUser';
//$unMois = $_SESSION['leMois'] ?? 'defaultMonth';
//$bddname = 'gsb_frais';
//$hostname = 'localhost';
//$username = 'userGsb';
//$password = 'secret';
//
//$db = mysqli_connect($hostname, $username, $password, $bddname);
//if (!$db) {
//    die("Connection failed: " . mysqli_connect_error());
//}
//mysqli_set_charset($db, "utf8");

// Inclusion de la bibliothèque FPDF

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

    function BreakCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false) {
        if (!isset($this->CurrentFont))
            $this->Error('No font has been set');
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;

        $b = 0;
        if ($border)
            $b = $border;

        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        $stop = false;
        while ($i < $nb && $stop === false) {
            $c = $s[$i];
            if ($c == "\n") {
                if ($this->ws > 0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                $this->Cell($w, $h, substr($s, $j, $i - $j), $b, 0, $align, $fill);
                $stop = true;
                break;
            }

            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                    if ($this->ws > 0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                    $this->Cell($w, $h, substr($s, $j, $i - $j - 3) . '...', $b, 0, $align, $fill);
                    $stop = true;
                    break;
                }
            } else
                $i++;
        }

        if ($stop === false) {
            if ($this->ws > 0) {
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

    function createPDF() {
        $this->AliasNbPages();
        $this->AddPage();
        $this->SetFont('Arial', '', 12);
    }

    function recupererInfoUser($db, $unId, $unMois) {

        $req = "SELECT id, CONCAT(nom, ' ', prenom) AS nomvisiteur, mois FROM utilisateur INNER JOIN lignefraisforfait ON lignefraisforfait.idvisiteur = utilisateur.id WHERE id=? AND mois=?";
        $stmt = mysqli_prepare($db, $req);
        mysqli_stmt_bind_param($stmt, "ss", $unId, $unMois);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);
        return $row;
    }

//----------------------------------------- POLICES ----------------------------------------- 
    function SetFontArial() {
        $this->SetFont('Arial', 'B', 12);
    }

// arial + noir
    function SetDefaultFont() {
        $this->SetFont('Times', '', 11);
        $this->SetTextColor(0, 0, 0);
    }

// text en bleu
    function SetTextColorBleu() {
        $this->SetTextColor(31, 73, 125);
        $this->SetDrawColor(31, 73, 125);
    }

// Gras
    function DefPolice1() {
        $this->SetFont('Times', 'B', 11);
        $this->SetTextColorBleu();
    }

// Gras + Italique
    function DefPolice2() {
        $this->SetFont('Times', 'IB', 11);
        $this->SetTextColorBleu();
    }
    
    // plus petite , en italique et bleu
    function DefPolice3(){
        $this->SetFont('Times', 'I', 11);
        $this->SetTextColorBleu();
    }

//----------------------------------------- MARGINS -----------------------------------------

//----------------------------------------- DATES -----------------------------------------


//    function ConvertionDate($row) {
//        $mois = substr($row['mois'], 4, 2);
//        $annee = substr($row['mois'], 0, 4);
//        $dateObj = DateTime::createFromFormat('!m', $mois);
//        $nomMois = $dateObj ? $dateObj->format('F') : 'Invalid Month';
//    }
//    
    
    // Modification de la méthode ConvertionDate pour qu'elle retourne un tableau contenant le mois et l'année
function ConvertionDate($row) {
    $mois = substr($row['mois'], 4, 2);
    $annee = substr($row['mois'], 0, 4);
    $dateObj = \DateTime::createFromFormat('!m', $mois);
    $nomMois = $dateObj ? $dateObj->format('F') : 'Invalid Month';
    return ['mois' => $mois, 'annee' => $annee, 'nomMois' => $nomMois];
}

    

    function SetNomMois($mois, $annee) {
        $NomFrMois = $this->numeroVersMois($mois);
        $this->Cell(30, 10, $NomFrMois . " " . $annee, 0);
        $this->Ln(10);
    }

//----------------------------------------- TABLEAUX -----------------------------------------

    function CreateTabVisiteurRembourser($row) {
        $this->Cell(175, 10, 'REMBOURSEMENT DE FRAIS ENGAGES', 1, 1, 'C');
        $this->SetDefaultFont();
        $this->Cell(50, 10, 'Visiteur', 0);
        $this->Cell(30, 10, $row['id']);
        $this->Cell(50, 10, $row['nomvisiteur'], 0, 1);
        $this->Cell(50, 10, 'Mois', 0);
    }
    
    function CreateTabVisiteurEtat(){
        $this->SetLeftMargin(20);
        $this->DefPolice1();
        $this->SetTextColorBleu();
        $this->Cell(175, 10, 'ETAT DE FRAIS ENGAGES', 'TLR', 1, 'C');
        $this->DefPolice3();
        $this->Cell(175,10, utf8_decode('A retourner accompagné des justificatifs au plus tard le 10 du mois qui suit l\'engagement des frais'), 'BLR', 1 ,'C');
        $this->SetTextColorBleu();
        $this->SetLeftMargin(30);
        $this->DefPolice1();
        $this->Cell(30, 10, 'Visiteur', 0);
        $this->SetDefaultFont();
        $this->Cell(30, 10, "Matricule",0, 0);
        $this->Cell(50, 10, "", 'B', 1);
        $this->Cell(30,10, "");
        $this->Cell(30, 10, 'Nom', 0, 0);
        $this->Cell(50, 10, "", 'B', 1);
        $this->SetTextColorBleu();
        $this->DefPolice1(); 
        $this->Cell(30, 10, 'Mois', 0, 1);
        $this->SetDefaultFont();
        $this->SetLeftMargin(30);
    }

    function CreateTabFraisForfait() {
        $this->SetX(0);
        $this->SetLeftMargin(30);
        $this->DefPolice2();
        $this->Cell(30, 10, 'Frais Forfaitaires', 'LTB', 0, 'C');
        $this->Cell(35, 10, utf8_decode('Quantité'), 'TB', 0, 'C');
        $this->Cell(60, 10, 'Montant Unitaire', 'TB', 0, 'C');
        $this->Cell(25, 10, 'Total', 'TBR', 1, 'C');

// on remet la couleur de base ainsi que la polcie de base
        $this->SetDefaultFont();
    }
    
    
    
    function CreateTabFraisHorsForfaits() {
        $this->SetTextColorBleu();
        $this->DefPolice2();
        $this->Cell(150, 10, 'Autres Frais', 'LTBR', 1, 'C');
        $this->Cell(25, 10, 'Date', 'LTBR', 0, 'C');
        $this->Cell(100, 10, utf8_decode('Libellé'), 'TB', 0, 'C');
        $this->Cell(25, 10, 'Montant', 'LTBR', 1, 'C');
        $this->SetDefaultFont();
    }

    //----------------------------------------- REMPLIR TABLEAUX -----------------------------------------

    
    function FillTabFraisForfait($db, $unId, $unMois) {
        $req2 = "SELECT libelle, montant, quantite, (montant * quantite) as total FROM fraisforfait INNER JOIN lignefraisforfait ON lignefraisforfait.idfraisforfait = fraisforfait.id WHERE idvisiteur=? AND mois=?";
        $stmt2 = mysqli_prepare($db, $req2);
        mysqli_stmt_bind_param($stmt2, "ss", $unId, $unMois);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $totalGeneral = 0;
        while ($row2 = mysqli_fetch_array($result2)) {
            $this->Cell(30, 10, utf8_decode($row2['libelle']), 1);
            $this->Cell(35, 10, $row2['quantite'], 1, 0, 'C');
            $this->Cell(60, 10, number_format($row2['montant'], 2, '.', ''), 1, 0, 'C');
            $this->Cell(25, 10, number_format($row2['total'], 2, '.', ''), 1, 1, 'C');
            $totalGeneral += $row2['total'];
        }
    }

    function FillTabFraisHorsForfait($db, $unId, $unMois, $mois, $annee) {
        $totalGeneral =0;
        $reqHorsForfaits = "Select libelle, date, montant from lignefraishorsforfait WHERE idvisiteur=? AND mois=?";
        $stmt3 = mysqli_prepare($db, $reqHorsForfaits);
        mysqli_stmt_bind_param($stmt3, "ss", $unId, $unMois);
        mysqli_stmt_execute($stmt3);
        $resultHorsForfait = mysqli_stmt_get_result($stmt3);
        while ($row3 = mysqli_fetch_array($resultHorsForfait)) {
            $this->Cell(25, 10, $this->formaterDate($row3['date']), 1, 0);
            $this->BreakCell(100, 10, utf8_decode($row3['libelle']), 1);
            $this->Cell(25, 10, number_format($row3['montant'], 2, '.', ''), 1, 1, 'R');
            $totalGeneral += $row3['montant'];
        }
        $this->Cell(75, 10, '',);
        $this->Cell(25, 10, 'Total ' . $mois . "/" . $annee, 1);
        $this->Cell(50, 10, $totalGeneral, 1, 'R', 'R');
    }
    
    function FillTabFraisForfaitEtat(){
        $this->Cell(30, 10, utf8_decode("Nuitée"), 1,0);
        $this->Cell(35, 10, "", 1,0);
        $this->Cell(60, 10, '80,00', 1, 0, 'R');
        $this->Cell(25, 10, "", 1,1);
        $this->Cell(30, 10, utf8_decode("Repas Midi"), 1,0);
        $this->Cell(35, 10, "", 1,0);
        $this->Cell(60, 10, '25,00', 1, 0, 'R');
        $this->Cell(25, 10, "", 1,1);
        $this->Cell(30, 10, utf8_decode("Kilométrage"), 1,0);
        $this->Cell(35, 10, "", 1,0);
        $this->Cell(60, 10, '', 1, 0, 'R');
        $this->Cell(25, 10, "", 1,1);
    }
    
    function FillTabFraisHorsForfaisEtat(){
       
        for ($i = 1; $i <= 5; $i++) {
            $this->Cell(25, 10, "", 1,0);
            $this->Cell(100, 10, "", 1,0);
            $this->Cell(25, 10, "", 1,1);
        }
}


    
    
        //----------------------------------------- SIGNATURES -----------------------------------------

    function AddSignatureComptable() {
        $this->Text(120, 200, 'Fait a Paris, le ' . $this->obtenirDateDuJour()); // marche
        $this->Text(120, 208, 'Vu l\'agent comptable');
        $this->Image('./images/signatureComptable.png', 116, 220);
    }
     function AddSignatureComptableEtat() {
        $this->DefPolice2();
        $this->Text(160, 200, "Signature");
    }

    function AddTextFooter() {
    $this->Cell(35, 30, "", 'B', 1);
    $this->SetDefaultFont();
    $this->SetFont('Arial', '', 8); // Assurez-vous de définir la police si nécessaire
    $text = utf8_decode("Les frais forfaitaires doivent être justifiés par une facture acquittée faisant apparaître le montant de la TVA.\n" .
            "Ces documents ne sont pas à joindre à l’état de frais mais doivent être conservés pendant trois années. Ils peuvent être contrôlés par le délégué régional ou le service comptable.\n" .
            "Tarifs en vigueur au 01/09/2023\n" .
            "Prix au kilomètre selon la puissance du véhicule déclaré auprès des services comptables:\n" .
            "- (Véhicule 4CV Diesel)    0.52 € / Km\n" .
            "- (Véhicule 5/6CV Diesel)  0.58 € / Km\n" .
            "- (Véhicule 4CV Essence)    0.62 € / Km\n" .
            "- (Véhicule 5/6CV Essence)  0.67 € / Km\n" .
            "Tout frais « hors forfait » doit être dûment justifié par l’envoi d’une facture acquittée faisant apparaître le montant de TVA.");
    $this->MultiCell(0, 5, $text); // Réduire la hauteur de ligne à 5
}


function CreatePDFRembourser(){
    
$bddname = 'gsb_frais';
$hostname = 'localhost';
$username = 'userGsb';
$password = 'secret';
$db = mysqli_connect($hostname, $username, $password, $bddname);
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}  
$unId = $_SESSION['idUtilisateur'] ?? 'defaultUser';
$unMois = $_SESSION['leMois'] ?? 'defaultMonth';
$row = $this->recupererInfoUser($db, $unId, $unMois);
$dateInfo = $this->ConvertionDate($row); // Utiliser la méthode modifiée
$mois = $dateInfo['mois'];
$annee = $dateInfo['annee'];
$this->SetFontArial();
$this->ConvertionDate($row);
$this->SetLeftMargin(20);
$this->SetTextColorBleu();
$this->CreateTabVisiteurRembourser($row);
$this->SetNomMois($mois, $annee);
$this->CreateTabFraisForfait();
$this->FillTabFraisForfait($db, $unId, $unMois);
$this->CreateTabFraisHorsForfaits();
$this->FillTabFraisHorsForfait($db, $unId, $unMois, $mois, $annee);
$this->AddSignatureComptable();
}

function CreatePDFEtat() {

   $this->CreateTabVisiteurEtat();
    $this->CreateTabFraisForfait(); 
  $this->FillTabFraisForfaitEtat();
    $this->CreateTabFraisHorsForfaits();
    $this->FillTabFraisHorsForfaisEtat();
    $this->AddSignatureComptableEtat();
    $this->AddTextFooter();
}
}
