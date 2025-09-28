<?php

namespace Controllers;

use Controllers\Controller;
use Models\User;

class UserController extends Controller
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $id = 0;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @param int $id
     * @return void
     */
    public function editAction(int $id): void
    {
        echo $this
            ->setId($id)
            ->view();
    }

    /**
     * {@inheritDoc}
     */
    protected function layout(): string
    {
        $user = $this->getUser();
        $id = $this->getId();

        $user = $user->findOne($id);

        return $this->getForm($user);
    }

    /**
     * @return User
     */
    private function getRecordedUser(): User
    {
        return $this
            ->getUser()
            ->setName(htmlspecialchars($_POST['name'] ?? ''))
            ->setSurname(htmlspecialchars($_POST['surname'] ?? ''))
            ->setEmail(htmlspecialchars($_POST['email'] ?? ''))
            ->setMessage(htmlspecialchars($_POST['message'] ?? ''));
    }

    /**
     * @return void
     */
    public function insertAction(): void
    {

        error_log("POST data: " . print_r($_POST, true));
        
        $user = $this->getRecordedUser();
        error_log("User object before insert: " . print_r($user, true));
        
        $user->insert();

        header('Location: /Quest5/index.php?route=/');
        exit;
    }

    /**
     * @return void
     */
    public function updateAction(): void
    {
        $this
            ->getRecordedUser()
            ->setId((int) ($_POST['id'] ?? 0))
            ->update();

        header('Location: /Quest5/index.php?route=/');
        exit;
    }

    

    /**
     * @param array $user
     * @return string
     */
    private function getForm(array $user): string
    {
        return $this->template('Templates/UserLayout/EditForm.php', ['user' => $user]);
    }

    public function delete(): void
        {
            $id = $this->getId();

            $this
                ->getUser()
                ->setId($id)
                ->delete();
        }

        /**
         * @return void
         */
        public function deleteAction(): void
        {
            error_log("Delete action called");
            $id = (int) ($_POST['id'] ?? 0);
            error_log("User ID: " . $id);
            
            if ($id > 0) {
                $this->setId($id)->delete();
                error_log("User deleted");
            }

            header('Location: /Quest5/');
            exit;
        }

    /**
     * @return string
     */
    public function addAction(): string
    {
        return $this->header() .
            $this->addLayout() .
            $this->footer();
    }

    /**
     * @return string
     */
    protected function addLayout(): string
    {
        return $this->getAddForm();
    }

    /**
     * @return string
     */
    protected function getAddForm(): string
    {
        return $this->template('Templates/UserLayout/AddForm.php');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User
     */
    private function getUser(): User
    {
        return $this->user;
    }
}

?>
