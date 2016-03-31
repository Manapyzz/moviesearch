<?php
namespace Controller;

use Doctrine\DBAL\Query\QueryBuilder;

class IndexController
{
    public function indexAction()
    {
        include("search.php");
    }

    public function searchAction()
    {
        //se connecter à la bdd
        header('Content-Type: application/json');

        $conn = \MovieSearch\Connexion::getInstance();
        //creer la requete adéquate
        $sql = "SELECT * FROM `artist` 
		left join film_director on artist.id = film_director.artist_id 
		left join film on film_director.film_id = film.id 
		WHERE film.title LIKE :title and film.duration between :duration1 and :duration2 and film.year between :year1 and :year2
		and artist.first_name like :firstName and artist.last_name like :lastName and artist.gender like :gender";

        //envoyer la requête à la BDD
        $title = '%';
        $duration1 = 0;
        $duration2 = 9999999;
        $year1 = 0;
        $year2 = 999999;
        $firstName = '%';
        $lastName = '%';
        $gender = '%';

        if($_POST) {
            if ($_POST['title']) {
                $title = $_POST['title'] . '%';
            }

            if ($_POST['year_start'] && $_POST['year_end']) {
                $year1 = $_POST['year_start'];
                $year2 = $_POST['year_end'];
            }

            if ($_POST['year_start'] && empty($_POST['year_end'])){
                $year1 = $_POST['year_start'];
            }

            if (empty($_POST['year_start']) && $_POST['year_end']){
                $year2 = $_POST['year_end'];
            }

            if($_POST['duration']){
                $choice = $_POST['duration'];
                if($choice == '2'){
                    $duration2 = 3600;
                } elseif($choice == '3'){
                    $duration1 = 3600;
                    $duration2 = 5400;
                } elseif($choice == '4'){
                    $duration1 = 5400;
                    $duration2 = 9000;
                } elseif($choice == '5'){
                    $duration1 = 9000;
                }
            }

            if ($_POST['first_name']) {
                $firstName = $_POST['first_name'] . '%';
            }

            if ($_POST['last_name']) {
                $lastName = $_POST['last_name'] . '%';
            }

            if ($_POST['gender'] && $_POST['gender'] != 'Tous') {
                $gender = $_POST['gender'];
            }
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':duration1', $duration1);
        $stmt->bindParam(':duration2', $duration2);
        $stmt->bindParam(':year1', $year1);
        $stmt->bindParam(':year2', $year2);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam("gender", $gender);
        $stmt->execute();
        //renvoyer les films qu'on a trouvés
        $films = $stmt->fetchAll();
        return json_encode(["films" => $films]);
    }



}