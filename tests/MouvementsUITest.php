<?php 
use PHPUnit\Framework\TestCase;

use PDO as PDO;
use Dotenv\Dotenv;
use PlaywrightPhp\Playwright;


// Test de l'interface entree et sortie d'objets, test de partie de code non encapsule dans des fonctions

class MouvementsUITest extends TestCase {
    private $pdo;
    private $faker;


    protected function setUp(): void {


        $dotenv = Dotenv::createImmutable(__DIR__ . '/../',".env.test");
        $dotenv->load();

        // Connexion à la base de test
        $this->pdo = new PDO(
            "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS']
        );

        //$this->pdo->beginTransaction();

        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->pdo->setAttribute(PDO::MYSQL_ATTR_DIRECT_QUERY, false);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    
        $this->faker = Faker\Factory::create('fr_FR');




    }

    protected function tearDown(): void {
        
       // $this->pdo->rollBack();
    }

    


    public function  test_VenteAvecPeseeCreate()
    {
        $playwright = new Playwright(['browser' => 'chromium']);

        $browser = $playwright->launch(); 
        $page = $browser->newPage(); 

        $commentaire=$this->faker->sentence();
        $page->goto($_ENV['URL'].'/ifaces/login.php');
        $page->getByRole('textbox', [ 'name'=> 'Mail :' ])->fill($_ENV['ADMIN_MAIL']);
        $page->getByRole('textbox', [ 'name'=> 'Mot de passe :=' ])->fill($_ENV['ADMIN_PASS']);
        $page->getByRole('textbox', [ 'name'=> 'Mot de passe :=' ])->press('Enter');
        $page->getByRole('link', [ 'name'=> 'Points de vente' ])->first()->click();
        $page->locator('[href="../ifaces/ventes.php?numero=1"]')->click();
        $page->getByRole('button', [ 'name'=> 'D3E' ])->click();
        $page->getByRole('textbox', [ 'name'=> 'Prix unitaire:' ])->fill( (string) $this->faker->randomDigitNotNull());
        $page->getByRole('textbox', [ 'name'=> 'Masse unitaire:' ])->fill((string) $this->faker->randomDigitNotNull());
        $page->getByRole('textbox', [ 'name'=> 'Commentaire' ])->fill($commentaire);
        $page->getByRole('button', [ 'name'=> 'Ajouter' ])->click();
        $page->getByRole('button', [ 'name'=> 'Encaisser' ])->click();

        sleep(1);
      
        $stmt = $this->pdo->query("SELECT * FROM ventes WHERE commentaire ='".$commentaire."'");



        $fetch = $stmt->fetch();
        $this->assertNotFalse($fetch);

        if ($fetch) {
            $vente_id=$fetch['id'];
            $stmt = $this->pdo->query("SELECT * FROM vendus WHERE id_vente ='".$vente_id."'");
            $fetch = $stmt->fetch();
            $this->assertNotFalse($fetch);

            if ($fetch) {
                $vendu_id=$fetch['id'];
                $stmt = $this->pdo->query("SELECT * FROM pesees_vendus WHERE id = '".$vendu_id."'");
                $fetch = $stmt->fetch();
                $this->assertNotFalse($fetch);
            }
        }
        

    } 

    // Modification de la masse d'un objet
    public function  test_Modification_masse_objet_vendu()
    {
        $playwright = new Playwright(['browser' => 'chromium']);

        $browser = $playwright->launch(); 
        $page = $browser->newPage(); 

        $commentaire=$this->faker->sentence();
        $page->goto($_ENV['URL'].'/ifaces/login.php');
        $page->getByRole('textbox', [ 'name'=> 'Mail :' ])->fill($_ENV['ADMIN_MAIL']);
        $page->getByRole('textbox', [ 'name'=> 'Mot de passe :=' ])->fill($_ENV['ADMIN_PASS']);
        $page->getByRole('textbox', [ 'name'=> 'Mot de passe :=' ])->press('Enter');
        sleep(1);

        $page->goto($_ENV['URL'].'/ifaces/verif_vente.php?date1=01-01-2025&date2='.date("d-m-Y").'&numero=1');

        sleep(1);


        $page->locator('[action="modification_verification_vente.php?nvente=1"]')->click();
        

        sleep(1);

        $page->locator('[action="modification_verification_objet.php"]')->first()->click();


        sleep(1);

        // On modifie la valeur d'origine

        $page->locator('#masse')->fill("2.88");
        $page->locator('#masse')->press('Enter');

        sleep(1);

        $stmt = $this->pdo->query("SELECT * FROM pesees_vendus WHERE id = '1' and masse= '2.88'");
        $fetch = $stmt->fetch();
        $this->assertNotFalse($fetch);



        $page->locator('[action="modification_verification_vente.php?nvente=1"]')->click();

        sleep(1);

        $page->locator('[action="modification_verification_objet.php"]')->first()->click();


        sleep(1);


        // On retabli la valeur d'origine

        $page->locator('#masse')->fill("2.77");
        $page->locator('#masse')->press('Enter');

        
        sleep(1);

        

    } 

    public function  test_Modification_masse_objet_sorti()
    {
        $playwright = new Playwright(['browser' => 'chromium']);

        $browser = $playwright->launch(); 
        $page = $browser->newPage(); 

        $commentaire=$this->faker->sentence();
        $page->goto($_ENV['URL'].'/ifaces/login.php');
        $page->getByRole('textbox', [ 'name'=> 'Mail :' ])->fill($_ENV['ADMIN_MAIL']);
        $page->getByRole('textbox', [ 'name'=> 'Mot de passe :=' ])->fill($_ENV['ADMIN_PASS']);
        $page->getByRole('textbox', [ 'name'=> 'Mot de passe :=' ])->press('Enter');
        sleep(1);

        $page->goto($_ENV['URL'].'/ifaces/verif_sorties.php?date1=01-01-2025&date2='.date("d-m-Y").'&numero=1');

        sleep(1);


        $page->locator('[href="../ifaces/modification_verif_sorties.php?id=1"]')->click();
        

        sleep(1);

        $page->locator('[href="../ifaces/modification_verification_pesee_sorties.php?id=1"]')->click();


        sleep(1);

        // On modifie la valeur d'origine

        $masse=$this->faker->numberBetween(1, 25);

        echo $masse;
        

        $page->locator('#masse')->fill(strval($masse));
        $page->locator('#masse')->press('Enter');

        sleep(1);

        $stmt = $this->pdo->query("SELECT * FROM pesees_sorties WHERE id = '1' and masse='$masse'");
        $fetch = $stmt->fetch();
        $this->assertNotFalse($fetch);

    
        

    } 

    

}
