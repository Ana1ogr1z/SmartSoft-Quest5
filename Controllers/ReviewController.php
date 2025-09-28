<?php

namespace Controllers;

use Controllers\Controller;
use Models\Review;

class ReviewController extends Controller
{
    /**
     * @var Review
     */
    private $review;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->review = new Review();
    }

     public function editAction(int $id): void
{
    error_log("Edit action called with ID: " . $id);
    $this->review->setId($id);
    echo $this->view();
}

    /**
     * Создание нового отзыва
     * @return void
     */
    public function insertAction(): void  
{
    error_log("Review insert action called");
    error_log("POST data: " . print_r($_POST, true));
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->review
            ->setName(htmlspecialchars($_POST['fullnameReview'] ?? ''))
            ->setAddress(htmlspecialchars($_POST['addressReview'] ?? '')) 
            ->setComment(htmlspecialchars($_POST['messageReview'] ?? ''))
            ->create();

        header('Location: /Quest5/index.php?route=/');
        exit;
    }
}

    /**
     * Обновление отзыва
     * @return void
     */
    public function updateAction(): void
{
    error_log("Review update action called");
    error_log("POST data: " . print_r($_POST, true));
    
    $id = (int) ($_POST['id'] ?? 0);

    if ($id) {
        $this->review
            ->setName(htmlspecialchars($_POST['name'] ?? ''))
            ->setComment(htmlspecialchars($_POST['comment'] ?? ''))
            ->setAddress(htmlspecialchars($_POST['address'] ?? ''))
            ->setId($id)
            ->update();
            
        error_log("Review updated successfully");
    } else {
        error_log("Error: No ID provided for update");
    }

    header('Location: /Quest5/index.php?route=/');
    exit;
}

    /**
     * Удаление отзыва
     * @return void
     */
     public function deleteAction(): void 
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id) {
            $this->review
                ->setId($id)
                ->delete();
        }

        header('Location: /Quest5/index.php?route=/');
        exit;
    }

    /**
     * {@inheritDoc}
     */
    protected function layout(): string
{
    $id = $this->review->getId(); // Используем ID из объекта
    error_log("Layout called with review ID: " . $id);
    
    $review = [];
    if ($id) {
        $review = $this->review->findOne((string)$id);
        error_log("Found review: " . print_r($review, true));
    }

    return $this->getForm($review);
}

    /**

     * @param array $review
     * @return string
     */
    private function getForm(array $review): string
    {
        return $this->template('Templates/ReviewLayout/EditForm.php', ['review' => $review]);
    }

    /**
     * Получение объекта Review
     * @return Review
     */
    public function getReview(): Review
    {
        return $this->review;
    }
}
?>