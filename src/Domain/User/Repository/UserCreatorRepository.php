<?php

namespace App\Domain\User\Repository;

use PDO;

/**
 * Repository.
 */
 final class UserCreatorRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Insert user row.
     *
     * @param array $user The user
     *
     * @return int The new ID
     */
    public function insertUser(array $user): string
    {
        $row = [
            'firmen_name' => $user['firmen_name'],
            'name_pruefer' => $user['name_pruefer'],
            'strasse' => $user['strasse'],
            'postleitzahl' => $user['postleitzahl'],
            'telefon' => $user['telefon'],
            'telefax' => $user['telefax'],
            'email' => $user['email'],
            'hersteller_von_messgeraet' => $user['hersteller_von_messgeraet'],
            'messgeraet_typ' => $user['messgeraet_typ'],
            'passwort' => $user['passwort']
        ];

        $sql = "INSERT INTO user SET 
                firmen_name=:firmen_name,  
                name_pruefer=:name_pruefer, 
                strasse=:strasse, 
                postleitzahl=:postleitzahl,  
                telefon=:telefon, 
                telefax=:telefax, 
                email=:email,
                hersteller_von_messgeraet=:hersteller_von_messgeraet,
                messgeraet_typ=:messgeraet_typ, 
                passwort=:passwort";
               
        $this->connection->prepare($sql)->execute($row);

     return $this->setToken($user['firmen_name']);
    }

    public  function generateToken(): string
    {
       return  bin2hex(openssl_random_pseudo_bytes(64));
    }

    public function getUser(string $firmenname): array
    {
        
        $row = ['firmen_name' => $firmenname];
        $sql = "SELECT * from user
        WHERE
        firmen_name =:firmen_name";
       $statemet =  $this->connection->prepare($sql);
       $statemet->execute($row);
       return $statemet->fetch();
    }


    public function setToken(string $firmenname): string
    {
        
        $row = ['genToken' => $this->generateToken(),
        'firmen_name' => $firmenname];

        $sql = "UPDATE user SET 
                token =:genToken
                WHERE
                firmen_name =:firmen_name   ";

        $this->connection->prepare($sql)->execute($row);

    return $this->getUser($firmenname)['token'];
    }


    public function resetToken(string $firmenname): string
    {
        
        $row = ['genToken' => null,
        'firmen_name' => $firmenname];

        $sql = "UPDATE user SET 
                token =:genToken
                WHERE
                firmen_name =:firmen_name   ";

        $this->connection->prepare($sql)->execute($row);

    return $this->getUser($firmenname)['token'];
    }
}

