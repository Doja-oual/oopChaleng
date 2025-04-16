
<?php


interface ReservableInterface
{
    public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation;
}


abstract class Vehicule implements ReservableInterface
{
    protected int $id;
    protected string $immatriculation;
    protected string $marque;
    protected string $modele;
    protected float $prixJour;
    protected bool $disponible;

    public function __construct( int $id,string $immatriculation,string $marque, string $modele,float $prixJour,bool $disponible
    ) {
        $this->id = $id;
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prixJour = $prixJour;
        $this->disponible = $disponible;
    }


     abstract public function getType():string;

     public function afficherDetails(): void
     {
         echo "id: $this->id";
         echo "immatriculation: $this->immatriculation";
         echo "Marque: $this->marque";
         echo "Modele: $this->modele";
         echo "Prix : $this->prixJour";
         echo "Disponible: " . ($this->disponible ? "Oui" : "Non") ;
     }
     public function calculerPrix(int $jours): float
     {
         return $jours * $this->prixJour;
     }
 
     public function estDisponible(): bool
     {
         return $this->disponible;
     }
  
 
 }

  class Voiture extends Vehicule
    {
        private int $nbPortes;
        private string $transmission;
    
        public function __construct( int $id, string $immatriculation,string $marque, string $modele, float $prixJour,int $nbPortes, string $transmission
        ) {
            {
                parent::__construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible);
                $this->nbPortes = $nbPortes;
                $this->transmission = $transmission;
            }}
        
            public function getType(): string
            {
                return "Voiture";
            }
            public function afficherDetails(): void
            {
                parent::afficherDetails();
                echo "nombre: $this->nbPortes";
                echo "Transmission: $this->transmission\n";
            }
        
            public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation
            {
                return new Reservation( $client,  $dateDebut, $nbJours);
            }
        }
             class Moto extends Vehicule{
                protected $cylindree;

                public function __construct( int $id, string $immatriculation,string $marque, string $modele, float $prixJour,int $cylindree
                ) {
                    {
                        parent::__construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible);
                        $this->cylindree = $cylindree;
                        
                    }}

                    public function getType(): string
            {
                return "Moto";
            }
            public function afficherDetails(): void
            {
                parent::afficherDetails();
                echo "cylindree: $this->cylindree";
               
            }
        
            public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation
            {
                return new Reservation($client, $nbJours);
            }


            
             }

     class Camion extends Vehicule
    {
        protected int $capaciteTonnage;
       
    
        public function __construct( int $id, string $immatriculation,string $marque, string $modele, float $prixJour, string $capaciteTonnage
        ) {
            {
                parent::__construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible);
                $this->capaciteTonnage=$capaciteTonnage;
            }}
        
            public function getType(): string
            {
                return "camion";
            }
            public function afficherDetails(): void
            {
                parent::afficherDetails();
                echo "nombre: $this-> capaciteTonnage";
               
            }
        
            public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation
            {
                return new Reservation($client,$dateDebut, $nbJours);
            }
        }

       //p3
       
       abstract class Personne
       {
           protected string $nom;
           protected string $prenom;
           protected string $email;
       
           public function __construct(string $nom, string $prenom, string $email)
           {
               $this->nom = $nom;
               $this->prenom = $prenom;
               $this->email = $email;
           }
       
           abstract public function afficherProfil(): void;
       }
       
       


       class Client extends Personne
       {
           private string $numeroClient;
           private array $reservations;
       
           public function __construct(string $nom, string $prenom, string $email, string $numeroClient)
           {
               parent::__construct($nom, $prenom, $email);
               $this->numeroClient = $numeroClient;
               $this->reservations = [];
           }
       
           public function ajouterReservation(Reservation $r): void
           {
               $this->reservations[] = $r;
           }
       
           public function afficherProfil(): void
           {
               echo "nom: $this->nom";
               echo "Prneom: $this->prenom";
               echo "email: $this->email";
               echo "Numro Client: $this->numeroClient";
           }
       
           public function getHistorique(): array
           {
               return $this->reservations;
           }
       }
       
        //p4

          class Agence {

            protected string $nom;
            protected string $ville;
            private array $vehicules ;
            private array $clients;

            public function __construct(string $nom,string $ville)
            {
                $this->nom=$nom;
                $this->ville=$ville;
                $this->vehicules = [];
                $this->clients = [];
            }

          
            
                public function ajouterVehicule(Vehicule $v): void
                {
                    $this->vehicules[] = $v;
                }
            
                public function rechercherVehiculeDisponible(string $type)
                {
                    foreach ($this->vehicules as $vehicule) {
                        if ($vehicule->getType() === $type && $vehicule->estDisponible()) {
                            return $vehicule;
                        }
                    }
                    return null; 
                }
            
                public function enregistrerClient(Client $c): void
                {
                    $this->clients[] = $c;
                }
            
                public function faireReservation(Client $client,Vehicule $v,DateTime $dateDebut,int $nbJours): Reservation {
                  
            
                    $reservation = $v->reserver($client, $dateDebut, $nbJours);
                    $client->ajouterReservation($reservation);
                    return $reservation;
                }
            }

            class Reservation
            {
                protected  $vehicule;
                protected  $client;
                protected  $dateDebut;
                private int $nbJours;
                protected string $statut; 
            
                public function __construct( Vehicule $vehicule, Client $client, DateTime $dateDebut,int $nbJours
                ) {
                    $this->vehicule = $vehicule;
                    $this->client = $client;
                    $this->dateDebut = $dateDebut;
                    $this->nbJours = $nbJours;
                    $this->statut = "attende";
                }
            
                public function calculerMontant(): float
                {
                    return $this->vehicule->calculerPrix($this->nbJours);
                }
            
                public function confirmer(): void
                {
                    $this->statut = "Confirm";
                    $this->vehicule->disponible = false; 
                }
            
                public function annuler(): void
                {
                    $this->statut = "Annule";
                    $this->vehicule->disponible = true; 
                }
            
               
            }
            

 $agenceParis = new Agence("Agence Paris", "Paris");
