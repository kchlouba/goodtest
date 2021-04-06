<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Utils\Json;
use \Firebase\JWT\JWT;



final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $jwt;
    private $key = "zabiprinc";


    public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

    public function startup()
    {
        parent::startup();
        if (isset($_COOKIE['jwt'])) {
                $decoded = JWT::decode($_COOKIE['jwt'],$this->key, ['HS256']);
                $this->jwt = (array) $decoded;
        }
    }

    public function actionDefault($page=1): void
    {
        
        $table = $this->database->table('users');

        if (isset($_POST['login'])) {
            $table->where('login', $_POST['login']);
            foreach ($table as $pk) {
            $pkk = $pk->Pass;
            }
            if ($pkk == $_POST['pass']) {

                // vytvoření jwt
                $this->jwt = array (
                    'login' => $_POST['login'],
                    'pass' => $_POST['pass'],
                  );
                  
                  $token = JWT::encode($this->jwt, $this->key);
                  setcookie('jwt', $token, time()+3600);
            }
        } 
        
            // kontrola jwt
            if ($this->jwt) {
                
                $table = $this->database->table('users');
                $table->where('login', $this->jwt['login']);
                    foreach ($table as $pk) {
                    $pkk = $pk->Pass;
                    }
                    if ($pkk == $this->jwt['pass']) {

                        try {
                            $table = $this->database->table('users');
                            $total = $table->count('*');
                            
                            $table = $this->database->table('users');
                            $table->page($page,5);
                               $pages = intval(ceil($total / 5));
                       
                       
                            if ($page > $pages) {
                               throw new \Exception('Stránek je jen '.$pages);
                           }
                                     
                            $data = $table->fetchAssoc('[]');
                               } catch(\Exception $e) {
                                   $data = ['error'=>$e->getMessage()];
                                   }
                                   $this->sendJson($data+['total users'=>$total]+['pages'=>$pages]);

            }
        } else {
            $this->sendJson(['status'=>'blocked', 'data'=>'nemate pristup']);

        }


	
    }

}
