<?
include "../../../connect.php"; // connexion au SGBD

$data = json_decode(file_get_contents("php://input"));
$function = $data->function;
$param = $data->param;

$function($bdd, $param);

function delJeu($bdd, $jeu) {
    $req = "DELETE FROM Jeu WHERE Nom = ?"; 
    $stmt = $bdd->prepare($req);
    $stmt->execute(array($jeu));
    // Débuguage de la requête
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
        print_r($errorInfo);
    }
    $req = "DELETE FROM Noter_Commenter WHERE Nom = ?";
    $stmt = $bdd->prepare($req);
    $stmt->execute(array($jeu));
    // Débuguage de la requête
    $errorInfo = $stmt->errorInfo();
    if ($errorInfo[0] != 0) {
        print_r($errorInfo);
    }
    $result = array("jeu" => $jeu);
    echo json_encode($result);
}