$agenceCasablanca = new Agence("Agence Casablanca", "Casablanca");

            

$voitureParis = new Voiture(1, "AA-123-BB", "toyota", "208", 50, 5, "Manuelle");
$motoParis = new Moto(2, "Cr-456-rD", "Yamaha", "YZF", 30, 600);
$camionParis = new Camion(3, "DD44G", "Renault", "TFF", 100, 5);
$agenceParis->ajouterVehicule($voitureParis);
$agenceParis->ajouterVehicule($motoParis);
$agenceParis->ajouterVehicule($camionParis);


$voitureCasa = new Voiture(4, "GG1223", "Toyota", "Corolla", 60, 4, "Automatique");
$motoCasa = new Moto(5, "EE33-222-JJDF", "Honda", "CC", 40, 1000);
$camionCasa = new Camion(6, "KK-3FFFL", "Volvo", "CCC", 120, 10);
$agenceCasablanca->ajouterVehicule($voitureCasa);
$agenceCasablanca->ajouterVehicule($motoCasa);
$agenceCasablanca->ajouterVehicule($camionCasa);




$client1 = new Client("Doja", "oualla", "dojaOualaa@mail.com", "A1");
$client2 = new Client("Fati", "oualla", "fatioualla@mail.com", "A2");
$agenceParis->enregistrerClient($client1);
$agenceCasablanca->enregistrerClient($client2);

  
$reservation1 = $agenceParis->faireReservation($client1, $voitureParis, new DateTime("2025-04-16"), 3);
$reservation1->confirmer();


$reservation2 = $agenceParis->faireReservation($client1, $voitureParis, new DateTime("2025-04-20"), 2);
$reservation2->annuler();


      
           



       


             

 
            
       

        
           
        
        
 

