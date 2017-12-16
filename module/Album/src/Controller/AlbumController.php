<?php
namespace Album\Controller;

// Add the following import:
use Album\Model\AlbumTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
// Add the following import statements at the top of the file:
use Album\Form\AlbumForm;
use Album\Model\Album;


class AlbumController extends AbstractActionController
{
    // Add this property:
    private $table;

    // Add this constructor:
    public function __construct(AlbumTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
       return new ViewModel([
        'albums' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        return $this->redirect()->toRoute('album');
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}