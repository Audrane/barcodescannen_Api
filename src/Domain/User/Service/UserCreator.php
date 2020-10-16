<?php

namespace App\Domain\User\Service;


use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UserCreator
{
    /**
     * @var UserCreatorRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UserCreatorRepository $repository The repository
     */
    public function __construct(UserCreatorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new user.
     *
     * @param array $data The form data
     *
     * @return int The new user ID
     */
    public function createUser(array $data): string
    {
        // Input validation
        $this->validateNewUser($data);

       $data =  $this->hashPassord($data);
        // Insert user
        $userId = $this->repository->insertUser($data);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }

    public function loginUser(array $data):array
    {
        $result  = ['token' => '',
                    'succeed' => false];
        $PEPPER = 'V0#a*g)16ovAAsyebG4Vc8YrVamtYFWt*N=B';
        $pepered_pwd = $data['passwort'].$PEPPER;
        $user = $this->repository->getUser($data['firmen_name']);
        if($user == null || empty($user))
        return $result;
        
        $correctPwd = $user['passwort'];

        if(password_verify($pepered_pwd, $correctPwd))
          {
              $result['token'] = $this->repository->setToken($data['firmen_name']);
              $result['succeed'] = true;
          }
         return $result;
    }
    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateNewUser(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (empty($data['firmen_name'])) {
            $errors['firmen_name'] = 'Input required';
        } 

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }

    private function hashPassord(array $data): array 
    {
        $COST = 12;
        $PEPPER = 'V0#a*g)16ovAAsyebG4Vc8YrVamtYFWt*N=B';
        $password = $data['passwort'];
        $data['passwort']=  password_hash($password . $PEPPER, PASSWORD_BCRYPT, [
            'cost' => $COST
            ]);

            return $data;
       
    }
}