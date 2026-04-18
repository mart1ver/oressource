<?php
use PHPUnit\Framework\TestCase;

use PDO as PDO;
use Dotenv\Dotenv;


// Tests des fonctions appellées dans le menu Gestion
// Ces tests permettent également d'initialier la base de donnée avec le minimum necessaire pour ensuite enregistrer une vente

class GestionTest extends TestCase {
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

        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . "/../api/");
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . "/../core/");
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . "/../ifaces/");
        set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . "/../moteur/");
        
        require_once  'configs.php';
        require_once  'session.php';
        require_once  'composants.php';

        $this->faker = Faker\Factory::create('fr_FR');


    }

    protected function tearDown(): void {
       // $this->pdo->rollBack();

    }

    public function test_PointDeVenteCreate() {

        $data=[
            'nom'=>$this->faker->company(),
            'adresse'=>$this->faker->address(),
            'surface_vente'=>$this->faker->numberBetween(1, 1000),
            'description'=>$this->faker->sentence(),
            'couleur'=>$this->faker->hexColor(),
            'createur'=>'1'
        ];

        generic_insert_5Config($this->pdo, 'points_vente', 'nom', 'adresse', 'surface_vente', $data);


        $stmt = $this->pdo->query("SELECT * FROM points_vente WHERE nom ='".$data['nom']."'");
        $fetch = $stmt->fetch();
        $this->assertNotFalse($fetch);
        
    }
     
    public function test_PointDeCollecteCreate() {

        $data=[
            'nom'=>$this->faker->company(),
            'adresse'=>$this->faker->address(),
            'pesee_max'=>$this->faker->numberBetween(1, 500),
            'description'=>$this->faker->sentence(),
            'couleur'=>$this->faker->hexColor(),
            'createur'=>'1'
        ];

        generic_insert_5Config($this->pdo, 'points_collecte', 'nom', 'adresse', 'pesee_max', $data);
       
    
        $stmt = $this->pdo->query("SELECT * FROM points_collecte WHERE nom ='".$data['nom']."'");
        $fetch = $stmt->fetch();
        $this->assertNotFalse($fetch);
        
    }
     

    public function test_PointDeSortieCreate() {

        $data=[
            'nom'=>$this->faker->company(),
            'adresse'=>$this->faker->address(),
            'pesee_max'=>$this->faker->numberBetween(1, 500),
            'description'=>$this->faker->sentence(),
            'couleur'=>$this->faker->hexColor(),
            'createur'=>'1'
        ];

        generic_insert_5Config($this->pdo, 'points_sortie', 'nom', 'adresse', 'pesee_max', $data);



        $stmt = $this->pdo->query("SELECT * FROM points_sortie WHERE nom ='".$data['nom']."'");
        $fetch = $stmt->fetch();
        $this->assertNotFalse($fetch);
  
    }
     

    public function test_TypeObjetsCollectesCreate() {



        $stmt = $this->pdo->query("SELECT * FROM type_dechets WHERE nom ='D3E'");
        $fetch = $stmt->fetch();

        if ($fetch) {

            $datas = [
                [
                    'nom'=>$this->faker->word(),
                    'description'=>$this->faker->sentence(),
                    'couleur'=>$this->faker->hexColor(),
                    'createur'=>'1'

                ],
                [
                    'nom'=>$this->faker->word(),
                    'description'=>$this->faker->sentence(),
                    'couleur'=>$this->faker->hexColor(),
                    'createur'=>'1'

                ]
            ];
        }   
        
        else {
            $datas = [
                [
                    'nom'=>'D3E',
                    'description'=>$this->faker->sentence(),
                    'couleur'=>$this->faker->hexColor(),
                    'createur'=>'1'

                ],
                [
                    'nom'=>$this->faker->word(),
                    'description'=>$this->faker->sentence(),
                    'couleur'=>$this->faker->hexColor(),
                    'createur'=>'1'

                ]
            ];

        }

        foreach ($datas as $data) {

            generic_insert_config($this->pdo, 'type_dechets', $data);
        
            $stmt = $this->pdo->query("SELECT * FROM type_dechets WHERE nom ='". $data['nom']."'");
            $fetch = $stmt->fetch();
            $this->assertNotFalse(condition: $fetch);
           
        }
    }


         

    public function test_TypeObjetsEvacueesCreate() {

        $datas = [
            [
                'nom'=>$this->faker->word(),
                'description'=>$this->faker->sentence(),
                'couleur'=>$this->faker->hexColor(),
                'createur'=>'1'

            ],
            [
                'nom'=>$this->faker->word(),
                'description'=>$this->faker->sentence(),
                'couleur'=>$this->faker->hexColor(),
                'createur'=>'1'

            ]
        ];


        foreach ($datas as $data) {
            generic_insert_config($this->pdo, 'type_dechets_evac', $data);
        
            $stmt = $this->pdo->query("SELECT * FROM type_dechets_evac WHERE nom ='". $data['nom']."'");
            $fetch = $stmt->fetch();
            $this->assertNotFalse(condition: $fetch);
            $this->assertEquals( $data['nom'], $fetch['nom']);
        }
    }
     

    public function test_TypePoubellesCreate() {

    // Pas d'API pour les types de poubelles, insert simple 



    $data=[
        'ultime'=>1,
        'nom'=>$this->faker->word(),
        'description'=>$this->faker->sentence(),
        'masse_bac'=>$this->faker->numberBetween(1, 500),
        'couleur'=>$this->faker->hexColor(),
        'createur'=>'1'
    ];

    
    $stmt = $this->pdo->prepare('INSERT INTO types_poubelles (nom, couleur, description, masse_bac, ultime, id_createur, id_last_hero) VALUES (?, ?, ?,  ?, ?, ?, ?)');

    $stmt->execute([$data['nom'], $data['couleur'], $data['description'], $data['masse_bac'], $data['ultime'], $data['createur'],$data['createur'] ]);
    $stmt->closeCursor();
    
     
    }


    public function test_ConventionsAvecPartenairesCreate() {

        $data=[
            'nom'=>$this->faker->word(),
            'description'=>$this->faker->sentence(),
            'couleur'=>$this->faker->hexColor(),
            'createur'=>'1'
        ];

        generic_insert_config($this->pdo, 'conventions_sorties', $data);



    }
}
